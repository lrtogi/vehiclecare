<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test', function () {
    return 'test';
});

Route::post('register', ['uses' => 'API\APIController@register']);
Route::post('login', ['uses' => 'API\APIController@login']);

Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return 'Tokens are deleted';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    return User::leftjoin('m_customer', 'm_customer.user_id', 'users.id')
        ->leftjoin('m_worker', 'm_worker.user_id', 'users.id')
        ->select(['m_customer.customer_id', 'm_customer.customer_name', 'users.email', 'users.username', 'users.user_type', 'users.company_id', 'm_worker.active as active_worker', 'users.id as user_id'])->where('users.id', $request->user()->id)->first();
});

// Route::get('test', ['uses' => [App\Http\Controllers\CompanyController::class, 'index'], 'as' => '/test'])->middleware();
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('getCompanyList', 'Master\CompanyController@getCompanyList');
    Route::post('getProfile', 'Master\CustomerController@getProfile');
    Route::post('saveProfile', 'Master\CustomerController@saveProfile');
    Route::post('changePassword', 'Master\CustomerController@changePassword');

    //company
    Route::post('company/search', 'Master\CompanyController@companySearch');
    Route::post('company/workerRegister', 'Master\CompanyController@workerRegister');
    Route::post('company/getWorkerData', 'Master\CompanyController@getWorkerData');
    Route::post('company/removeApplication', 'Master\CompanyController@removeApplication');

    //vehicle
    Route::post('vehicle/getAll', 'Master\CustomerController@getVehicle');
    Route::post('vehicle/getVehicleDetail', 'Master\CustomerController@getVehicleDetail');
    Route::post('vehicle/save', 'Master\CustomerController@saveVehicle');
    Route::post('vehicle/delete', 'Master\CustomerController@deleteVehicle');
    Route::post('vehicle/getType', 'Master\CustomerController@getType');

    //job
    Route::post('job/search', 'Transaction\JobController@search');
    Route::post('job/checkJob', 'Transaction\JobController@checkJob');

    //package
    Route::post('package/search', 'Transaction\TransactionController@packageSearchMobile');
    Route::post('package/getDetail', 'Transaction\TransactionController@getDetailPackage');

    //transaction
    Route::post('transactionMobile/getListData', 'Transaction\TransactionController@getListData');
    Route::post('transactionMobile/getData', 'Transaction\TransactionController@getData');
    Route::post('transactionMobile/save', 'Transaction\TransactionController@saveMobileForm');
    Route::post('transactionMobile/delete', 'Transaction\TransactionController@deleteMobileTransaction');

    //payment
    Route::post('paymentMobile/getDetailTransaction', 'Transaction\PaymentController@getDetailTransaction');
    Route::post('paymentMobile/getList', 'Transaction\PaymentController@getList');
    Route::post('paymentMobile/save', 'Transaction\PaymentController@savePaymentMobile');
    Route::post('paymentMobile/getDetailPayment', 'Transaction\PaymentController@getDetailPayment');

    //payment method
    Route::post('paymentMethod/getByCompany', 'Master\PaymentMethodController@getByCompany');
});

Route::group(['middleware' => ['auth:sanctum', 'isWorker']], function () {
    Route::post('job/getJob', 'Transaction\JobController@getJob');
});
