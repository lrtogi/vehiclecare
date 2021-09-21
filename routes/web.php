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
    Route::get('admin/home', 'AdminController@index')->name('admin/home');
    Route::get('admin/companyList/get/search/{active}/{approved}', 'AdminController@getSearch')->name('admin/companyList/get/search');
    Route::get('admin/getPendingCompany', 'AdminController@getPendingCompany')->name('admin/getPendingCompany');
    Route::post('admin/approveCompany', 'AdminController@approveCompany')->name('admin/approveCompany');
    Route::post('admin/rejectCompany', 'AdminController@rejectCompany')->name('admin/rejectCompany');
});

Route::group(['namespace' => 'Master', 'middleware' => 'isAdmin'], function() {
    //vehicle type
    Route::get('admin/vehicleType', 'VehicleController@index')->name('admin/vehicleType');
    Route::get('admin/vehicleType/showForm/{id?}', 'VehicleController@showForm')->name('admin/vehicleType/showForm');
    Route::post('admin/vehicleType/save', 'VehicleController@store')->name('admin/vehicleType/save');
    Route::post('admin/vehicleType/delete', 'VehicleController@delete')->name('admin/vehicleType/delete');

    //company
    Route::get('admin/company', 'CompanyController@index')->name('admin/company');
    Route::get('admin/company/showForm/{id?}', 'CompanyController@showForm')->name('admin/company/showForm');
    Route::post('admin/company/save', 'CompanyController@store')->name('admin/company/save');
    Route::post('admin/company/void', 'CompanyController@void')->name('admin/company/void');
    Route::post('admin/company/unvoid', 'CompanyController@unvoid')->name('admin/company/unvoid');
    Route::get('admin/company/getUser/{user_type}', 'CompanyController@getUser')->name('admin/company/getUser');
});