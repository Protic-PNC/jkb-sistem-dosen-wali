<x-app-layout>
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

        <section class="bg-white dark:bg-gray-900">
            <div class="py-12">
                <div class="max-w-7xl sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @role('admin')
                            @if ($errors->any())
                            <div id="error-message" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                                role="alert">
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
                            @php
                            $userId = $user;
                            $isNewLecturer = !$user ? 1 : 0; // Jika user null, maka default ke form tambah mahasiswa
                            @endphp
                                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                                    @if (!$isNewLecturer)
                                    Pilih atau
                                    @endif Tambah Dosen
                                </h2>
                                <form action="{{ route('masterdata.lecturers.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                        {{-- Input untuk cek apakah tambah mhs baru atau tidak --}}
                                        <input type="hidden" name="is_new_lecturer" id="is_new_lecturer" value="{{ $isNewLecturer }}">
                                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id ?? ''}}">

                                        <!-- Dropdown Dosen Wali dengan Pencarian -->
                                        <div id="select_lecturer_container" @if($isNewLecturer) style="display:none;" @else style="display:block;" @endif>
                                            <label for="select_lecturer"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Dosen
                                                Wali</label>
                                            <div class="w-full">
                                                <label for="name"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                                <input type="text" name="name" id="name" value="{{ $user->name ?? '' }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled>
                                            </div>

                                            <div class="w-full">
                                                <label for="name"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Dosen Wali</label>
                                                <select id="select_lecturer" name="lecturer_id"
                                                    class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option value="" disabled selected>Pilih Dosen Wali</option>
                                                    @foreach ($lecturers as $lecturer)
                                                        <option value="{{ $lecturer->lecturer_id }}">
                                                            {{ $lecturer->lecturer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <!-- Tambah Dosen Wali -->
                                        <div id="add-lecturer-form"  @if(!$isNewLecturer) style="display:none;" @endif>
                                            @if (!$isNewLecturer)
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Tambah
                                                    Dosen</label>
                                            @endif

                                            <div class="w-full">
                                                <label for="name"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                                <input type="text" name="lecturer_name" id="lecturer_name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>

                                            <div class="w-full">
                                                <label for="nidn"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nidn</label>
                                                <input type="text" name="nidn" id="nidn"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                            <div class="w-full">
                                                <label for="nip"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">nip</label>
                                                <input type="text" name="nip" id="nip"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>

                                            <div class="w-full">
                                                <label for="nim"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                                                <select id="select_position" name="position_id"
                                                    class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <option value="" disabled selected>Pilih Jabatan</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{ $position->position_id }}">
                                                            {{ $position->position_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="w-full">
                                                <label for="address"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                                                <textarea name="lecturer_address" id="lecturer_address"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                                            </div>

                                            <div class="w-full">
                                                <label for="number_phone"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor
                                                    Telephone</label>
                                                <input type="text" name="lecturer_phone_number" id="lecturer_phone_number"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>

                                            <div class="w-full">
                                                <label for="signature"
                                                    class="block mb-2 text-sm font-medium text-gray-700">Upload Tanda
                                                    Tangan</label>
                                                <input type="file" id="lecturer_signature" name="lecturer_signature"
                                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                            </div>
                                        </div>

                                        <!-- Checkbox untuk menambah Dosen Wali baru dengan onchange langsung -->
                                        <div @if($isNewLecturer) hidden @endif class="w-full">
                                            <label for="add_new_lecturer"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                <input type="checkbox" id="add_new_lecturer"
                                                    onchange="toggleAddlecturerForm(this)" class="mr-2" @if($isNewLecturer) checked @endif>
                                                    Tambah Dosen Wali 
                                            </label>
                                        </div>

                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" onclick="console.log('Button clicked!');"
                                            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
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
            function toggleAddlecturerForm(checkbox) {
                if (checkbox.checked) {
                    document.getElementById('add-lecturer-form').style.display = 'block';
                    document.getElementById('select_lecturer_container').style.display = 'none';
                    document.getElementById('is_new_lecturer').value = '1'; //menandakan mhs baru ditambahkan
                } else {
                    document.getElementById('add-lecturer-form').style.display = 'none';
                    document.getElementById('select_lecturer_container').style.display = 'block';
                    document.getElementById('is_new_lecturer').value = '0'; //menandakan mhs diambil dari database
                }
            }

            $(document).ready(function() {
                // Aktifkan select2 untuk dropdown Dosen Wali
                $('#select_lecturer').select2({
                    placeholder: "Pilih Dosen",
                    allowClear: true
                });

                // Tambahkan validasi jika diperlukan
                $('#select_lecturer').on('select2:select', function(e) {
                    $(this).valid();
                });
            });
        </script>


    @endsection
</x-app-layout>
