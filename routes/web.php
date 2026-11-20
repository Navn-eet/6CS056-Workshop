<?php

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

Route::pattern('student', '[0-9]+');
Route::pattern('course', '[0-9]+');

Route::get('/', fn () => view('home'));

// Static "create" MUST precede the {student} parameter route.
Route::get('/students', function () {
    return view('student.list', ['students' => Student::all()]);
})->name('students.index');

Route::get('/students/create', fn () => view('student.create'))->name('students.create');

Route::post('/students', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:students,email',
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    try {
        $student = Student::create($validated);
    } catch (UniqueConstraintViolationException) {
        throw ValidationException::withMessages([
            'email' => 'That email is already registered.',
        ]);
    }

    return redirect()
        ->route('students.index')
        ->with('success', "Student {$student->name} created successfully!");
})->name('students.store');

Route::get('/students/{student}', function (Student $student) {
    return view('student.detail', ['student' => $student]);
})->name('students.show');

Route::get('/students/{student}/edit', function (Student $student) {
    return view('student.edit', ['student' => $student]);
})->name('students.edit');

Route::put('/students/{student}', function (Request $request, Student $student) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    try {
        $student->update($validated);
    } catch (UniqueConstraintViolationException) {
        throw ValidationException::withMessages([
            'email' => 'That email is already registered.',
        ]);
    }

    return redirect()
        ->route('students.show', $student)
        ->with('success', 'Student updated successfully!');
})->name('students.update');

Route::delete('/students/{student}', function (Student $student) {
    $student->delete();

    return redirect('/students')->with('success', 'Student deleted successfully!');
})->name('students.destroy');

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
