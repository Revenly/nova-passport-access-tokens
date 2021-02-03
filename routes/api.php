<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/users', '\R64\NovaPassportAccessTokens\Http\Controllers\UserController@list');
Route::post('/token', '\R64\NovaPassportAccessTokens\Http\Controllers\TokenController@create');
Route::get('/tokens', '\R64\NovaPassportAccessTokens\Http\Controllers\TokenController@list');
Route::delete('/token/{token_id}', '\R64\NovaPassportAccessTokens\Http\Controllers\TokenController@destroy');
