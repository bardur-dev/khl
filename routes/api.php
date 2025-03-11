<?php

use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\ForwardController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


// Divisions
Route::apiResource('divisions', DivisionController::class);

// Clubs
Route::apiResource('clubs', ClubController::class);

// Forwards
Route::apiResource('forwards', ForwardController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::put('update-profile', [AuthController::class, 'updateProfile']);
});
