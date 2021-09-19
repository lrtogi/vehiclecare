<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('test', ['uses' => [App\Http\Controllers\CompanyController::class, 'index'], 'as' => '/test'])->middleware();
Route::get('test', 'CompanyController@index')->middleware(['auth:sanctum', 'isAdmin']);