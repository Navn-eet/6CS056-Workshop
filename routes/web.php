<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::pattern('student', '[0-9]+');
Route::pattern('course', '[0-9]+');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('students', StudentController::class);

Route::resource('courses', CourseController::class);
