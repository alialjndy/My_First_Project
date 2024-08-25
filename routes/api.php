<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('movie', MovieController::class);

//register
Route::post('register' , [ApiController::class , 'register']);

//login
Route::post('login', [ApiController::class , 'login']);

//logout
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ApiController::class, 'logout']);
});
//
Route::apiResource('rating', RatingController::class);
