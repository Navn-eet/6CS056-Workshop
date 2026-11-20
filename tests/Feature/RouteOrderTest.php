<?php

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('serves the student create form at /students/create, not the parameter route', function () {
    Student::factory()->create(['name' => 'Ada Lovelace']);

    $response = $this->get('/students/create');

    $response->assertOk()->assertSee('Create Student');

    // The create form, not a detail page for a student named "create".
    expect($response->getContent())
        ->toContain('action="'.route('students.store').'"')
        ->not->toContain('Ada Lovelace');
});

it('serves the course create form at /courses/create, not the parameter route', function () {
    Course::factory()->create(['name' => 'Advanced Laravel']);

    $response = $this->get('/courses/create');

    $response->assertOk()->assertSee('Create Course');

    expect($response->getContent())
        ->toContain('action="'.route('courses.store').'"')
        ->not->toContain('Advanced Laravel');
});

it('still resolves numeric ids through the parameter routes', function () {
    $student = Student::factory()->create(['name' => 'Ada Lovelace']);
    $course = Course::factory()->create(['name' => 'Advanced Laravel']);

    $this->get("/students/{$student->id}")
        ->assertOk()
        ->assertSee('Ada Lovelace');

    $this->get("/courses/{$course->id}")
        ->assertOk()
        ->assertSee('Advanced Laravel');
});

it('returns 404 for a student id with a non-numeric suffix', function () {
    $student = Student::factory()->create();

    $response = $this->get("/students/{$student->id}abc");

    $response->assertNotFound();

    // The row exists, so the 404 comes from the route pattern, not a missing record.
    expect(Student::find($student->id))->not->toBeNull();
});

it('returns 404 for a course id with a non-numeric suffix', function () {
    $course = Course::factory()->create();

    $response = $this->get("/courses/{$course->id}abc");

    $response->assertNotFound();

    expect(Course::find($course->id))->not->toBeNull();
});
