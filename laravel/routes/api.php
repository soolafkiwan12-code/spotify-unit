<?php

use App\Http\Controllers\Api\ArtistController as ApiArtistController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\TrackController as ApiTrackController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/dashboard', [ApiDashboardController::class, 'index']);

    Route::apiResource('tracks', ApiTrackController::class);
    Route::get('/artists', [ApiArtistController::class, 'index']);
    Route::post('/artists', [ApiArtistController::class, 'store'])->middleware('role:admin,editor');
});
