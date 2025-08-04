<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('TEFA.png') }}" type="image/png" sizes="16">    

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex lg:min-h-screen">

        <div class="w-[70%] p-16 text-white hidden sm:flex items-center justify-center 
            bg-gradient-to-br from-[#FDAC00] to-[#fe831c] border-l-[16px] border-[#024CAA]">
            <img src="{{ asset('build/assets/icons/JKB.png') }}" alt="BAZNAS Cilacap" class="w-24 mr-4 bg-white p-3 rounded-xl">
            <div>
                <h1 class="text-3xl font-bold">SIWALI JKB</h1>
                <p class="text-xl">SISTEM INFORMASI PERWALIAN JURUSAN<br>KOMPUTER DAN BISNIS
                </p>
            </div>
        </div>
        
        <div class="sm:w-[30%] w-full flex justify-center items-center min-h-screen bg-white px-4 sm:px-4 py-4 sm:py-6">
            <div class="max-w-md mx-6">
                <div class="text-center mb-6">
                    <img src="{{ asset('build/assets/icons/JKB.png') }}" alt="BAZNAS Cilacap" class="w-20 mb-2 mx-auto">
                    <h2 class="text-2xl font-bold text-[#024CAA]">Selamat datang di SIWALI</h2>
                    <p class="text-md text-[#FDAC00]">Sistem Informasi Perwalian</p>
                    <hr class="border-yellow-500 my-2 mx-24">
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
    