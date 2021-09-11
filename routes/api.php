<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\BiddingsController;

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
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/items', [ItemsController::class, 'index']);
    Route::get('/items/{id}', [ItemsController::class, 'show']);
    Route::post('/biddings', [BiddingsController::class, 'store']);
});
