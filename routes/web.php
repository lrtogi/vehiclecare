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

Route::post('changePassword', 'Master\UserController@changePassword')->name('changePassword')->middleware('auth:sanctum');

Route::group(['namespace' => 'Admin', 'middleware' => 'isAdmin'], function () {
    Route::get('admin/home', 'AdminController@index')->name('admin/home');
    Route::get('admin/companyList/get/search/{active}/{approved}', 'AdminController@getSearch')->name('admin/companyList/get/search');
    Route::get('admin/getDashboard', 'AdminController@getDashboard')->name('admin/getDashboard');
    Route::post('admin/approveCompany', 'AdminController@approveCompany')->name('admin/approveCompany');
    Route::post('admin/rejectCompany', 'AdminController@rejectCompany')->name('admin/rejectCompany');
});

Route::group(['namespace' => 'Master', 'middleware' => 'isAdmin'], function () {
    Route::get('changePassword', 'UserController@changePasswordFormAdmin')->name('changePassword');
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

    //user
    Route::get('admin/user', 'UserController@index')->name('admin/user');
    Route::get('admin/user/showForm/{id?}', 'UserController@showForm')->name('admin/user/showForm');
    Route::post('admin/user/save', 'UserController@store')->name('admin/user/save');
    Route::post('admin/user/delete', 'UserController@delete')->name('admin/user/delete');
    Route::get('admin/user/getCompany', 'UserController@getCompany')->name('admin/user/getCompany');
});

Route::group(['middleware' => 'isCompany'], function () {

    Route::get('home', 'HomeController@index')->name('home');
    Route::get('getDashboard', 'HomeController@getDashboard')->name('getDashboard');
    Route::get('changePasswordForm', 'Master\UserController@changePasswordForm')->name('changePasswordForm');

    //Payment Method
    Route::get('paymentMethod', 'Master\PaymentMethodController@index')->name('paymentMethod');
    Route::get('paymentMethod/showForm/{id?}', 'Master\PaymentMethodController@showForm')->name('paymentMethod/showForm');
    Route::post('paymentMethod/save', 'Master\PaymentMethodController@store')->name('paymentMethod/save');
    Route::post('paymentMethod/void', 'Master\PaymentMethodController@void')->name('paymentMethod/void');
    Route::post('paymentMethod/unvoid', 'Master\PaymentMethodController@unvoid')->name('paymentMethod/unvoid');

    //Package
    Route::get('package', 'Master\PackageController@index')->name('package');
    Route::get('package/showForm/{id?}', 'Master\PackageController@showForm')->name('package/showForm');
    Route::post('package/save', 'Master\PackageController@store')->name('package/save');
    Route::post('package/void', 'Master\PackageController@void')->name('package/void');
    Route::post('package/unvoid', 'Master\PackageController@unvoid')->name('package/unvoid');
    Route::get('package/getByVehicle/{vehicle_id}', 'Master\PackageController@getByVehicle')->name('package/getByVehicle');
    Route::get('package/getPrice/{package_id}', 'Master\PackageController@getPrice')->name('package/getPrice');

    //worker
    Route::get('worker', 'Master\WorkerController@index')->name('worker');
    Route::get('worker/showForm/{id?}', 'Master\WorkerController@showForm')->name('worker/showForm');
    Route::post('worker/save', 'Master\WorkerController@store')->name('worker/save');
    Route::post('worker/delete', 'Master\WorkerController@void')->name('worker/delete');
    Route::post('worker/reject', 'Master\WorkerController@reject')->name('worker/reject');
    Route::post('worker/approve', 'Master\WorkerController@approve')->name('worker/approve');

    //Payment
    Route::get('payment', 'Transaction\PaymentController@index')->name('payment');
    Route::post('payment/rejectPayment', 'Transaction\PaymentController@rejectPayment')->name('payment/rejectPayment');
    Route::post('payment/approvePayment', 'Transaction\PaymentController@approvePayment')->name('payment/approvePayment');
    Route::get('payment/get/search/{approved}/{created_by_customer}/{startdate}/{enddate}', 'Transaction\PaymentController@getSearch')->name('payment/get/search');

    //Transaction
    Route::get('transaction', 'Transaction\TransactionController@index')->name('transaction');
    Route::get('transaction/showForm/{id?}', 'Transaction\TransactionController@showForm')->name('transaction/showForm');
    Route::post('transaction/save', 'Transaction\TransactionController@store')->name('transaction/save');
    Route::get('transaction/get/search/{status}/{vehicle_id}/{startdate}/{enddate}', 'Transaction\TransactionController@getSearch')->name('transaction/get/search');
    Route::get('transaction/detail/{id}', 'Transaction\TransactionController@detail')->name('transaction/detail');
    Route::get('transaction/print/{id}', 'Transaction\TransactionController@print')->name('transaction/print');
    Route::post('transaction/delete', 'Transaction\TransactionController@delete')->name('transaction/delete');

    //Jobs
    Route::get('job', 'Transaction\JobController@index')->name('job');
    Route::get('job/showForm/{id?}', 'Transaction\TransactionController@showForm')->name('job/showForm');
    Route::post('job/save', 'Transaction\TransactionController@store')->name('job/save');
    Route::get('job/get/search/{status}/{vehicle_id}/{startdate}/{enddate}', 'Transaction\JobController@getSearch')->name('job/get/search');
});
