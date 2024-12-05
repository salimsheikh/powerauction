<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <script>
        const color_theme = localStorage.getItem('color-theme');
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (color_theme === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');        
        }
    </script>

    {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=visibility" rel="stylesheet" />
    
</head>

<body class="font-sans text-gray-900 antialiased dark:bg-gray-900 overflow-y-scroll">
    <div id="container" class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-50 shadow ">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>       
    </div>       
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>

</html>
