<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect()->route('students.index');
});

Route::get('students/trashed', [StudentController::class, 'trashed'])->name('students.trashed');
Route::post('students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
Route::delete('students/{id}/force-delete', [StudentController::class, 'forceDelete'])->name('students.force-delete');

Route::resource('students', StudentController::class);
