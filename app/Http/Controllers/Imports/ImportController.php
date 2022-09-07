<?php

namespace App\Http\Controllers\Imports;

use App\Models\User;
use App\Models\CsvData;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CsvImportRequest;
use Maatwebsite\Excel\HeadingRowImport;

class ImportController extends Controller
{
  public function getImport()
  {
    return view('imports.import');
  }

  public function parseImport(CsvImportRequest $request)
  {
    if ($request->has('header')) {
      $headings = (new HeadingRowImport)->toArray($request->file('csv_file'));
      $data = Excel::toArray(new UsersImport, $request->file('csv_file'))[0];
    } else {
      // Obtener el archivo CSV
      $data = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()));
    }

    if (count($data) > 0) {
      /* 
        Representarlo como una tabla y darle al usuario una opción de campos
        Mostrar solo las dos primeras líneas
      */
      $csv_data = array_slice($data, 0, 2);

      /*
        Almacenar datos completos en la tabla CsvData
        Guardar los datos del archivo en la base de datos con json_encode()
        y pasar el resultado a la vista.
        Luego, en el formulario import_fields.blade.php, mostrar qué archivo queremos procesar, especificando su ID como un campo oculto
      */
      $csv_data_file = CsvData::create([
        'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
        'csv_header'   => $request->has('header'),
        'csv_data'     => json_encode($data)
      ]);
    } else {
      return redirect()->back();
    }

    return view('imports.import_fields', [
      'headings' => $headings ?? null,
      'csv_data' => $csv_data,
      'csv_data_file' => $csv_data_file
    ]);
  }

  public function processImport(Request $request)
  {
    $data     = CsvData::find($request->csv_data_file_id);
    $csv_data = json_decode($data->csv_data, true);   // Resultado de matriz

    /*foreach ($csv_data as $row) {
        $user = new User();
        foreach (config('app.db_fields') as $index => $field) {
            if ($data->csv_header) {
                $user->$field = $row[$request->fields[$field]];
            } else {
                $user->$field = $row[$request->fields[$index]];
            }
        }
        $user->save();
    }*/

    // Validaciones de los campos únicos

        foreach ($csv_data as $row) {
          User::updateOrCreate(
            ['email' => $row['email']],
            [
              'email'    => $row['email'],
              'name'     => $row['name'],
              'password' => bcrypt($row['password']),
            ]
          );
        }
    /* foreach ($csv_data as $row) {
      User::updateOrCreate(
        ['email' => $row['email']],
        [
          'email'    => $row['email'],
          'name'     => $row['name'],
          'password' => $row['password'],
        ]
        ['email' => $row['Correo Electronico']],
        [
          'email'    => $row['Correo Electronico'],
          'name'     => $row['Nombre Completo'],
          'password' => $row['Clave'],
        ]
      ); 
    }*/

    return to_route('import')->with('success', 'Importación finalizada.');
  }
}