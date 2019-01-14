<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

Route::get('', function () {
    return response()->json('v1.0');
});

Route::post('auth/login', 'AuthController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('media/{filePath}', 'StorageController')->where(['filePath' => '.*']);

    Route::get('auth/user', 'AuthController@user');
    Route::get('auth/logout', 'AuthController@logout');
    Route::post('auth/reset', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('auth/reset/{token}', 'ResetPasswordController@reset');

    Route::get('users', 'UserController@index');
    Route::post('users', 'UserController@create');
    Route::get('users/{id}', 'UserController@show');
    Route::put('users/{id}', 'UserController@update');
    Route::delete('users/{id}', 'UserController@destroy');

    Route::get('transactions', 'TransactionController@index');
    Route::post('transactions', 'TransactionController@create');
    Route::get('transactions/{id}', 'TransactionController@show');
    Route::put('transactions/{id}', 'TransactionController@update');
    Route::delete('transactions/{id}', 'TransactionController@destroy');
    Route::post('transactions/{id}/files', 'TransactionController@createFile');
    Route::delete('transactions/{id}/files/{fileId}', 'TransactionController@destroyFile');
    Route::put('transactions/{id}/files/{fileId}', 'TransactionController@syncFileTags');

    Route::get('people', 'PersonController@index');
    Route::post('people', 'PersonController@create');
    Route::put('people/{id}', 'PersonController@update');
    Route::delete('people/{id}', 'PersonController@destroy');

    Route::get('companies', 'CompanyController@index');
    Route::post('companies', 'CompanyController@create');
    Route::put('companies/{id}', 'CompanyController@update');
    Route::delete('companies/{id}', 'CompanyController@destroy');

    Route::get('tags', 'TagController@index');
    Route::post('tags', 'TagController@create');
    Route::put('tags/{id}', 'TagController@update');
    Route::delete('tags/{id}', 'TagController@destroy');

    Route::get('categories', 'CategoryController@index');
    Route::get('categories/tree', 'CategoryController@tree');
    Route::get('categories/{id}', 'CategoryController@show');
    Route::post('categories', 'CategoryController@create');
    Route::put('categories/{id}', 'CategoryController@update');
    Route::delete('categories/{id}', 'CategoryController@destroy');
});
