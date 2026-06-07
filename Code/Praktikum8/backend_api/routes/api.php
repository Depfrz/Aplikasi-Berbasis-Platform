<?php

// Nama: Muhammad Daffa Fariza | NIM: 103012300004
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/mahasiswa', MahasiswaController::class);
});
