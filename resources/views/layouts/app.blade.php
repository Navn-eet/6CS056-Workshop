@php($pageTitle = trim($__env->yieldContent('title')) ?: 'Home')

<x-layouts.app :title="$pageTitle">
    @yield('content')
</x-layouts.app>
