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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('stacks', [\App\Http\Controllers\Api\StackController::class, 'index']);
Route::post('stacks', [\App\Http\Controllers\Api\StackController::class, 'store']);
Route::post('stacks/generate', [\App\Http\Controllers\Api\StackController::class, 'generate']);
