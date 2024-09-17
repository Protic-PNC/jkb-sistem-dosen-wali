<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        {{-- Tailwind --}}
        <link href="https://cdn.tailwindcss.com" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        
    </head>
    <body class="font-sans antialiased min-h-screen bg-gray-100" style="background: #edf2f7;">
        <div>
            <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
                <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
                class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
                
                @include('components.sidebar')
                <div class="flex flex-col flex-1 overflow-hidden">
                    <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-indigo-600">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">X</path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center">
                            <div x-data="{notificationOpen: false} " class="relative">
                                <button @click="notificationOpen = ! notificationOpen"
                                    class="flex mx-4 text-gray-600 focus:outline-none">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </button>

                                <div x-show="notificationOpen" @click="notificationOpen = false"
                                    class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                                <div x-show="notificationOpen"
                                    class="absolute right-0 z-10 mt-2 overflow-hidden bg-white rounded-lg shadow-xl w-80"
                                    style="width: 20rem; display: none;">

                                    <a href="#"
                                        class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-blue-600">
                                        <img class="object-cover w-8 h-8 mx-1 rounded-full"
                                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=334&amp;q=80"
                                            alt="avatar">
                                        <p class="mx-2 text-sm">
                                            <span class="font-bold" href="#">Sara Salah</span> replied on the <span
                                                class="font-bold text-blue-400" href="#">Upload Image</span> artical
                                            . 2m
                                        </p>
                                    </a>

                                    <a href="#"
                                        class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-blue-600">
                                        <img class="object-cover w-8 h-8 mx-1 rounded-full"
                                            src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=634&amp;q=80"
                                            alt="avatar">
                                        <p class="mx-2 text-sm">
                                            <span class="font-bold" href="#">Slick Net</span> start following you .
                                            45m
                                        </p>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-blue-600">
                                        <img class="object-cover w-8 h-8 mx-1 rounded-full"
                                            src="https://images.unsplash.com/photo-1450297350677-623de575f31c?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=334&amp;q=80"
                                            alt="avatar">
                                        <p class="mx-2 text-sm">
                                            <span class="font-bold" href="#">Jane Doe</span> Like Your reply on <span
                                                class="font-bold text-blue-400" href="#">Test with TDD</span>
                                            artical . 1h
                                        </p>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-3 -mx-2 text-gray-600 hover:text-white hover:bg-blue-600">
                                        <img class="object-cover w-8 h-8 mx-1 rounded-full"
                                            src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=398&amp;q=80"
                                            alt="avatar">
                                        <p class="mx-2 text-sm">
                                            <span class="font-bold" href="#">Abigail Bennett</span> start following
                                            you . 3h
                                        </p>
                                    </a>

                                </div>
                            </div>

                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = ! dropdownOpen"
                                    class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none">
                                    @if (Auth::user()->avatar)
                                    
                                        <img class="object-cover w-full h-full"
                                            src="{{ Storage::url(Auth::user()->avatar)}}"
                                            alt="Your avatar">
                                    @else
                                            
                                        <img class="object-cover w-full h-full"
                                            src="{{ asset('images/avatar-default.svg') }}"
                                            alt="Your avatar">
                                            
                                    @endif
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
                            <div class="page-header mb-4 flex justify-between items-center ">
                                <nav class="flex" aria-label="Breadcrumb">
                                    <ol class="inline-flex items-center ">
                                        <li class="inline-flex items-center">
                                            <a href="{{ route('dashboard.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                            </svg>
                                            </a>
                                        </li>
                                        <li aria-current="page">
                                            <div class="flex items-center">
                                            <span class=" text-sm font-medium text-gray-500 dark:text-gray-400">@yield('main_folder')</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex items-center">
                                            <a href="@yield('href_descendant_folder')" class=" text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">@yield('descendant_folder')</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="flex items-center">
                                            <a href="@yield('href_breadcrumb_extra')" class=" text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">@yield('breadcrumb_extra')</a>
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
    </body>    
</html>
