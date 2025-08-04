<aside 
    :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full lg:w-[220px]'"
    class="sidebar fixed left-0 top-[64px] z-40 flex h-full transition-all duration-300 ease-in-out flex-col overflow-y-hidden border-r border-gray-200 bg-gray-50 lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div
        :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 pb-7 px-5 sidebar-header sm:block transition-all duration-300 ease-in-out">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <span class="logo">
                {{-- <div class="flex items-center gap-2"> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 337.068 564.893" class="size-8 py-1 bg-yellow-500 rounded-lg">
                        <defs>
                            <style>
                                .cls-1 {
                                    fill: #fff;
                                }
                            </style>
                        </defs>
                        <g id="Layer_2" data-name="Layer 2">
                            <g id="Layer_1-2" data-name="Layer 1"><path
                                class="cls-1"
                                d="M234.069,77.686v219.03a407.551,407.551,0,0,1-77.71-18.49,247.322,247.322,0,0,1-69.96-36.84V77.686a12,12,0,0,1,12-12h123.67A12.01,12.01,0,0,1,234.069,77.686Z"/><path
                                class="cls-1"
                                d="M158.749,423.216a547.22,547.22,0,0,0,75.32,23.67v33.62a11.982,11.982,0,0,1-2.88,7.79l-61.83,72.39a12,12,0,0,1-18.25,0L89.269,488.3a12.025,12.025,0,0,1-2.87-7.79v-93.6A456.846,456.846,0,0,0,158.749,423.216Z"/><path
                                class="cls-1"
                                d="M3.438,131.074V30a30,30,0,0,1,30-30H88.7a3,3,0,0,1,2.606,4.486L9.044,132.56A3,3,0,0,1,3.438,131.074Z"/><path
                                class="cls-1"
                                d="M2.815,182.73a2,2,0,0,1,3.727-.829c11.269,19.792,54.133,85.123,143.418,115.271,105.936,35.771,163.032,15.822,176.79,25.452s13.758,103.185,0,113.5C313.069,446.388,89.2,419.917,1.467,277.575A10,10,0,0,1,0,272.325C.066,222.949,1.843,194.593,2.815,182.73Z"/></g>
                        </g>
                    </svg>
                {{-- </div> --}}
            </span>
            <h1 
                {{-- x-show="$store.sidebar.open" x-transition  --}}
                :class="sidebarToggle ? 'opacity-0 w-0' : 'opacity-100 w-auto'" class="font-bold text-xl text-yellow-500 ml-2">SIWALI JKB</h1>
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar pl-5 pr-2">
        <!-- Sidebar Menu -->
        <nav>
            <!-- Menu Group -->
            <div>
                <ul class="flex flex-col gap-4 mb-6 transition-all duration-300 ease-in-out">                    
                    <!-- Menu Item Dashboard -->
                    <li>
                        <x-side-link href="{{ route('dashboard') }}"
                                    :active="request()->routeIs('dashboard')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-4">
                                <path
                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                            </svg>
                            <span
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                                class="ms-3 text-sm font-light">Dashboard</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="{{ route(Auth::user()->roles->first()->name.'-khs.index') }}"
                                    :active="request()->routeIs('*-khs.*')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-4">
                                <path
                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                            </svg>
                            <span
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                                class="ms-3 text-sm font-light">KHS</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="{{ route(Auth::user()->roles->first()->name.'-krs.index') }}"
                                    :active="request()->routeIs('*-krs.*')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-4">
                                <path
                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                            </svg>
                            <span
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                                class="ms-3 text-sm font-light">KRS</span>
                        </x-side-link>
                    </li>
                    @role(['admin', 'dosenWali', 'kaprodi', 'kajur'])
                    <li>
                        @php
                            $dropdownOpen = request()->routeIs(['*-reports.*', 'gpas.*', 'resignations.*', 'achievements.*', 'guidances.*', 'scholarships.*', 'warnings.*', 'tuition-arrears.*']);
                        @endphp
                        <div x-data="{ open: {{ $dropdownOpen ? 'true' : 'false' }} }">

                            <button onclick="toggleDropdown()"
                                    type="button"
                                    class="flex w-full items-center px-2 py-3 rounded-md transition-all text-gray-500  hover:bg-gray-100">
                                <svg aria-hidden="true" class="size-5"  fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap text-sm" :class="sidebarToggle ? 'lg:hidden' : 'block'">Laporan</span>
                                <svg aria-hidden="true" id="arrowIcon" class="{{ $dropdownOpen ? 'rotate-180' : '' }} size-6 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <ul id="dropdown-pages" class="{{ $dropdownOpen ? '' : 'hidden' }} py-2 space-y-2">
                                <li>
                                    <x-side-link wire:navigate href="{{ route(Auth::user()->roles->first()->name . '-reports.index') }}" :active="request()->routeIs('*-reports.*')">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="size-4">
                                            <path
                                                d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                        </svg>
                                        <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Laporan Dosen Wali</span>
                                    </x-side-link>
                                </li>
                                @role('dosenWali')
                                @if (optional(Auth::user()->lecturer)->student_class)
                                    <li>
                                        <x-side-link wire:navigate href="{{ route('gpas.index') }}" :active="request()->routeIs('gpas.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Indeks Prestasi</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('resignations.index') }}" :active="request()->routeIs('resignations.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Undur Diri Mahasiwa</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('scholarships.index') }}" :active="request()->routeIs('scholarships.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Beasiswa</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('achievements.index') }}" :active="request()->routeIs('achievements.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Pencapaian Mahasiswa</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('warnings.index') }}" :active="request()->routeIs('warnings.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Peringatan Mahasiswa</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('tuition-arrears.index') }}" :active="request()->routeIs('tuition-arrears.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Tunggakan UKT</span>
                                        </x-side-link>
                                    </li>
                                    <li>
                                        <x-side-link href="{{ route('guidances.index') }}" :active="request()->routeIs('guidances.*')">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                                class="size-4">
                                                <path
                                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
                                            </svg>
                                            <span :class="sidebarToggle ? 'lg:hidden' : 'block'" class="ms-3 text-sm font-light">Bimbingan</span>
                                        </x-side-link>
                                    </li>
                                @endif
                                @endrole
                            </ul>
                        </div>
                    </li>
                    @endrole

                    @role('admin')
                    <li>
                        <h3 class="text-sm uppercase leading-[20px] text-gray-400">
                            <span
                                {{-- x-show="$store.sidebar.open"x-transition --}}
                                class="menu-group-title"
                                :class="sidebarToggle ? 'lg:hidden' : ''">
                                DATA MASTER
                            </span>
                        </h3>
                    </li>
                    <li>
                        <x-side-link href="{{ route('users.index') }}"
                                    :active="request()->routeIs('users.*')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                            </svg>


                            <span
                                {{-- x-show="$store.sidebar.open" x-transition --}}
                                :class="sidebarToggle ? 'lg:hidden' : ''"
                                class="ms-3 text-sm font-light">Pengguna</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="{{ route('programs.index') }}"
                                    :active="request()->routeIs('programs.*')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5">
                                <path
                                    d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z"/>
                                <path
                                    d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z"/>
                                <path
                                    d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z"/>
                            </svg>

                            <span
                            {{-- x-show="$store.sidebar.open" x-transition --}}
                            :class="sidebarToggle ? 'lg:hidden' : ''"
                            class="ms-3 text-sm font-light">Prodi & Kelas</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="{{ route('lecturers.index') }}"
                                    :active="request()->routeIs('lecturers.*')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5">
                                <path
                                    d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z"/>
                                <path
                                    d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z"/>
                                <path
                                    d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z"/>
                            </svg>

                            <span
                            {{-- x-show="$store.sidebar.open" x-transition --}}
                            :class="sidebarToggle ? 'lg:hidden' : ''"
                            class="ms-3 text-sm font-light">Dosen</span>
                        </x-side-link>
                    </li>
                    <li>
                        <x-side-link href="{{ route('students.index') }}"
                                    :active="request()->routeIs('students.*')">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="size-5">
                                <path
                                    d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z"/>
                                <path
                                    d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z"/>
                                <path
                                    d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z"/>
                            </svg>

                            <span
                            {{-- x-show="$store.sidebar.open" x-transition --}}
                            :class="sidebarToggle ? 'lg:hidden' : ''"
                            class="ms-3 text-sm font-light">Mahasiswa</span>
                        </x-side-link>
                    </li>
                    @endrole
                </ul>
            </div>
        </nav>
    </div>
</aside>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown-pages');
        const arrowIcon = document.getElementById('arrowIcon');

        dropdown.classList.toggle('hidden');
        arrowIcon.classList.toggle('rotate-180');
    }
</script>
