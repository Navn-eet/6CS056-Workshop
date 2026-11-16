# 6CS056 Workshop 1 — Training Institute

A Laravel admin application for a training institute, written as coursework for 6CS056 Workshop 1.
It keeps two registers, students and courses, each with list, detail, create, edit and delete pages,
on Blade and Tailwind, backed by MySQL. Work on the `workshop-01` branch.

## Requirements

- PHP 8.3 or newer, with Composer 2; tested on PHP 8.5.4.
- MySQL 8, reachable at `127.0.0.1:3306`.
- Node.js 20.19+ or 22.12+, with npm — the range Vite 8 requires.

## Clone and install

```bash
git clone https://github.com/Navn-eet/6CS056-Workshop.git
cd 6CS056-Workshop
git switch workshop-01
composer install
npm install
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
php artisan view:clear && npm run build
php artisan serve
```

Then open <http://127.0.0.1:8000>. The seeder leaves six students and six courses, one of the
courses inactive.

`view:clear` runs first because `resources/css/app.css` scans compiled views in
`storage/framework/views`, and a stale one makes Tailwind emit classes no view uses. `npm run build`
writes the gitignored `public/build`, so a clone needs that build first; `npm run dev` watches assets
while you edit.

## Tests

`php artisan test` runs the Pest suite: 53 tests against the MySQL database `WorkshopDB_test`.
