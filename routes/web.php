<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LeadController, ImportsController};

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