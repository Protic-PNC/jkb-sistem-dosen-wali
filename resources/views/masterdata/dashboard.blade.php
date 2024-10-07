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
            <div class="max-w-sm text-bold text-center mt-5 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                Kelas {{ Auth::user()->lecturer->student_classes->class_name }}
            </div>
            <div class="flex">
                <div class="max-w-sm mt-5 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                    <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
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
                </div>
                <div class="ml-5 mt-5 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                            <tr>
                                <th rowspan="2" scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                    Keterangan
                                </th>
                                <th colspan="{{ $jumlahSemester }}" scope="col"
                                    class="text-center px-6 py-3 bg-gray-50 dark:bg-gray-800">
                                    Semester
                                </th>
                            </tr>
                            <tr>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <th scope="col" class="px-6 py-3">
                                        {{ $i + 1 }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    IPS Rata-rata
                                </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">{{ $table_data[$i]['avg_gpa'] ?? '-' }}</td>
                                @endfor
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    IPS Tertinggi
                                </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">{{ $table_data[$i]['max_gpa'] ?? '-' }}</td>
                                @endfor
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    IPS Terendah
                                </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">{{ $table_data[$i]['min_gpa'] ?? '-' }}</td>
                                @endfor
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    IPS < 3.00 </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">{{ $table_data[$i]['count_below_3'] ?? '-' }}</td>
                                @endfor
                            </tr>

                            <tr>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    % IPS < 3.00 </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">
                                        @if (isset($table_data[$i]['percentage_below_3']))
                                            {{ $table_data[$i]['percentage_below_3'] }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    IPS >= 3.00
                                </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">{{ $table_data[$i]['count_above_3'] ?? '-' }}</td>
                                @endfor
                            </tr>
                            <tr>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap bg-gray-50 dark:text-white dark:bg-gray-800">
                                    % IPS >= 3.00
                                </th>
                                @for ($i = 0; $i < $jumlahSemester; $i++)
                                    <td class="px-6 py-4">
                                        @if (isset($table_data[$i]['percentage_above_3']))
                                            {{ $table_data[$i]['percentage_above_3'] }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
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
