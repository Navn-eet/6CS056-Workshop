<x-layouts.app title="Home">
    <h1>Training Institute</h1>

    <p>
        This application keeps the institute's student register and its course catalogue.
        Use the links below to view either register.
    </p>

    <p>
        <a href="{{ route('students.index') }}" class="btn">Students</a>
        <a href="{{ route('courses.index') }}" class="btn">Courses</a>
    </p>
</x-layouts.app>
