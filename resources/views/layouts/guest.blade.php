<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'siwali') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 bg-gray-100 h-screen">
    <div class="flex h-full">
        <div class="flex items-center justify-center w-full bg-white lg:w-1/2">
            <div class="w-full max-w-md p-6">
                {{ $slot }}
            </div>
        </div>
        <div class="hidden lg:flex flex-col justify-center w-1/2 bg-yellow-400 text-white p-12">
            <h2 class="text-4xl font-bold mb-4">SIWALI JKB</h2>
            <p class="text-lg mb-6">JKB Academic Advising Information System (SIWALI JKB) is a comprehensive academic
                advising management system designed to streamline the process of managing student performance,
                counseling, and other academic data for higher education institutions.</p>
        </div>
    </div>
</body>
</html>
