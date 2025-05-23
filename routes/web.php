<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NoteController; 
use App\Http\Controllers\AttachmentController; 
use App\Http\Controllers\HomeController; 

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home'); // Updated to use HomeController for dashboard data

// Company Resource Routes
Route::resource('companies', CompanyController::class);
Route::get('companies/export/csv', [CompanyController::class, 'exportCsv'])->name('companies.export.csv'); // Export route

// Employee Resource Routes
Route::resource('employees', EmployeeController::class);
Route::get('employees/export/csv', [EmployeeController::class, 'exportCsv'])->name('employees.export.csv'); // Export route

// Notes Routes 
Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

// Attachments Routes 
Route::post('attachments', [AttachmentController::class, 'store'])->name('attachments.store');
Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
