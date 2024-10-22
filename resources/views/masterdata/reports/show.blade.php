<x-app-layout>

    @section('descendant_folder')
        > &nbsp;&nbsp;Nilai
    @endsection
    @section('content')
        <style>
            #success-message {
                transition: opacity 0.2s ease-out;
            }

            #errors-message {
                transition: opacity 0.2s ease-out;
            }

            .close-btn {
                cursor: pointer;
                float: right;
                font-size: 1.2rem;
                font-weight: bold;
                color: black;
            }

            .close-btn:hover {
                color: black;
            }
        </style>

        @if ($errors->any())
            <div id="error-message"
                class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <span class="font-medium">Whoops!</span> There were some problems with your input.
                <span class="close-btn" onclick="closeAlert('error-message')">&times;</span>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div id="success-message"
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                <span class="font-medium">Success!</span> {{ session('success') }}
                <span class="close-btn" onclick="closeAlert('success-message')">&times;</span>
            </div>
        @endif

        <script>
            function closeAlert(id) {
                var alert = document.getElementById(id);
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 200); // Menunggu transisi opacity selesai
            }
        </script>
        {{-- <div class="flex">
            <div class="w-max mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                <table class="table-auto text-left text-gray-600 dark:text-gray-400">
                    <tbody>
                        <tr class="">
                            <td class="font-semibold ">Nama Dosen Wali</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $class->academic_advisor->lecturer_name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Program Studi</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">
                                {{ $class->program->degree }}-{{ $class->program->program_name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Nomor SK Dosen Wali</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">221/PL.43/HK.03.01/2023</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Semester</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $semester }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Kelas/Angkatan</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $class->class_name }}/ {{ $class->academic_year }}
                            </td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Semester/Tahun Akademik</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $semester }}/ 2022-2023</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="w-max mb-4 mt-5 ml-4  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">

                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">Dropdown button <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown"
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign
                                out</a>
                        </li>
                    </ul>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const button = document.getElementById('dropdownDefaultButton');
                        const dropdown = document.getElementById('dropdown');
                        
                        button.addEventListener('click', function () {
                            dropdown.classList.toggle('hidden');
                        });
                    });
                </script>
                

            </div>
        </div> --}}
        <div class="flex">
            <div class="w-max mb-4 mt-5 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 ">
                <!-- Table content -->
                <table class="table-auto text-left text-gray-600 dark:text-gray-400">
                    <tbody>
                        <tr class="">
                            <td class="font-semibold ">Nama Dosen Wali</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $class->academic_advisor->lecturer_name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Program Studi</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">
                                {{ $class->program->degree }}-{{ $class->program->program_name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Nomor SK Dosen Wali</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">221/PL.43/HK.03.01/2023</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Semester</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $semester }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Kelas/Angkatan</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $class->class_name }}/ {{ $class->academic_year }}
                            </td>
                        </tr>
                        <tr class="">
                            <td class="font-semibold">Semester/Tahun Akademik</td>
                            <td class="font-semibold">:</td>
                            <td class="text-gray-800 dark:text-white">{{ $semester }}/ 2022-2023</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        

        <a href="">
            <button type="button"
                class=" mb-4 hover:text-white text-blue-700 bg-white border-2 border-blue-700 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 48 48">
                    <path fill="#2196F3" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"></path>
                    <path fill="#FFF"
                        d="M25 15.001H39V17H25zM25 19H39V21H25zM25 23.001H39V25.001H25zM25 27.001H39V29H25zM25 31H39V33.001H25z">
                    </path>
                    <path fill="#0D47A1" d="M27 42L6 38 6 10 27 6z"></path>
                    <path fill="#FFF"
                        d="M21.167,31.012H18.45l-1.802-8.988c-0.098-0.477-0.155-0.996-0.174-1.576h-0.032c-0.043,0.637-0.11,1.162-0.197,1.576l-1.85,8.988h-2.827l-2.86-14.014h2.675l1.536,9.328c0.062,0.404,0.111,0.938,0.143,1.607h0.042c0.019-0.498,0.098-1.051,0.223-1.645l1.97-9.291h2.622l1.785,9.404c0.062,0.348,0.119,0.846,0.17,1.511h0.031c0.02-0.515,0.073-1.035,0.16-1.563l1.503-9.352h2.468L21.167,31.012z">
                    </path>
                </svg>
                Export Laporan
            </button>
        </a>

        <a href="{{ route('masterdata.reports.exportPdf', $report->report_id) }}" target="_blank">
            <button type="button" onclick="exportChartAsImage()"
                class=" mb-4 hover:text-white text-red-400 bg-white border-red-400 border-2 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    width="30" height="30" viewBox="0 0 256 256" xml:space="preserve">
                    <defs>
                    </defs>
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                        transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path
                            d="M 19.309 0 C 15.04 0 11.58 3.46 11.58 7.729 v 47.153 v 27.389 c 0 4.269 3.46 7.729 7.729 7.729 h 51.382 c 4.269 0 7.729 -3.46 7.729 -7.729 V 54.882 V 25.82 L 52.601 0 H 19.309 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(226,38,43); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path d="M 78.42 25.82 H 60.159 c -4.175 0 -7.559 -3.384 -7.559 -7.559 V 0 L 78.42 25.82 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(235,103,106); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 30.116 46.949 h -5.944 c -0.966 0 -1.75 0.783 -1.75 1.75 v 9.854 v 6.748 c 0 0.967 0.784 1.75 1.75 1.75 s 1.75 -0.783 1.75 -1.75 v -4.998 h 4.194 c 2.53 0 4.588 -2.059 4.588 -4.588 v -4.177 C 34.704 49.008 32.646 46.949 30.116 46.949 z M 31.204 55.715 c 0 0.6 -0.488 1.088 -1.088 1.088 h -4.194 v -6.354 h 4.194 c 0.6 0 1.088 0.488 1.088 1.089 V 55.715 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 43.703 46.949 h -3.246 c -0.966 0 -1.75 0.783 -1.75 1.75 v 16.602 c 0 0.967 0.784 1.75 1.75 1.75 h 3.246 c 4.018 0 7.286 -3.269 7.286 -7.287 v -5.527 C 50.989 50.218 47.721 46.949 43.703 46.949 z M 47.489 59.764 c 0 2.088 -1.698 3.787 -3.786 3.787 h -1.496 V 50.449 h 1.496 c 2.088 0 3.786 1.699 3.786 3.787 V 59.764 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 65.828 46.949 h -8.782 c -0.967 0 -1.75 0.783 -1.75 1.75 v 16.602 c 0 0.967 0.783 1.75 1.75 1.75 s 1.75 -0.783 1.75 -1.75 V 58.75 h 4.001 c 0.967 0 1.75 -0.783 1.75 -1.75 s -0.783 -1.75 -1.75 -1.75 h -4.001 v -4.801 h 7.032 c 0.967 0 1.75 -0.783 1.75 -1.75 S 66.795 46.949 65.828 46.949 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                    </g>
                </svg>
                Export Laporan
            </button>
        </a>
        <div class="w-full mb-4 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Perkembangan Akademis Mahasiswa Perwalian</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">NIM</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th colspan="{{ $jumlahSemester }}" scope="col" class="px-6 py-3 border-b text-center">
                            Semester</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b text-center">IPK</th>
                    </tr>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        @for ($i = 1; $i <= $jumlahSemester; $i++)
                            <th scope="col" class="px-6 py-3 border-b text-center">{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        @php
                            $totalGpa = 0;
                            $semesterCount = 0;
                        @endphp
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                {{ $student->nim }}</td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                {{ $student->student_name }}</td>
                            @for ($i = 1; $i <= $jumlahSemester; $i++)
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{-- Cek apakah ada nilai semester GPA untuk semester ini --}}
                                    @php
                                        if ($student->gpa_cumulative) {
                                            $semesterGpa = $student->gpa_cumulative->gpa_semester
                                                ->where('semester', $i)
                                                ->first();

                                            if ($semesterGpa && $semesterGpa->semester_gpa !== null) {
                                                // Pastikan semesterGpa bukan null
                                                $semesterGpaValue = $semesterGpa->semester_gpa; // Ambil nilai semester_gpa
                                                $totalGpa += $semesterGpaValue; // Tambahkan ke totalGpa
                                                $semesterCount++; // Tambahkan ke semesterCount
                                            } else {
                                                $semesterGpaValue = null; // Atur menjadi null jika tidak ada
                                            }
                                        }
                                    @endphp
                                    {{ $semesterGpaValue ?? '-' }}
                                </td>
                            @endfor
                            @php
                                // Hitung rata-rata GPA hanya untuk semester yang ditampilkan
                                $averageGpa = $semesterCount > 0 ? $totalGpa / $semesterCount : null;
                            @endphp
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                {{ $averageGpa ? number_format($averageGpa, 2) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="w-full mb-4 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            {{-- <p class="text-2xl font-bold text-gray-700 dark:text-white">Perkembangan Akademis Mahasiswa Perwalian</p> --}}
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

        <div class="w-full mb-4 bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white"> Grafik IPS Mahasiswa Perwalian</p>
            <div id="column-chart"></div>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Data mahasiswa mengundurkan diri/Drop Out</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Jenis Undur Diri</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">SK Penetapan</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($student_resignationDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data pengunduran diri mahasiswa</td>
                        </tr>
                    @else
                        @foreach ($student_resignationDetail as $resignation)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $resignation->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $resignation->resignation_type }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $resignation->decree_number }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $resignation->reason }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Mahasiswa penerima beasiswa/peninjauan ulangan UKT
            </p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="col" class="px-6 py-3 border-b">NIM</th>
                        <th scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th scope="col" class="px-6 py-3 border-b">Jenis Beasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($scholarshipDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data beasiswa</td>
                        </tr>
                    @else
                        @foreach ($scholarshipDetail as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->nim }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->scholarship_type }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Mahasiswa Berprestasi dan Keaktifan Organisasi</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">NPM</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Jenis Prestasi/Organisasi</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Tingkat</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($achievementDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data pengunduran diri mahasiswa</td>
                        </tr>
                    @else
                        @foreach ($achievementDetail as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->nim }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->achievement_type }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->level }}</td>
                            </tr>
                        @endforeach
                        
                    @endif
                </tbody>
            </table>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Surat Peringatan</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">NIM</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Jenis Peringatan</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($warningDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data peringatan</td>
                        </tr>
                    @else
                        @foreach ($warningDetail as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->nim }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->warning_type }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->reason }}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Tunggakan UKT</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">NPM</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Jumlah Tunggakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tuition_arrearDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data Tunggakan</td>
                        </tr>
                    @else
                        @foreach ($tuition_arrearDetail as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->nim }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    Rp. {{ number_format($detail->amount, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="w-full mb-4 mt-5  bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <p class="text-2xl font-bold text-gray-700 dark:text-white">Bimbingan Perwalian</p>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">NPM</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Permasalahan</th>
                        <th rowspan="2" scope="col" class="px-6 py-3 border-b">Solusi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($guidanceDetail->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td scope="row" colspan="4"
                                class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white border-b">
                                Tidak ada data pengunduran diri mahasiswa</td>
                        </tr>
                    @else
                        @foreach ($guidanceDetail as $detail)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->nim }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->student->student_name }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->problem }}</td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $detail->solution }}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>
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

            function exportChartAsImage() {
                html2canvas(document.getElementById("column-chart")).then(canvas => {
                    const chartImage = canvas.toDataURL("image/png");
                    sendChartImageToServer(chartImage);
                });
            }

            // function sendChartImageToServer(chartImage) {
            //     const formData = new FormData();
            //     formData.append("chartImage", chartImage);

            //     // Send POST request to save chart image on server
            //     fetch("{{ route('masterdata.reports.saveChartImage', $report->report_id) }}", {
            //         method: "POST",
            //         headers: {
            //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
            //         },
            //         body: formData
            //     }).then(response => {
            //         if (response.ok) {
            //             // Open a new tab to preview PDF
            //             window.open("{{ route('masterdata.reports.exportPdf', $report->report_id) }}", "_blank");
            //         } else {
            //             console.error("Failed to create PDF preview.");
            //         }
            //     });
            // }
        </script>
    @endsection
</x-app-layout>
