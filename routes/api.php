<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\AuthController;
Route::post('/auth', [AuthController::class, 'auth']);

use App\Http\Controllers\UserController;
Route::post('/user', [UserController::class, 'store']);
Route::get('/user', [UserController::class, 'show'])->middleware('auth:sanctum');

use App\Http\Controllers\NewsController;
Route::get('/news', [NewsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/personalized', [NewsController::class, 'personalized'])->middleware('auth:sanctum');

