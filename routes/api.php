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
Route::post('register', ['uses'=>'API\APIController@register']);
Route::post('login', ['uses'=>'API\APIController@login']);

Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return 'Tokens are deleted';
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    return User::leftjoin('m_customer', 'm_customer.user_id', 'users.id')->select(['m_customer.customer_id','m_customer.customer_name', 'users.email', 'users.username', 'users.user_type', 'users.company_id','users.id as user_id'])->where('users.id',$request->user()->id)->first();
});

// Route::get('test', ['uses' => [App\Http\Controllers\CompanyController::class, 'index'], 'as' => '/test'])->middleware();
Route::group(['middleware'=>['auth:sanctum']], function() {
    Route::post('getProfile', 'Master\CustomerController@getProfile');
    Route::post('saveProfile', 'Master\CustomerController@saveProfile');
    Route::post('getVehicle', 'Master\CustomerController@getVehicle');
    Route::post('getCompanyList', 'Master\CompanyController@getCompanyList');
});