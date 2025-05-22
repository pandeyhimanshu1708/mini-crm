<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); // Laravel UI authentication routes

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Company Resource Routes (protected by 'auth' middleware in controller constructor)
Route::resource('companies', CompanyController::class);

// Employee Resource Routes (protected by 'auth' middleware in controller constructor)
Route::resource('employees', EmployeeController::class);