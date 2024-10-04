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

    {{-- Tailwind --}}
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


</head>

<body class="font-sans antialiased min-h-screen bg-gray-100" style="background: #edf2f7;">
    <div>
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
                class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

            @include('components.sidebar')
            <div class="flex flex-col flex-1 overflow-hidden">
                <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">X</path>
                            </svg>
                        </button>

                    </div>

                    <div class="flex">
                        <div class="flex mr-5">
                            <span class="text-lg font-regular text-gray-500 dark:text-gray-400">Selamat datang,
                                <b>{{ Auth::user()->name }}!</b></span>
                        </div>
                        <div x-data="{ dropdownOpen: false }" class="relative">
                            <button @click="dropdownOpen = ! dropdownOpen"
                                class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none">
                                {{-- @if (Auth::user()->avatar)
                                    <img class="object-cover w-full h-full"
                                        src="{{ Storage::url(Auth::user()->avatar) }}" alt="Your avatar">
                                @else
                                    <img class="object-cover w-full h-full"
                                        src="{{ asset('images/avatar-default.svg') }}" alt="Your avatar">
                                        @endif --}}
                                <img class="object-cover w-full h-full"
                                    src="{{ asset('images/avatar-default.svg') }}" alt="Your avatar">
                            </button>

                            <div x-show="dropdownOpen" @click="dropdownOpen = false"
                                class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                            <div x-show="dropdownOpen"
                                class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl"
                                style="display: none;">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600 hover:text-white">Profile</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    <button type="submit"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600 hover:text-white w-full text-left">Logout</button>
                                    @csrf
                                </form>

                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    <div class="container px-6 pt-6 mx-auto">
                        <div
                            class="page-header mb-4 flex justify-between items-center bg-white overflow-hidden shadow-sm sm:rounded-lg px-6 py-4">
                            <nav class="flex" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center ">
                                    <li class="inline-flex items-center">
                                        <a href="{{ route('dashboard.index') }}"
                                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                            </svg>
                                        </a>
                                    </li>
                                    {{-- <li aria-current="page">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                        </svg>
                                    </li> --}}
                                    <li>
                                        <div class="flex items-center">
                                            <span
                                                class=" text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400 dark:hover:text-white">@yield('descendant_folder')</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <span
                                                class=" text-sm font-medium text-gray-700 md:ms-2 dark:text-gray-400 dark:hover:text-white">@yield('breadcrumb_extra')</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    {{-- <footer class=" mt-10 bg-gray-200 dark:bg-gray-800 w-full absolute bottom-0 ">
        <div class="w-full mx-auto max-w-screen-xl p-4 flex items-center justify-end">
            <span class="text-sm text-gray-500 text-center dark:text-gray-400">
                © 2024 <a href="https://github.com/Rayhan-Afrizal-Fajri" target="_blank" rel="noopener noreferrer"
                    class="text-gray-600 hover:underline">Rayhan Afrizal Fajri</a>. All Rights
                Reserved.
            </span>
        </div>
    </footer> --}}
</body>

</html>
