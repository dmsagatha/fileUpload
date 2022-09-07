<?php

use App\Http\Controllers\{LeadController, ImportsController};
use App\Http\Controllers\Imports\ImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LeadController::class)->group(function () {
  Route::get('import-leads', 'index')->name('import.index');
  Route::post("parse-csv", "importLeads")->name('import.parse-csv');
});

Route::controller(ImportsController::class)->group(function () {
  Route::get('usuarios', 'index')->name('users.upload');
  Route::post("importarUsuarios", "importUsers")->name('users.import');

  Route::post("importar-usuarios", "import2")->name('users.import2');
});

/**
 * https://github.com/LaravelDaily/Laravel-8-Import-CSV
 * Revisar: Laravel Excel: Import with Relationships
 * https://www.youtube.com/watch?v=n2WOag1G7Zg
 */
Route::controller(ImportController::class)->group(function () {
  Route::get('/daily-usuarios', 'getImport')->name('import');
  Route::post('/import_parse', 'parseImport')->name('import_parse');
  Route::post('/import_process', 'processImport')->name('import_process');
});