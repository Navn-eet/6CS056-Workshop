@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">Courses</h1>

        <a href="/courses/create"
           class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
            Create Course
        </a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 lg:table-cell">ID</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Name</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 lg:table-cell">Duration</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 lg:table-cell">Fee</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 sm:table-cell">Difficulty</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 sm:table-cell">Active</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="hidden px-4 py-3 text-sm text-gray-700 lg:table-cell">{{ $course->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $course->name }}</td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 lg:table-cell">{{ $course->duration }} weeks</td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 lg:table-cell">{{ number_format($course->fee, 2) }}</td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 sm:table-cell">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                'bg-green-100 text-green-800' => $course->difficulty === 'Easy',
                                'bg-amber-100 text-amber-800' => $course->difficulty === 'Medium',
                                'bg-red-100 text-red-800' => $course->difficulty === 'Hard',
                            ])>
                                {{ $course->difficulty }}
                            </span>
                        </td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 sm:table-cell">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                'bg-green-100 text-green-800' => $course->is_active,
                                'bg-gray-100 text-gray-700' => ! $course->is_active,
                            ])>
                                {{ $course->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-4">
                                <a href="/courses/{{ $course->id }}"
                                   class="rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                                    View
                                </a>
                                <a href="/courses/{{ $course->id }}/edit"
                                   class="rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                                    Edit
                                </a>
                                <form action="/courses/{{ $course->id }}" method="POST"
                                      onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-md text-sm font-medium text-red-700 underline underline-offset-4 hover:text-red-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center">
                            <p class="text-sm font-medium text-gray-900">No courses found.</p>
                            <p class="mt-1 text-sm text-gray-600">
                                Add the first course to start building the catalogue.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
