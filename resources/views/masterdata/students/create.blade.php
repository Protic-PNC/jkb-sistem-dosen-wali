<x-app-layout>
    @section('content')

    <section class="bg-white dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @role('admin')
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Pilih atau Tambah Mahasiswa</h2>
                            <form action="{{ route('masterdata.students.store', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                    {{-- Input untuk cek apakah tambah mhs baru atau tidak --}}
                                    <input type="hidden" name="is_new_student" id="is_new_student" value="0">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

                                    <!-- Dropdown Mahasiswa dengan Pencarian -->
                                    <div id="select_student_container" style="display: block">
                                        <label for="select_student" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Mahasiswa</label>
                                        <select id="select_student" name="student_id" class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="" disabled selected>Pilih Mahasiswa</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->student_id }}">{{ $student->student_name }} - {{ $student->nim }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    

                                    <!-- Tambah Mahasiswa -->
                                    <div id="add-student-form" style="display:none;">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Mahasiswa</label>

                                        <div class="w-full">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                            <input type="text" name="student_name" id="student_name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required="">
                                        </div>

                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIM</label>
                                            <input type="text" name="nim" id="nim"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required="">
                                        </div>
                                        <div class="w-full">
                                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                            <select id="select_class" name="class_id"
                                            class="select2 g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            <option value="" disabled selected>Pilih Kelas</option>
                                            @foreach ($student_class as $classes)
                                                <option value="{{ $classes->class_id }}">{{ $classes->class_name }}</option>
                                            @endforeach
                                        </select>
                                        </div>

                                        <div class="w-full">
                                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                                            <textarea name="student_address" id="student_address"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required=""></textarea>
                                        </div>

                                        <div class="w-full">
                                            <label for="number_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telephone</label>
                                            <input type="text" name="student_phone_number" id="student_phone_number"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                required="">
                                        </div>

                                        <div class="w-full">
                                            <label for="signature" class="block mb-2 text-sm font-medium text-gray-700">Upload Tanda Tangan</label>
                                            <input type="file" id="student_signature" name="student_signature"
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                        </div>
                                    </div>

                                    <!-- Checkbox untuk menambah mahasiswa baru dengan onchange langsung -->
                                    <div class="w-full">
                                        <label for="add_new_student" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            <input type="checkbox" id="add_new_student" onchange="toggleAddStudentForm(this)" class="mr-2"> Tambah Mahasiswa Baru
                                        </label>
                                    </div>

                                </div>

                                <div class="mt-2">
                                    <button type="submit" onclick="console.log('Button clicked!');" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
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
        function toggleAddStudentForm(checkbox) {
            if (checkbox.checked) {
                document.getElementById('add-student-form').style.display = 'block';
                document.getElementById('select_student_container').style.display = 'none';
                document.getElementById('is_new_student').value = '1'; //menandakan mhs baru ditambahkan
            } else {
                document.getElementById('add-student-form').style.display = 'none';
                document.getElementById('select_student_container').style.display = 'block';
                document.getElementById('is_new_student').value = '0'; //menandakan mhs diambil dari database
            }
        }
    
        $(document).ready(function() {
            // Aktifkan select2 untuk dropdown mahasiswa
            $('#select_student').select2({
                placeholder: "Pilih Mahasiswa",
                allowClear: true
            });
            
            // Tambahkan validasi jika diperlukan
            $('#select_student').on('select2:select', function (e) {
                $(this).valid();
            });
        });

    </script>
    

    @endsection
</x-app-layout>
