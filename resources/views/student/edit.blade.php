@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-900">Edit Student</h1>
    <p class="mt-1 text-sm text-gray-600">Editing {{ $student->name }}</p>

    <form action="/students/{{ $student->id }}" method="POST" class="mt-6 max-w-xl" novalidate>
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-5 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}" autocomplete="name"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" autocomplete="email"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}" autocomplete="tel"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" id="address" rows="3" autocomplete="street-address"
                          class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">{{ old('address', $student->address) }}</textarea>
            </div>

            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" autocomplete="off"
                       value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                Update Student
            </button>

            <a href="/students"
               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-700">
                Back to Students
            </a>
        </div>
    </form>
@endsection
