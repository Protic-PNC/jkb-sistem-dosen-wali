<x-app-layout>


    @section('content')
        @role('admin')
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="stats" role="tabpanel"
                            aria-labelledby="stats-tab">
                            <dl
                                class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $usersCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Pengguna</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $programsCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Prodi</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $student_classesCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Kelas</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $studentsCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Mahasiswa</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $lecturersCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Dosen</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ $reportsCount }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Laporan Perwalian</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Find all buttons with data-tabs-target attribute
                    const tabs = document.querySelectorAll('[data-tabs-target]');

                    // Add click event for each tab button
                    tabs.forEach((tab) => {
                        tab.addEventListener('click', () => {
                            // Hide all tab panels
                            document.querySelectorAll('[role="tabpanel"]').forEach((panel) => {
                                panel.classList.add('hidden');
                            });

                            // Remove hidden class from the tab panel that corresponds to the clicked tab
                            const target = document.querySelector(tab.dataset.tabsTarget);
                            target.classList.remove('hidden');

                            // Update aria-selected on the buttons
                            tabs.forEach((t) => t.setAttribute('aria-selected', 'false'));
                            tab.setAttribute('aria-selected', 'true');
                        });
                    });

                    // Handling mobile select
                    const mobileSelect = document.getElementById('tabs');
                    mobileSelect.addEventListener('change', function() {
                        document.querySelectorAll('[role="tabpanel"]').forEach((panel) => {
                            panel.classList.add('hidden');
                        });
                        const selectedTab = mobileSelect.value.toLowerCase();
                        const target = document.getElementById(selectedTab);
                        target.classList.remove('hidden');
                    });
                });
            </script>
        @endrole

        @role('dosenWali')
            <div class="">
                {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
                {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ __('Role Dosen Wali') }}
                        </div>
                    </div> --}}

                <div class="max-w-sm mt-5 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                                <path
                                    d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z" />
                                <path
                                    d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
                                {{ $students->count() }}</h5>
                            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Mahasiswa kelas
                                {{ $students->first()->student_classes->class_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2">
                    <dl class="flex items-center">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm font-normal me-1">Rata-rata IPK:</dt>
                        <dd class="text-gray-900 text-sm dark:text-white font-semibold">{{ $avg_gpas }}</dd>
                    </dl>
                </div>

                <div id="column-chart"></div>

                <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                    {{-- <div class="flex justify-between items-center pt-5">
                        <!-- Button -->
                        <button id="dropdownSemester" data-dropdown-toggle="semesterDropdown" data-dropdown-placement="bottom"
                            class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 inline-flex items-center dark:hover:text-white">
                            Semua Semester
                            <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="semesterDropdown"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownSemester">
                                @php
                                    $user = Auth::user();
                                    if ($user->lecturer->student_classes->program->degree == 'D3') {
                                        $semesters = 6;
                                    } else {
                                        $semesters = 8;
                                    }
                                @endphp
                                @for ($i = 1; $i <= $semesters; $i++)
                                    <li>
                                        <a href="{{ route('masterdata.gpas.index', ['semester' => $i]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Semester {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div> --}}
                </div>
                </div>

                {{-- </div> --}}
            </div>

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                const options = {
                    colors: ["#1A56DB"],
                    series: [{
                        name: "Rata-rata IPS",
                        color: "#1A56DB",
                        data: @json($chart_data),
                    }],
                    chart: {
                        type: "bar",
                        height: "320px",
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "70%",
                            borderRadius: 8,
                            borderRadiusApplication: "end",
                        },
                    },
                    xaxis: {
                        labels: {
                            style: {
                                fontFamily: "Inter, sans-serif",
                                cssClass: 'text-xs font-normal fill-gray-500'
                            }
                        },
                    },
                }

                const chart = new ApexCharts(document.getElementById("column-chart"), options);
                chart.render();
            </script>
        @endrole

        @role('mahasiswa')
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            {{ __('Role Mahasiswa') }}
                        </div>
                    </div>
                </div>
            </div>
        @endrole
    @endsection




</x-app-layout>
