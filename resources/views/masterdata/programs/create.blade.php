<x-app-layout>
    
    @section('href_descendant_folder', route('masterdata.programs.index')) 
    @section('descendant_folder', '/ Programs')
    @section('breadcrumb_extra', '/ Add New Program')
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
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambah Program Studi</h2>
                            <form action="{{ route('masterdata.programs.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                    {{-- Input untuk cek apakah tambah mhs baru atau tidak --}}
                                    {{-- <input type="hidden" name="is_new_student" id="is_new_student" value="0">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">                                    
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Mahasiswa</label> --}}

                                    <div class="w-full">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                        <input type="text" name="program_name" id="program_name"
                                            class="g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            required="">
                                    </div>
                                    
                                    <div class="w-full">
                                        <label for="degree" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenjang</label>
                                        <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="horizontal-list-radio-license" type="radio" value="D3" name="degree" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="horizontal-list-radio-license" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">D3</label>
                                                </div>
                                            </li>
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="horizontal-list-radio-id" type="radio" value="D4" name="degree" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="horizontal-list-radio-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">D4</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    

                                    <div class="w-full">
                                        <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kaprodi</label>
                                        <select id="select_class" name="head_of_program_id"
                                        class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" disabled selected>Pilih Kaprodi</option>
                                        @foreach ($kaprodis as $kaprodi)
                                            <option value="{{ $kaprodi->lecturer_id }}">{{ $kaprodi->lecturer_name }}</option>
                                        @endforeach
                                    </select>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        // function toggleAddClassForm(checkbox) {
        //     if (checkbox.checked) {
        //         document.getElementById('add-class-form').style.display = 'block';
        //         document.getElementById('generate_class').style.display = 'none';
        //         document.getElementById('is_add_manual').value = '1';

        //         // Remove required attribute from generate_class inputs
        //         document.getElementById('total_classes').removeAttribute('required');
        //         document.getElementById('academic_year_select').removeAttribute('required');

        //         // Add required attribute to add-class-form inputs
        //         document.getElementById('class_name').setAttribute('required', '');
        //         document.getElementById('academic_year_manual').setAttribute('required', '');

        //     } else {
        //         document.getElementById('add-class-form').style.display = 'none';
        //         document.getElementById('generate_class').style.display = 'block';
        //         document.getElementById('is_add_manual').value = '0';

        //         // Add required attribute back to generate_class inputs
        //         document.getElementById('total_classes').setAttribute('required', '');
        //         document.getElementById('academic_year_select').setAttribute('required', '');

        //         // Remove required attribute from add-class-form inputs
        //         document.getElementById('class_name').removeAttribute('required');
        //         document.getElementById('academic_year_manual').removeAttribute('required');
        //     }
        // }

    
        $(document).ready(function() {
            // Aktifkan select2 untuk dropdown Kelas
            $('#select_program').select2({
                placeholder: "Pilih Kelas",
                allowClear: true
            });
            
            // Tambahkan validasi jika diperlukan
            $('#select_program').on('select2:select', function (e) {
                $(this).valid();
            });
        });

    </script>

    @endsection
</x-app-layout>