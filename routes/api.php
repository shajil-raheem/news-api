<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\AuthController;
Route::post('/auth', [AuthController::class, 'auth'])->middleware(['throttle:100,1']);
Route::post('/get_reset_token', [AuthController::class, 'getPasswordResetToken'])->middleware(['throttle:100,1']);
Route::post('/reset_password', [AuthController::class, 'resetPassword'])->middleware(['throttle:100,1']);

use App\Http\Controllers\UserController;
Route::post('/user', [UserController::class, 'store'])->middleware(['throttle:100,1']);
Route::get('/user', [UserController::class, 'show'])->middleware(['throttle:100,1','auth:sanctum']);

use App\Http\Controllers\NewsController;
Route::get('/news', [NewsController::class, 'index'])->middleware(['throttle:100,1','auth:sanctum']);
Route::get('/personalized', [NewsController::class, 'personalized'])->middleware(['throttle:100,1','auth:sanctum']);

use App\Http\Controllers\PreferenceController;
Route::get('/preferences', [PreferenceController::class, 'index'])->middleware(['throttle:100,1','auth:sanctum']);
Route::put('/preferences', [PreferenceController::class, 'upsert'])->middleware(['throttle:100,1','auth:sanctum']);
