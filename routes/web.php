<?php

use App\Http\Controllers\StudentController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::pattern('student', '[0-9]+');
Route::pattern('course', '[0-9]+');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('students', StudentController::class);

// --- Course routes (Week 1 closures) ---
// leave untouched until the course controller exists

// Static "create" MUST precede the {course} parameter route.
Route::get('/courses', function () {
    return view('course.list', ['courses' => Course::all()]);
})->name('courses.index');

Route::get('/courses/create', fn () => view('course.create'))->name('courses.create');

Route::post('/courses', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:2000',
        'duration' => 'required|integer|min:1|max:520',
        'fee' => 'required|numeric|min:0|max:999999.99',
        'difficulty' => 'required|in:Easy,Medium,Hard',
        'is_active' => 'required|boolean',
    ]);

    $course = Course::create($validated);

    return redirect()
        ->route('courses.index')
        ->with('success', "Course {$course->name} created successfully!");
})->name('courses.store');

Route::get('/courses/{course}', function (Course $course) {
    return view('course.detail', ['course' => $course]);
})->name('courses.show');

Route::get('/courses/{course}/edit', function (Course $course) {
    return view('course.edit', ['course' => $course]);
})->name('courses.edit');

Route::put('/courses/{course}', function (Request $request, Course $course) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:2000',
        'duration' => 'required|integer|min:1|max:520',
        'fee' => 'required|numeric|min:0|max:999999.99',
        'difficulty' => 'required|in:Easy,Medium,Hard',
        'is_active' => 'required|boolean',
    ]);

    $course->update($validated);

    return redirect()
        ->route('courses.show', $course)
        ->with('success', 'Course updated successfully!');
})->name('courses.update');

Route::delete('/courses/{course}', function (Course $course) {
    $course->delete();

    return redirect('/courses')->with('success', 'Course deleted successfully!');
})->name('courses.destroy');
