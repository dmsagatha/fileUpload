<?php

namespace App\Http\Controllers\Imports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
  public function getImport()
  {
    return view('imports.users');
  }

  public function parseImport(CsvImportRequest $request)
  {
    // Obtener el archivo CSV
    $path = $request->file('csv_file')->getRealPath();

    // Analizar el CSV en una matriz de filas y cada fila tendrá una matriz de columnas
    $data = array_map('str_getcsv', file($path));

    // Representarlo como una tabla y darle al usuario una opción de campos
    // Mostrar solo las dos primeras líneas
    $csv_data = array_slice($data, 0, 2);

    return view('import_fields', compact('csv_data'));
  }
}
