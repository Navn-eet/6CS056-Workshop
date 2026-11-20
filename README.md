# 6CS056 Workshop 2 — Training Institute

A Laravel admin application for a training institute, written as coursework for 6CS056 Workshop 2.
Workshop 1 answered every request from a closure in `routes/web.php`; this version moves each one
onto a controller. It keeps two registers, students and courses, each with list, detail, create, edit
and delete pages, on Blade, backed by MySQL. Work on the `workshop-02` branch.

## Requirements

- PHP 8.3 or newer, with Composer 2; tested on PHP 8.5.4.
- MySQL 8, reachable at `127.0.0.1:3306`.
- No Node, and no asset build. Every page serves `public/css/app.css` through `asset()`, and no view
  uses `@vite`.

## Clone and install

```bash
git clone https://github.com/Navn-eet/6CS056-Workshop.git
cd 6CS056-Workshop
git switch workshop-02
composer install
cp .env.example .env
php artisan key:generate
```

`.env` is never committed, so every clone makes its own copy. `.env.example` supplies every key with
`DB_PASSWORD` left blank, so set that one value to your MySQL root password. The rest already read
`DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=WorkshopDB` and
`DB_USERNAME=root`.

Then create both databases — `WorkshopDB` for the application and `WorkshopDB_test` for the tests,
which `phpunit.xml` points the suite at:

```sql
CREATE DATABASE WorkshopDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE WorkshopDB_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Start it

```bash
php artisan migrate --seed
php artisan serve
```

Then open <http://127.0.0.1:8000>. The seeder leaves six students and six courses; one of the
courses, Digital Marketing, is inactive.

## Structure

- `app/Http/Controllers/StudentController.php` and `CourseController.php` hold the seven resource
  actions — `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` — plus one private
  `rules()` method each, called from both `store` and `update`.
- `routes/web.php` is two `Route::resource` lines and `/`.
- `resources/views/components/layouts/app.blade.php` holds the only `<head>`, navbar and footer and
  loads `public/css/app.css` with `asset()`. Student pages use `<x-layouts.app title="...">`; course
  pages `@extends('layouts.app')`, whose layout renders that same component.
- Each resource has one `_form` partial — `student/_form.blade.php`, `course/_form.blade.php`.
  Inputs, textareas, alerts and delete buttons are the Blade components in
  `resources/views/components/`.

## Tests

`php artisan test` runs the Pest suite: 70 tests and 356 assertions against the MySQL database
`WorkshopDB_test`.
