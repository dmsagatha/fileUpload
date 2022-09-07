<?php

namespace App\Http\Controllers\Imports;

use App\Models\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ImportController extends Controller
{
  public function getImport()
  {
    return view('imports.import');
  }

  public function parseImport(CsvImportRequest $request)
  {
    // Obtener el archivo CSV
    $path = $request->file('csv_file')->getRealPath();

    // Analizar el CSV en una matriz de filas y cada fila tendrá una matriz de columnas
    $data = array_map('str_getcsv', file($path));

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

    /* 
      Representarlo como una tabla y darle al usuario una opción de campos
      Mostrar solo las dos primeras líneas
    */
    $csv_data = array_slice($data, 0, 2);

    return view('imports.import_fields', compact('csv_data'));
  }

  public function processImport(Request $request)
  {
    $data = CsvData::find($request->csv_data_file_id);
    $csv_data = json_decode($data->csv_data, true);   // Resultado de matriz

    foreach ($csv_data as $row) {
      $user = new User();

      foreach (config('app.db_fields') as $index => $field) {
        $user->$field = $row[$request->fields[$index]];
      }
      $user->save();
    }

    return view('import_success');
  }
}
