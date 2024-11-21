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


    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


</head>

<body class="m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500"
    style="background: #edf2f7;">
    @include('components.sidebar')
    <div>
        {{-- <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
                class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div> --}}

        {{-- <div class="flex flex-col flex-1 overflow-hidden"> --}}
        {{-- <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4">
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
        {{-- <img class="object-cover w-full h-full"
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
                </header> --}}

        <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
            {{-- <div class="container px-6 pt-6 mx-auto">
                        <div
                            class="page-header mb-4 flex justify-between items-center bg-white overflow-hidden shadow-sm sm:rounded-lg px-6 py-4"> --}}
            <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
                navbar-main navbar-scroll="true" aria-label="Breadcrumb">
                <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
                    <nav>
                        <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                            <li class="leading-normal text-sm">
                                <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                            </li>
                            <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']"
                                aria-current="page">Dashboard</li>
                        </ol>
                        <h6 class="mb-0 font-bold capitalize">Dashboard</h6>
                    </nav>
                </div>
            </nav>
            {{-- </div> --}}
            <div class="w-full px-6 py-6 mx-auto">
                
                @yield('content')
            </div>
            {{-- </div> --}}
        </main>
        {{-- </div> --}}
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
<!-- plugin for charts  -->
<script src={{ "asset('assets/js/plugins/chartjs.min.js')" }}async></script>
<!-- plugin for scrollbar  -->
<script src={{ "asset('assets/js/plugins/perfect-scrollbar.min.js')" }}async></script>
<!-- github button -->
<script async defer src={{ "asset('https://buttons.github.io/buttons.js')" }}></script>
<!-- main script file  -->
<script src={{ "asset('assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5')" }}async></script>
</html>
