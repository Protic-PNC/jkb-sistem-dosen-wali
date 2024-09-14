<x-app-layout>

    @section('main_folder', '/ Master Data')
    @section('href_descendant_folder', route('masterdata.student_classes.index', $studentClass->class_id)) 
    @section('descendant_folder', '/ Classes')
    @section('href_breadcrumb_extra', route('masterdata.student_classes.edit', $studentClass->class_id))
    @section('breadcrumb_extra', '/ Modify Class')
    @section('content')

    @section('content')

    <section class="bg-white dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit Kelas</h2>
                            <form action="{{ route('masterdata.student_classes.update', $studentClass->class_id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                        <div class="w-full">
                                            <label for="select_program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Prodi</label>
                                            <select id="select_program" name="program_id" class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="" disabled selected>Pilih Prodi</option>
                                                @foreach ($programs as $program)
                                                    <option @selected($studentClass->program_id == $program->program_id) value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                                            <input type="text" name="class_name" id="class_name" value="{{ $studentClass->class_name ?? '-' }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dosen Wali</label>
                                            <select id="select_lecturer" name="academic_advisor_id"
                                            class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="" disabled selected>Pilih Dosen Wali</option>
                                            @foreach ($lecturers as $lecturer)
                                                <option @selected($studentClass->academic_advisor_id == $lecturer->lecturer_id) value="{{ $lecturer->lecturer_id }}">{{ $lecturer->lecturer_name }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Akademik</label>
                                            <input type="number" name="academic_year" id="academic_year" value="{{ $studentClass->academic_year ?? '-' }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </section>

    @endsection

</x-app-layout>
