<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{LeadController, ImportsController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
});