<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatakuliahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get('dosen/trashed', [DosenController::class, 'trashed'])->name('dosen.trashed');
    Route::post('dosen/{id}/restore', [DosenController::class, 'restore'])->name('dosen.restore');
    Route::resource('dosen', DosenController::class);

    Route::post('matakuliah/{id}/assign-dosen', [MatakuliahController::class, 'assignDosen'])->name('matakuliah.assign-dosen');
    Route::resource('matakuliah', MatakuliahController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
