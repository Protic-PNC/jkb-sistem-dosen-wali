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
        <div>
            <a href="{{ route('masterdata.gpas.edit', $studentClass->class_id) }}" class="inline-block">
                <button type="button"
                    class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                    Edit Nilai
                </button>
            </a>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">

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
                                                if($student->gpa_cumulative)
                                                {
                                                    $semesterGpa = $student->gpa_cumulative
                                                    ->gpa_semester
                                                    ->where('semester', $i)
                                                    ->first();
                                                }
                                            @endphp
                                            {{ $semesterGpa->semester_gpa ?? '-' }}
                                        </td>
                                    @endfor
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                    {{ $student->gpa_cumulative->cumulative_gpa ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-app-layout>
