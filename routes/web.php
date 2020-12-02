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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/logout', 'HomeController@logout');
    Route::resource('kategori-pengeluaran', 'ExpenseCategoryController');
    Route::resource('pengeluaran', 'ExpenseController');
    Route::resource('pemasukan', 'IncomeController');

    // api
    Route::get('/api/kategori', 'ExpenseCategoryController@data')->name('api.kategori');
    Route::get('/api/pengeluaran', 'ExpenseController@data')->name('api.pengeluaran');
    Route::get('/api/pemasukan', 'IncomeController@data')->name('api.pemasukan');
});


Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
