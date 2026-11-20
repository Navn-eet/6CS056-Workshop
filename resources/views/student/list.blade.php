@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">Students</h1>

        <a href="/students/create"
           class="inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
            Create Student
        </a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">ID</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Name</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 lg:table-cell">Email</th>
                    <th scope="col" class="hidden px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 lg:table-cell">Phone</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $student->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $student->name }}</td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 lg:table-cell">{{ $student->email }}</td>
                        <td class="hidden px-4 py-3 text-sm text-gray-700 lg:table-cell">{{ $student->phone }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-4">
                                <a href="/students/{{ $student->id }}"
                                   class="rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                                    View
                                </a>
                                <a href="/students/{{ $student->id }}/edit"
                                   class="rounded-md text-sm font-medium text-blue-700 underline underline-offset-4 hover:text-blue-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700">
                                    Edit
                                </a>
                                <form action="/students/{{ $student->id }}" method="POST"
                                      onsubmit="return confirm('Delete this student?')">
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
                        <td colspan="5" class="px-4 py-10 text-center">
                            <p class="text-sm font-medium text-gray-900">No students found.</p>
                            <p class="mt-1 text-sm text-gray-600">
                                Add the first student to start building the register.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
