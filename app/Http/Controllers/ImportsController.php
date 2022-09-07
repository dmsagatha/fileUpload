<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ImportsController extends Controller
{
  public function index()
  {
    return view('importUsers');
  }

  /**
   * https://www.codecheef.org/article/laravel-9-upload-csv-file-example-without-packages
   */
  private $rows = [];

  public function importUsers(Request $request)
  {
    $this->validate($request, [
      'file' => 'required|file|mimes:csv'
    ]);

    $path    = $request->file('file')->getRealPath();
    $records = array_map('str_getcsv', file($path));

    if (!count($records) > 0)
    {
      return 'Error...';
    }
    
    // Obtener los nombres de campo de la columna de encabezado
    $fields = array_map('strtolower', $records[0]);

    // Eliminar la columna de encabezado
    array_shift($records);

    // Recorrer los datos del archivo
    foreach ($records as $record)
    {
      if (count($fields) != count($record))
      {
        return 'Carga de datos Csv no válidos.';
      }

      // Decodificar entidades html no deseadas
      $record = array_map("html_entity_decode", $record);

      // Establecer el nombre del campo como clave
      $record = array_combine($fields, $record);

      // Obtener los datos limpios
      $this->rows[] = $this->clear_encoding_str($record);
    }

    foreach ($this->rows as $data)
    {
      // User::updateOrCreate([
        /* 'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => bcrypt($data['password']), */

        /* "first_name"      => $data['first_name'],
        "last_name"       => $data['last_name'],
        "full_name"       => $data["full_name"],
        "email"           => $data["email"],
        "phone"           => str_replace("'", "", $data["phone"]),
        "alternate_phone" => $data["alternate_phone"],
        "address"         => $data["address"],
        "city"            => $data["city"],
        "requirement"     => $data["requirement"], */
      // ]);
      User::updateOrCreate(
        ['email' => $data['email']],
        [
          'name'     => $data['name'],
          'email'    => $data['email'],
          'password' => bcrypt($data['password']),
        ]
      );
    }

    return to_route('users.upload');
  }

  private function clear_encoding_str($value)
  {
    if (is_array($value))
    {
      $clean = [];

      foreach ($value as $key => $val)
      {
        $clean[$key] = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
      }
      return $clean;
    }
    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
  }

  /**
   * Sin encabezados
   * https://www.youtube.com/watch?v=nGcW4jR9vLg
   */
  public function import2(Request $request)
  {
    $this->validate($request, [
      'csv_file' => 'required|file|mimes:csv'
    ]);

    $users = User::pluck('email')->toArray();

    $file = fopen($request->csv_file->getRealPath(), 'r');

    while ($csvColumn = fgetcsv($file)) {
      if (!in_array($csvColumn[2], $users)) {
        User::firstOrCreate(
          ['email' => $csvColumn[1]],
          [
            'name'     => $csvColumn[0],
            'email'    => $csvColumn[1],
            'password' => bcrypt($csvColumn[2]),
          ]
        );
      }
    }

    fclose($file);

    return to_route('users.upload');
  }
}