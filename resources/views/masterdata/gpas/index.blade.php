<x-app-layout>
    @section('content')
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th rowspan="2" scope="col" class="px-6 py-3 border-b">NIM</th>
                            <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                            <th colspan="{{ $jumlahSemester }}" scope="col" class="px-6 py-3 border-b text-center">Semester</th>
                            <th rowspan="2" scope="col" class="px-6 py-3 border-b text-center">IPK</th>
                            <th rowspan="2" scope="col" class="px-6 py-3 border-b">Aksi</th>

                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            @for ($i = 1; $i <= $jumlahSemester; $i++)
                            
                                <th scope="col" class="px-6 py-3 border-b text-center">{{ $i }}</th>

                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gpa as $data)
                            @foreach ($data->gpa_cumulative as $cumulative )
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $cumulative->student->nim }}</td>
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $cumulative->student->student_name }}</td>
                                    @for ($i = 1; $i <= $jumlahSemester; $i++)
                                        @php
                                            $gpa_semester_value = '-';
                                        @endphp

                                        @foreach ($cumulative->gpa_semester as $gpa_semester)
                                            @if ($gpa_semester->semester == $i)
                                                @php
                                                    $gpa_semester_value = $gpa_semester->semester_gpa;
                                                @endphp
                                            @endif
                                        @endforeach

                                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b text-center">{{ $gpa_semester_value }}</td>
                                    @endfor
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b text-center">{{ $cumulative->cumulative_gpa ?? '-' }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a> <br>
                                        <a href="#" class="font-medium text-yellow-200 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
    @endsection
</x-app-layout>