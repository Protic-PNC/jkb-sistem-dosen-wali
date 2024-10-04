<x-app-layout>
    @section('content')
    <style>
        /* Menghilangkan panah untuk browser Chrome, Safari, dan Edge */
        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* Menghilangkan panah untuk browser Firefox */
        .no-spinner[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    
            <form action="{{ route('masterdata.gpas.update', $studentClass->class_id) }}" method="post">
                @csrf
                @method('PUT')
                <div>
                    <button type="submit" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                        Simpan Nilai
                    </button>
                </div>
                <div class="max-w-7xl bg-white mx-auto sm:px-6 lg:px-8">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th rowspan="2" scope="col" class="px-6 py-3 border-b">NIM</th>
                                <th rowspan="2" scope="col" class="px-6 py-3 border-b">Nama</th>
                                <th colspan="{{ $jumlahSemester }}" scope="col" class="px-6 py-3 border-b text-center">Semester</th>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                @for ($i = 1; $i <= $jumlahSemester; $i++)
                                
                                    <th scope="col" class="px-6 py-3 border-b text-center">{{ $i }}</th>
    
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $student->nim }}</td>
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $student->student_name }}</td>
                                    @for ($i = 1; $i <= $jumlahSemester; $i++)
                                        <td scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">
                                            @php
                                                if($student->gpa_cumulative)
                                                {
                                                    // Mencari nilai semester GPA untuk semester yang sesuai
                                                    $semesterGpa = $student->gpa_cumulative->gpa_semester->where('semester', $i)->first();
                                                }
                                            @endphp
                                            <input value="{{ $semesterGpa->semester_gpa ?? '' }}" @disabled($i > $currentSemester) 
                                                class="@if($i > $currentSemester) bg-gray-300 cursor-not-allowed @else bg-white @endif no-spinner w-full" 
                                                type="number" 
                                                step="0.01" min="0" max="4.00"  name="ips[{{ $student->nim }}][{{ $i }}]" id="">
                                        </td>
                                    @endfor
                                </tr>                            
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </form>
    @endsection
</x-app-layout>