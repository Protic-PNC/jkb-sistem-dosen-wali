<x-app-layout>

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambah Peringatan</h2>
                        <form method="POST" action="{{ route('masterdata.warnings.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="guidanceForm">
                                <div class="guidance-entry">
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div class="w-full">
                                            <div class="">
                                                <label for="select_program"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                                                    Mahasiswa</label>
                                                <select id="select_program" name="student_id"
                                                    class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option value="" disabled selected>Pilih Mahasiswa</option>
                                                    @foreach ($students as $student)
                                                        <option value="{{ $student->student_id }}">
                                                            {{ $student->student_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Flex Container for Warning and Button -->
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 mt-3">
                                                <!-- Warning Input -->
                                                <div class="flex-grow">
                                                    <label for="name"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                                                        Peringatan</label>
                                                    <select id="select_program" name="warning_type"
                                                        class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        <option value="" disabled selected>Pilih Jenis Peringatan
                                                        </option>
                                                        <option value="SP 1">SP 1</option>
                                                        <option value="SP 2">SP 2</option>
                                                        <option value="SP 3">SP 3</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="w-full">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alasan</label>
                                            <input type="text" name="reason" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="Tidak masuk kelas selama 30 jam" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit"
                        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Tambah Bimbingan
                    </button>
                </div>
            </div>
        </div>
        </form>
    @endsection

</x-app-layout>
