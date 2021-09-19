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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', ['uses' => 'HomeController@index'])->name('home');

Route::group(['namespace' => 'Admin', 'middleware' => 'isAdmin'], function() {
    Route::get('admin/home', 'AdminController@index')->name('admin.home');
    Route::get('admin/companyList/get/search/{active}/{approved}', 'AdminController@getSearch')->name('admin.companyList.search');
    Route::get('admin/getPendingCompany', 'AdminController@getPendingCompany')->name('admin.getPendingCompany');
});