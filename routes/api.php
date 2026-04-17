<?php

use App\Domains\Auth\Http\Controllers\AuthController;
use App\Domains\Companies\Http\Controllers\CompanyController;
use App\Domains\Users\Http\Controllers\CandidateProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/candidate/profile', [CandidateProfileController::class, 'show']);
    Route::put('/candidate/profile', [CandidateProfileController::class, 'update']);

    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::get('/companies/{company}', [CompanyController::class, 'show']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
});
