@extends('layouts.app')

@section('title', $course->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $course->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Course record #{{ $course->id }}</p>
        </div>

        <a href="/courses/{{ $course->id }}/edit"
           class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
            Edit Course
        </a>
    </div>

    <dl class="mt-6 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">ID</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $course->id }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Name</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $course->name }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Description</dt>
            <dd class="whitespace-pre-line text-sm text-gray-900 sm:col-span-2">{{ $course->description ?? '—' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Duration</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $course->duration }} weeks</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Fee</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ number_format($course->fee, 2) }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Difficulty</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">
                <span @class([
                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                    'bg-green-100 text-green-800' => $course->difficulty === 'Easy',
                    'bg-amber-100 text-amber-800' => $course->difficulty === 'Medium',
                    'bg-red-100 text-red-800' => $course->difficulty === 'Hard',
                ])>
                    {{ $course->difficulty }}
                </span>
            </dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Active</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">
                <span @class([
                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                    'bg-green-100 text-green-800' => $course->is_active,
                    'bg-gray-100 text-gray-700' => ! $course->is_active,
                ])>
                    {{ $course->is_active ? 'Yes' : 'No' }}
                </span>
            </dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Created</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $course->created_at?->format('j M Y, H:i') ?? '—' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Updated</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $course->updated_at?->format('j M Y, H:i') ?? '—' }}</dd>
        </div>
    </dl>

    <div class="mt-6">
        <a href="/courses"
           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-700">
            Back to Courses
        </a>
    </div>
@endsection
