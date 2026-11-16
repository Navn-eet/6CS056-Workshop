@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
    <h1 class="text-2xl font-semibold text-gray-900">Create Course</h1>

    <form action="/courses" method="POST" class="mt-6 max-w-xl" novalidate autocomplete="off">
        @csrf

        <div class="flex flex-col gap-5 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" autocomplete="off" value="{{ old('name') }}"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" autocomplete="off"
                          class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                <div class="relative mt-1">
                    <input type="number" name="duration" id="duration" min="1" autocomplete="off" value="{{ old('duration') }}"
                           class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-16 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-500">weeks</span>
                </div>
            </div>

            <div>
                <label for="fee" class="block text-sm font-medium text-gray-700">Fee</label>
                <input type="number" name="fee" id="fee" min="0" max="999999.99" step="0.01" autocomplete="off" value="{{ old('fee') }}"
                       class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
            </div>

            <div>
                <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                <select name="difficulty" id="difficulty"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    <option value="" disabled @selected(! old('difficulty'))>Select difficulty</option>
                    @foreach (['Easy', 'Medium', 'Hard'] as $difficulty)
                        <option value="{{ $difficulty }}" @selected(old('difficulty') === $difficulty)>{{ $difficulty }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true))
                       class="size-4 rounded border-gray-300 text-blue-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                <label for="is_active" class="text-sm font-medium text-gray-700">Is Active</label>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                Create Course
            </button>

            <a href="/courses"
               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-700">
                Back to Courses
            </a>
        </div>
    </form>
@endsection
