<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes(['register'=> false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/student-create', [App\Http\Controllers\HomeController::class, 'studentCreate'])->name('studentCreate');
Route::post('/student-form-save', [App\Http\Controllers\HomeController::class, 'studentFormSave'])->name('studentFormSave');
Route::get('/student-delete/{id}', [App\Http\Controllers\HomeController::class, 'studentDelete'])->name('studentDelete');
Route::get('/student-view/{id}', [App\Http\Controllers\HomeController::class, 'studentViewData'])->name('studentViewData');
Route::get('/student-edit/{id}', [App\Http\Controllers\HomeController::class, 'studentEdit'])->name('studentEdit');
Route::post('/student-edit/{id}', [App\Http\Controllers\HomeController::class, 'studentUpdate'])->name('studentUpdate');

Route::get('download/{id}', [App\Http\Controllers\HomeController::class, 'resumeDownloadFile'])->name('resumeDownload');




