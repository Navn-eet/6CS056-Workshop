@extends('layouts.app')

@section('title', $student->name)

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $student->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Student record #{{ $student->id }}</p>
        </div>

        <a href="/students/{{ $student->id }}/edit"
           class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
            Edit Student
        </a>
    </div>

    <dl class="mt-6 divide-y divide-gray-200 rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">ID</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->id }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Name</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->name }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Email</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->email }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Phone</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->phone }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Address</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->address ?? '—' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Date of Birth</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->date_of_birth?->format('j M Y') ?? '—' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Created</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->created_at?->format('j M Y, H:i') ?? '—' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-600">Updated</dt>
            <dd class="text-sm text-gray-900 sm:col-span-2">{{ $student->updated_at?->format('j M Y, H:i') ?? '—' }}</dd>
        </div>
    </dl>

    <div class="mt-6">
        <a href="/students"
           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-700">
            Back to Students
        </a>
    </div>
@endsection
