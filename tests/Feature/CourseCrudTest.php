<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function coursePayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Advanced Laravel',
        'description' => 'Deep dive into the framework internals.',
        'duration' => 12,
        'fee' => '25000.00',
        'difficulty' => 'Hard',
        'is_active' => '1',
    ], $overrides);
}

it('lists every course on the index page with a formatted fee', function () {
    Course::factory()->create([
        'name' => 'Advanced Laravel',
        'fee' => 25000,
        'difficulty' => 'Hard',
    ]);
    Course::factory()->create([
        'name' => 'Intro to PHP',
        'fee' => 1234.5,
        'difficulty' => 'Easy',
    ]);

    $response = $this->get('/courses');

    $response->assertOk()->assertSee('Advanced Laravel')->assertSee('Intro to PHP');

    expect($response->getContent())
        ->toContain('25,000.00')
        ->toContain('1,234.50')
        ->toContain('Hard');
});

it('renders the create form', function () {
    $response = $this->get('/courses/create');

    $response->assertOk()->assertSee('Create Course');

    expect($response->getContent())->toContain('action="/courses"');
});

it('stores a valid course with every typed column persisted', function () {
    $response = $this->post('/courses', coursePayload());

    $response->assertRedirect('/courses');
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('courses', [
        'name' => 'Advanced Laravel',
        'duration' => 12,
        'fee' => '25000.00',
        'difficulty' => 'Hard',
        'is_active' => 1,
    ]);

    $course = Course::query()->firstOrFail();

    expect($course->fee)->toBe('25000.00')
        ->and($course->duration)->toBe(12)
        ->and($course->is_active)->toBeTrue()
        ->and($course->difficulty)->toBe('Hard');
});

it('rejects an unknown difficulty on store', function () {
    $response = $this->from('/courses/create')->post('/courses', coursePayload([
        'difficulty' => 'Impossible',
    ]));

    $response->assertSessionHasErrors('difficulty');

    expect(Course::count())->toBe(0);
});

it('rejects a negative fee on store', function () {
    $response = $this->from('/courses/create')->post('/courses', coursePayload([
        'fee' => '-1',
    ]));

    $response->assertSessionHasErrors('fee');

    expect(Course::count())->toBe(0);
});

it('rejects a duration of zero on store', function () {
    $response = $this->from('/courses/create')->post('/courses', coursePayload([
        'duration' => 0,
    ]));

    $response->assertSessionHasErrors('duration');

    expect(Course::count())->toBe(0);
});

it('rejects a missing description on store', function () {
    $payload = coursePayload();
    unset($payload['description']);

    $response = $this->from('/courses/create')->post('/courses', $payload);

    $response->assertSessionHasErrors('description');

    expect(Course::count())->toBe(0);
});

it('stores an unchecked is_active checkbox as false', function () {
    $this->post('/courses', coursePayload(['is_active' => '0']))
        ->assertRedirect('/courses');

    $course = Course::query()->firstOrFail();

    expect($course->is_active)->toBeFalse();

    $this->assertDatabaseHas('courses', ['id' => $course->id, 'is_active' => 0]);
});

it('shows a single course', function () {
    $course = Course::factory()->create([
        'name' => 'Advanced Laravel',
        'description' => 'Deep dive into the framework internals.',
    ]);

    $response = $this->get("/courses/{$course->id}");

    $response->assertOk();

    expect($response->getContent())
        ->toContain('Advanced Laravel')
        ->toContain('Deep dive into the framework internals.')
        ->toContain('Course record #'.$course->id);
});

it('returns 404 for an unknown course', function () {
    $response = $this->get('/courses/999999');

    $response->assertNotFound();

    expect(Course::find(999999))->toBeNull();
});

it('prefills the edit form with the course data', function () {
    $course = Course::factory()->create([
        'name' => 'Advanced Laravel',
        'duration' => 12,
        'fee' => 25000,
    ]);

    $response = $this->get("/courses/{$course->id}/edit");

    $response->assertOk();

    expect($response->getContent())
        ->toContain('value="Advanced Laravel"')
        ->toContain('value="12"')
        ->toContain('value="25000.00"');
});

it('updates every course field', function () {
    $course = Course::factory()->create([
        'name' => 'Intro to PHP',
        'duration' => 8,
        'fee' => 10000,
        'difficulty' => 'Easy',
        'is_active' => true,
    ]);

    $response = $this->put("/courses/{$course->id}", coursePayload([
        'name' => 'Advanced Laravel',
        'description' => 'Deep dive into the framework internals.',
        'duration' => 16,
        'fee' => '31000.50',
        'difficulty' => 'Medium',
        'is_active' => '0',
    ]));

    $response->assertRedirect("/courses/{$course->id}");
    $response->assertSessionHasNoErrors();

    $course->refresh();

    expect($course->name)->toBe('Advanced Laravel')
        ->and($course->duration)->toBe(16)
        ->and($course->fee)->toBe('31000.50')
        ->and($course->difficulty)->toBe('Medium')
        ->and($course->is_active)->toBeFalse()
        ->and($course->description)->toBe('Deep dive into the framework internals.');
});

it('rejects an unknown difficulty on update', function () {
    $course = Course::factory()->create(['difficulty' => 'Easy']);

    $response = $this->put("/courses/{$course->id}", coursePayload([
        'difficulty' => 'Impossible',
    ]));

    $response->assertSessionHasErrors('difficulty');

    expect($course->fresh()->difficulty)->toBe('Easy');
});

it('rejects a duration of zero on update', function () {
    $course = Course::factory()->create(['duration' => 8]);

    $response = $this->put("/courses/{$course->id}", coursePayload([
        'duration' => 0,
    ]));

    $response->assertSessionHasErrors('duration');

    expect($course->fresh()->duration)->toBe(8);
});

it('rejects an update with no name', function () {
    $course = Course::factory()->create(['name' => 'Intro to PHP']);

    $payload = coursePayload();
    unset($payload['name']);

    $response = $this->put("/courses/{$course->id}", $payload);

    $response->assertSessionHasErrors('name');

    expect($course->fresh()->name)->toBe('Intro to PHP');
});

it('accepts a duration of exactly 520 weeks', function () {
    $this->post('/courses', coursePayload(['duration' => 520]))
        ->assertRedirect('/courses');

    expect(Course::firstOrFail()->duration)->toBe(520);
});

it('rejects a duration above 520 weeks', function () {
    $response = $this->from('/courses/create')->post('/courses', coursePayload([
        'duration' => 521,
    ]));

    $response->assertSessionHasErrors('duration');

    expect(Course::count())->toBe(0);
});

it('accepts a fee of zero', function () {
    $this->post('/courses', coursePayload(['fee' => '0']))
        ->assertRedirect('/courses');

    expect(Course::firstOrFail()->fee)->toBe('0.00');
});

it('stores a fee of exactly 999999.99, the decimal(8,2) ceiling', function () {
    $this->post('/courses', coursePayload(['fee' => '999999.99']))
        ->assertRedirect('/courses')
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('courses', ['fee' => '999999.99']);

    expect(Course::firstOrFail()->fee)->toBe('999999.99');
});

it('rejects a fee above the decimal ceiling on store', function () {
    $response = $this->from('/courses/create')->post('/courses', coursePayload([
        'fee' => '1000000',
    ]));

    $response->assertSessionHasErrors('fee');

    expect(Course::count())->toBe(0);
});

it('rejects a fee above the decimal ceiling on update', function () {
    $course = Course::factory()->create(['fee' => 10000]);

    $response = $this->put("/courses/{$course->id}", coursePayload([
        'fee' => '1000000',
    ]));

    $response->assertSessionHasErrors('fee');

    expect($course->fresh()->fee)->toBe('10000.00');
});

it('deletes a course', function () {
    $course = Course::factory()->create();

    $this->delete("/courses/{$course->id}")
        ->assertRedirect('/courses');

    $this->assertDatabaseMissing('courses', ['id' => $course->id]);

    expect(Course::count())->toBe(0);
});
