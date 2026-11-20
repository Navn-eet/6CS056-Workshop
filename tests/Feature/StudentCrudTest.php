<?php

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function studentPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Ada Lovelace',
        'email' => 'ada.lovelace@example.com',
        'phone' => '07000000001',
        'address' => '12 Analytical Way',
        'date_of_birth' => '1990-12-10',
    ], $overrides);
}

it('lists every student on the index page', function () {
    Student::factory()->create(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);
    Student::factory()->create(['name' => 'Alan Turing', 'email' => 'alan@example.com']);

    $response = $this->get('/students');

    $response->assertOk()->assertSee('Ada Lovelace')->assertSee('Alan Turing');

    expect($response->getContent())
        ->toContain('ada@example.com')
        ->toContain('alan@example.com');
});

it('renders the create form', function () {
    $response = $this->get('/students/create');

    $response->assertOk()->assertSee('Create Student');

    expect($response->getContent())->toContain('action="'.route('students.store').'"');
});

it('stores a valid student and redirects to the index', function () {
    $response = $this->post('/students', studentPayload());

    $response->assertRedirect('/students');
    $response->assertSessionHasNoErrors();

    expect(Student::count())->toBe(1);

    $this->assertDatabaseHas('students', [
        'name' => 'Ada Lovelace',
        'email' => 'ada.lovelace@example.com',
        'phone' => '07000000001',
        'address' => '12 Analytical Way',
        'date_of_birth' => '1990-12-10',
    ]);
});

it('stores NULL rather than an empty string for blank optionals', function () {
    $this->post('/students', studentPayload([
        'address' => '',
        'date_of_birth' => '',
    ]))->assertRedirect('/students');

    $this->assertDatabaseHas('students', [
        'email' => 'ada.lovelace@example.com',
        'address' => null,
        'date_of_birth' => null,
    ]);

    $student = Student::query()->where('email', 'ada.lovelace@example.com')->firstOrFail();

    expect($student->address)->toBeNull()
        ->and($student->getRawOriginal('address'))->toBeNull()
        ->and($student->date_of_birth)->toBeNull()
        ->and($student->getRawOriginal('date_of_birth'))->toBeNull();
});

it('stores a submitted date of birth as a date', function () {
    $this->post('/students', studentPayload(['date_of_birth' => '2001-02-03']))
        ->assertRedirect('/students');

    $student = Student::query()->firstOrFail();

    expect($student->date_of_birth)->toBeInstanceOf(Carbon::class)
        ->and($student->date_of_birth->format('Y-m-d'))->toBe('2001-02-03');
});

it('rejects a student with no name', function () {
    $payload = studentPayload();
    unset($payload['name']);

    $response = $this->from('/students/create')->post('/students', $payload);

    $response->assertSessionHasErrors('name');
    $response->assertRedirect('/students/create');

    expect(Student::count())->toBe(0);
});

it('rejects a duplicate email on store', function () {
    Student::factory()->create(['email' => 'taken@example.com']);

    $response = $this->from('/students/create')->post('/students', studentPayload([
        'email' => 'taken@example.com',
    ]));

    $response->assertSessionHasErrors('email');

    expect(Student::count())->toBe(1);
});

it('reports a duplicate email that arrives after the unique rule ran as an email error', function () {
    $email = 'raced@example.com';

    expect(Student::where('email', $email)->exists())->toBeFalse();

    // The unique rule therefore passes. This listener commits a competing row in the
    // window between validation and the INSERT — the race the catch arm exists for.
    $insertAttempted = false;

    Student::creating(function (Student $student) use ($email, &$insertAttempted) {
        if ($student->email === $email) {
            DB::table('students')->insert([
                'name' => 'Concurrent Writer',
                'email' => $email,
                'phone' => '07000000009',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $insertAttempted = true;
        }
    });

    $response = $this->from('/students/create')->post('/students', studentPayload([
        'email' => $email,
    ]));

    $response->assertRedirect('/students/create');

    // The catch arm's own message, not the unique rule's "already been taken".
    $response->assertSessionHasErrors(['email' => 'That email is already registered.']);

    expect($insertAttempted)->toBeTrue()
        ->and(Student::where('email', $email)->count())->toBe(1);
});

it('rejects a malformed email on store', function () {
    $response = $this->from('/students/create')->post('/students', studentPayload([
        'email' => 'not-an-email',
    ]));

    $response->assertSessionHasErrors('email');

    expect(Student::count())->toBe(0);
});

it('preserves the typed name when validation fails', function () {
    $this->followingRedirects()
        ->from('/students/create')
        ->post('/students', studentPayload([
            'name' => 'Grace Hopper',
            'email' => 'not-an-email',
        ]))
        ->assertOk()
        ->assertSee('Create Student')
        ->assertSee('value="Grace Hopper"', false);

    expect(Student::count())->toBe(0);
});

it('shows a single student', function () {
    // The requested record is the second row: a detail page that rendered the
    // first student instead of the bound one would show Alan and fail here.
    Student::factory()->create([
        'name' => 'Alan Turing',
        'email' => 'alan@example.com',
    ]);
    $student = Student::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $response = $this->get("/students/{$student->id}");

    $response->assertOk();

    expect($response->getContent())
        ->toContain('Ada Lovelace')
        ->toContain('ada@example.com');

    expect($response->getContent())
        ->not->toContain('Alan Turing')
        ->not->toContain('alan@example.com');
});

it('returns 404 for an unknown student', function () {
    $response = $this->get('/students/999999');

    $response->assertNotFound();

    expect(Student::find(999999))->toBeNull();
});

it('prefills the edit form with the student data', function () {
    $student = Student::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $response = $this->get("/students/{$student->id}/edit");

    $response->assertOk();

    expect($response->getContent())
        ->toContain('value="Ada Lovelace"')
        ->toContain('value="ada@example.com"');
});

it('updates a student and redirects to the detail page', function () {
    $student = Student::factory()->create([
        'email' => 'ada@example.com',
        'phone' => '07000000001',
    ]);

    $response = $this->put("/students/{$student->id}", studentPayload([
        'name' => 'Ada Byron',
        'email' => 'ada@example.com',
        'phone' => '07999999999',
    ]));

    $response->assertRedirect("/students/{$student->id}");
    $response->assertSessionHasNoErrors();

    expect($student->fresh()->phone)->toBe('07999999999');

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'name' => 'Ada Byron',
    ]);
});

it('allows a student to keep their own email on update', function () {
    $student = Student::factory()->create(['email' => 'ada@example.com']);

    $response = $this->put("/students/{$student->id}", studentPayload([
        'email' => 'ada@example.com',
    ]));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect("/students/{$student->id}");

    expect(Student::count())->toBe(1);
    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'email' => 'ada@example.com',
    ]);
});

it('rejects another student email on update', function () {
    $student = Student::factory()->create(['email' => 'ada@example.com']);
    $other = Student::factory()->create(['email' => 'alan@example.com']);

    $response = $this->put("/students/{$student->id}", studentPayload([
        'email' => 'alan@example.com',
    ]));

    $response->assertSessionHasErrors('email');

    $this->assertDatabaseHas('students', ['id' => $student->id, 'email' => 'ada@example.com']);
    $this->assertDatabaseHas('students', ['id' => $other->id, 'email' => 'alan@example.com']);

    expect($student->fresh()->email)->toBe('ada@example.com');
});

it('rejects an update with no name', function () {
    $student = Student::factory()->create(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);

    $payload = studentPayload(['email' => 'ada@example.com']);
    unset($payload['name']);

    $response = $this->put("/students/{$student->id}", $payload);

    $response->assertSessionHasErrors('name');

    expect($student->fresh()->name)->toBe('Ada Lovelace');
});

it('stores NULL rather than an empty string for blank optionals on update', function () {
    $student = Student::factory()->create([
        'address' => '12 Analytical Way',
        'date_of_birth' => '1990-12-10',
    ]);

    $this->put("/students/{$student->id}", studentPayload([
        'email' => $student->email,
        'address' => '',
        'date_of_birth' => '',
    ]))->assertRedirect("/students/{$student->id}");

    $student->refresh();

    expect($student->getRawOriginal('address'))->toBeNull()
        ->and($student->getRawOriginal('date_of_birth'))->toBeNull();
});

it('deletes a student', function () {
    $student = Student::factory()->create();

    $this->delete("/students/{$student->id}")
        ->assertRedirect('/students');

    $this->assertDatabaseMissing('students', ['id' => $student->id]);

    expect(Student::count())->toBe(0);
});

it('accepts a name of exactly 255 characters', function () {
    $name = str_repeat('a', 255);

    $this->post('/students', studentPayload(['name' => $name]))
        ->assertRedirect('/students');

    expect(Student::firstOrFail()->name)->toBe($name);
});

it('rejects a name longer than 255 characters', function () {
    $response = $this->from('/students/create')->post('/students', studentPayload([
        'name' => str_repeat('a', 256),
    ]));

    $response->assertSessionHasErrors('name');

    expect(Student::count())->toBe(0);
});

it('enforces the 500-character address boundary on store', function () {
    $address = str_repeat('a', 500);

    $this->post('/students', studentPayload(['address' => $address]))
        ->assertRedirect('/students')
        ->assertSessionHasNoErrors();

    expect(Student::firstOrFail()->address)->toBe($address);

    $this->from('/students/create')
        ->post('/students', studentPayload([
            'email' => 'grace.hopper@example.com',
            'address' => str_repeat('b', 501),
        ]))
        ->assertSessionHasErrors('address');

    expect(Student::count())->toBe(1);
});

it('rejects an address longer than 500 characters on update', function () {
    $student = Student::factory()->create([
        'email' => 'ada@example.com',
        'address' => '12 Analytical Way',
    ]);

    $response = $this->put("/students/{$student->id}", studentPayload([
        'email' => 'ada@example.com',
        'address' => str_repeat('a', 501),
    ]));

    $response->assertSessionHasErrors('address');

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'address' => '12 Analytical Way',
    ]);

    expect($student->fresh()->address)->toBe('12 Analytical Way');
});

it('reports a duplicate email that arrives after the unique rule ran as an email error on update', function () {
    $student = Student::factory()->create(['email' => 'ada@example.com']);
    $email = 'raced@example.com';

    expect(Student::where('email', $email)->exists())->toBeFalse();

    $insertAttempted = false;

    Student::updating(function (Student $updating) use ($email, &$insertAttempted) {
        if ($updating->email === $email) {
            DB::table('students')->insert([
                'name' => 'Concurrent Writer',
                'email' => $email,
                'phone' => '07000000009',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $insertAttempted = true;
        }
    });

    $response = $this->from("/students/{$student->id}/edit")->put("/students/{$student->id}", studentPayload([
        'email' => $email,
    ]));

    $response->assertRedirect("/students/{$student->id}/edit");

    $response->assertSessionHasErrors(['email' => 'That email is already registered.']);

    expect($insertAttempted)->toBeTrue()
        ->and($student->fresh()->email)->toBe('ada@example.com')
        ->and(Student::where('email', $email)->count())->toBe(1);
});

it('rejects a duplicate email at the database level', function () {
    Student::factory()->create(['email' => 'taken@example.com']);

    try {
        Student::factory()->create(['email' => 'taken@example.com']);
    } catch (QueryException $exception) {
        expect($exception->getCode())->toBe('23000')
            ->and(Student::count())->toBe(1);

        return;
    }

    $this->fail('The unique index on students.email did not reject a duplicate email.');
});

/*
|--------------------------------------------------------------------------
| Shared layout, form partial, delete component and accessors
|--------------------------------------------------------------------------
*/

it('renders the shared layout on every page', function () {
    $student = Student::factory()->create();
    $course = Course::factory()->create();

    $pages = [
        '/',
        '/students',
        '/students/create',
        "/students/{$student->id}",
        "/students/{$student->id}/edit",
        '/courses',
        '/courses/create',
        "/courses/{$course->id}",
        "/courses/{$course->id}/edit",
    ];

    foreach ($pages as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        // Exactly one copy of each layout marker: a page that bypassed the shared
        // layout would render none, and a double render would produce two.
        expect(substr_count($content, '<nav class="navbar">'))->toBe(1)
            ->and(substr_count($content, '<footer class="footer">'))->toBe(1)
            ->and($content)
            ->toContain('<link rel="stylesheet" href="'.asset('css/app.css').'">')
            ->toContain('href="'.route('students.index').'"')
            ->toContain('href="'.route('courses.index').'"')
            ->toContain('Training Institute Management System');
    }
});

it('marks the current section in the navbar and only that section', function () {
    // The active class is request()->routeIs('students.*' / 'courses.*'); a broken
    // pattern leaves both links inactive or both active, which looks identical in a
    // browser until the user notices the wrong tab is highlighted.
    $activeSection = function (string $content): string {
        $start = strpos($content, '<nav class="navbar">');
        $nav = substr($content, $start, strpos($content, '</nav>', $start) - $start);

        expect(substr_count($nav, 'class="active"'))->toBe(1);

        $studentsAt = strpos($nav, 'href="'.route('students.index').'"');
        $coursesAt = strpos($nav, 'href="'.route('courses.index').'"');
        $activeAt = strpos($nav, 'class="active"');

        return $studentsAt < $activeAt && $activeAt < $coursesAt ? 'students' : 'courses';
    };

    expect($activeSection($this->get('/students')->assertOk()->getContent()))->toBe('students');
    expect($activeSection($this->get('/courses')->assertOk()->getContent()))->toBe('courses');
});

it('renders the same five student fields on the create and edit forms', function () {
    $student = Student::factory()->create();

    $fields = ['name', 'email', 'phone', 'address', 'date_of_birth'];

    foreach (['/students/create', "/students/{$student->id}/edit"] as $url) {
        $content = $this->get($url)->assertOk()->getContent();

        foreach ($fields as $field) {
            expect($content)->toContain('name="'.$field.'"');
        }
    }
});

it('posts the edit form through PUT to the update route', function () {
    $student = Student::factory()->create();

    $content = $this->get("/students/{$student->id}/edit")->assertOk()->getContent();

    expect($content)
        ->toContain('action="'.route('students.update', $student).'"')
        ->toContain('<input type="hidden" name="_method" value="PUT">');
});

it('renders the delete component contract on the student list', function () {
    $student = Student::factory()->create();

    $content = $this->get('/students')->assertOk()->getContent();

    $start = strpos($content, '<form action="'.route('students.destroy', $student).'" method="POST"');

    expect($start)->not->toBeFalse();

    $form = substr($content, $start, strpos($content, '</form>', $start) - $start);

    expect($form)
        ->toContain('name="_token"')
        ->toContain('<input type="hidden" name="_method" value="DELETE">');
});

it('renders validation errors through the form input component', function () {
    $response = $this->followingRedirects()
        ->from('/students/create')
        ->post('/students', studentPayload([
            'name' => '',
            'email' => 'not-an-email',
        ]))
        ->assertOk();

    $content = $response->getContent();

    expect(substr_count($content, '<p class="error">'))->toBe(2)
        ->and($content)
        ->toContain('<p class="error">The name field is required.</p>')
        ->toContain('<p class="error">The email field must be a valid email address.</p>');
});

it('renders the age and the formatted date of birth on the detail page', function () {
    $birthDate = Carbon::now()->subYears(30)->subDay();

    $student = Student::factory()->create([
        'date_of_birth' => $birthDate->toDateString(),
    ]);

    $content = $this->get("/students/{$student->id}")->assertOk()->getContent();

    expect($content)
        ->toContain('<strong>Date of Birth:</strong> '.$birthDate->format('d M Y'))
        ->toContain('<strong>Age:</strong> 30')
        ->not->toContain($birthDate->format('Y-m-d'));
});

it('renders Not available when the student has no date of birth', function () {
    $student = Student::factory()->create(['date_of_birth' => null]);

    $content = $this->get("/students/{$student->id}")->assertOk()->getContent();

    expect($content)->toContain('<strong>Age:</strong> Not available');
});
