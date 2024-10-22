<x-app-layout>

    @section('href_descendant_folder', route('masterdata.student_classes.index')) 
    @section('descendant_folder', '/ Classes')
    @section('breadcrumb_extra', '/ Add New Class')
    @section('content')
    <style>
        .eye-icon {
            display: none; /* Sembunyikan ikon mata secara default */
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            color: gray;
        }

        /* .input-container {
            position: relative; /* Kontainer input harus relatif
            display: flex;
            align-items: center; /* Vertikal center
        } */
    
        .eye-icon.visible {
            display: block; /* Tampilkan ikon mata ketika perlu */
        }

        .password-input {
            padding-right: 30px; /* Sisakan ruang untuk ikon mata */
        }
    </style>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @role('admin')
                        @if ($errors->any())
                            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                                role="alert">
                                <span class="font-medium">Whoops!</span> There were some problems with your input.
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Generate atau Input Manual</h2>
                            <form action="{{ route('masterdata.student_classes.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                    {{-- Input untuk cek apakah tambah mhs baru atau tidak --}}
                                    <input type="hidden" name="is_add_manual" id="is_add_manual" value="0">

                                    <!-- Dropdown Kelas dengan Pencarian -->
                                    <div id="generate_class" style="display: block">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Generate Kelas</label>
                                        <div class="w-full">
                                            <label for="select_program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Prodi</label>
                                            <select id="select_program" name="program_id" class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="" disabled selected>Pilih Prodi</option>
                                                @foreach ($programs as $program)
                                                    <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Akademik</label>
                                            <input type="number" name="entry_year_select" id="entry_year_select"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Banyaknya Kelas</label>
                                            <input type="number" name="total_classes" id="total_classes"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                    </div>
                                    

                                    <!-- Tambah Kelas -->
                                    <div id="add-class-form" style="display:none;">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Kelas</label>

                                        <div class="w-full">
                                            <label for="select_program" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Prodi</label>
                                            <select id="select_program" name="program_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option value="" disabled selected>Pilih Prodi</option>
                                                @foreach ($programs as $program)
                                                    <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                                            <input type="text" name="class_name" id="class_name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dosen Wali</label>
                                            <select id="select_lecturer" name="academic_advisor_id"
                                            class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="" disabled selected>Pilih Dosen Wali</option>
                                            @foreach ($lecturers as $lecturer)
                                                <option value="{{ $lecturer->lecturer_id }}">{{ $lecturer->lecturer_name }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Akademik</label>
                                            <input type="number" name="entry_year_manual" id="entry_year_manual"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                >
                                        </div>
                                    </div>

                                    <!-- Checkbox untuk menambah Kelas baru dengan onchange langsung -->
                                    <div class="w-full">
                                        <label for="add_classes_manual" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            <input type="checkbox" id="add_classes_manual" onchange="toggleAddClassForm(this)" class="mr-2"> Tambah Kelas Manual
                                        </label>
                                    </div>

                                </div>

                                <div class="mt-2">
                                    <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endrole
                </div>
            </div>
        </div>
    </section>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        function toggleAddClassForm(checkbox) {
            if (checkbox.checked) {
                document.getElementById('add-class-form').style.display = 'block';
                document.getElementById('generate_class').style.display = 'none';
                document.getElementById('is_add_manual').value = '1';

                // Remove required attribute from generate_class inputs
                document.getElementById('total_classes').removeAttribute('required');
                document.getElementById('entry_year_select').removeAttribute('required');

                // Add required attribute to add-class-form inputs
                document.getElementById('class_name').setAttribute('required', '');
                document.getElementById('entry_year_manual').setAttribute('required', '');

            } else {
                document.getElementById('add-class-form').style.display = 'none';
                document.getElementById('generate_class').style.display = 'block';
                document.getElementById('is_add_manual').value = '0';

                // Add required attribute back to generate_class inputs
                document.getElementById('total_classes').setAttribute('required', '');
                document.getElementById('entry_year_select').setAttribute('required', '');

                // Remove required attribute from add-class-form inputs
                document.getElementById('class_name').removeAttribute('required');
                document.getElementById('entry_year_manual').removeAttribute('required');
            }
        }

    
        // $(document).ready(function() {
        //     // Aktifkan select2 untuk dropdown Kelas
        //     $('#select_program').select2({
        //         placeholder: "Pilih Kelas",
        //         allowClear: true
        //     });
            
        //     // Tambahkan validasi jika diperlukan
        //     $('#select_program').on('select2:select', function (e) {
        //         $(this).valid();
        //     });
        // });

    </script>

    @endsection
</x-app-layout>