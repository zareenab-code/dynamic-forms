<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\FormController::class, 'index']);
Route::get('/form/{form}', [App\Http\Controllers\FormController::class, 'getForm'])->name('form');
Route::post('/form/{form}', [App\Http\Controllers\FormController::class, 'postForm']);

Route::prefix('admin')->name('admin.')->group(function () {

    Route::redirect('/', '/admin/login');

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/form/create', [App\Http\Controllers\FormController::class, 'getCreateForm'])->name('form.create');
    Route::post('/form/create', [App\Http\Controllers\FormController::class, 'postCreateForm']);
});
