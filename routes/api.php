<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PropertieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user', [AuthController::class, 'store']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/properties/public', [PropertieController::class, 'public']);
Route::get('/properties/home_categories', [PropertieController::class, 'home_categories']);
Route::get('/properties/unic/{id}', [PropertieController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/properties', [PropertieController::class, 'index']);
    Route::post('/properties', [PropertieController::class, 'store']);
    Route::put('/properties/{id}', [PropertieController::class, 'update']);

    Route::post('/media', [MediaController::class, 'store']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);
});
