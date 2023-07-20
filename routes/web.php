<?php

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post( 'register', [AuthController::class, 'register'] );
Route::post( 'login', [AuthController::class, 'login'] );

Route::group( ['middleware' => 'auth:jwt'], function () {
    Route::get( 'user', [UserController::class, 'getUser'] );
} );