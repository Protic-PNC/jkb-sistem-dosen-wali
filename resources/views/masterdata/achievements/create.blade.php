<x-app-layout>

    @section('content')

        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambah Prestasi Mahasiswa</h2>
                        <form method="POST" action="{{ route('masterdata.achievements.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="guidanceForm">
                                <div class="guidance-entry">
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div class="w-full">
                                            <div class="">
                                                <label for="select_program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Mahasiswa</label>
                                            <select id="select_program" name="student_id" class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="" disabled selected>Pilih Mahasiswa</option>
                                                @foreach ($students as $student)
                                                    <option value="{{ $student->student_id }}">{{ $student->student_name }}</option>
                                                @endforeach
                                            </select>
                                            </div>

                                            <!-- Flex Container for Achievement and Button -->
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 mt-3">
                                                <!-- Achievement Input -->
                                                <div class="flex-grow">
                                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Prestasi atau Keaktifan Organisasi</label>
                                                    <input type="text" name="achievement_type" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Juara 1 Lomba blabla, HMTI" required>
                                                </div>
                                                <!-- Button Add Achievement -->
                                                {{-- <div class="mt-3 sm:mt-0">
                                                    <button id="addAchievement" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-yellow-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                                        </svg>
                                                    </button>
                                                </div> --}}
                                            </div>

                                        </div>

                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tingkat</label>
                                            <input type="text" name="level" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Provinsi" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        </form>

    @endsection

</x-app-layout>