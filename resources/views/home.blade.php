@extends('layouts.app')

@section('title', 'Training Institute')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-900">Training Institute</h1>

    <p class="mt-4 max-w-2xl text-gray-700">
        This application keeps the records of a training institute. You can manage the
        <strong>students</strong> on the register and the <strong>courses</strong> the institute
        teaches, adding, updating and removing records as they change.
    </p>

    <div class="mt-8 grid gap-6 sm:grid-cols-2">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Students</h2>
            <p class="mt-2 text-sm text-gray-600">
                View every student on file, add a new one, and edit or delete an existing record.
            </p>
            <a href="/students"
               class="mt-4 inline-block rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                View students
            </a>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Courses</h2>
            <p class="mt-2 text-sm text-gray-600">
                View the courses the institute offers, with their names, descriptions, duration,
                fees and difficulty.
            </p>
            <a href="/courses"
               class="mt-4 inline-block rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                View courses
            </a>
        </div>
    </div>
@endsection
