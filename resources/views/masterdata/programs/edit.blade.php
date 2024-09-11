<x-app-layout>

    @section('content')

    <section class="bg-white dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Pilih atau Tambah Mahasiswa</h2>
                            <form action="{{ route('masterdata.programs.update', $program->program_id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                                    {{-- Input untuk cek apakah tambah mhs baru atau tidak --}}
                                    {{-- <input type="hidden" name="is_new_student" id="is_new_student" value="0">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">                                    
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tambah Mahasiswa</label> --}}

                                    <div class="w-full">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                        <input type="text" name="program_name" id="program_name" value="{{ $program->program_name }}"
                                            class="g-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            required="">
                                    </div>
                                    
                                    <div class="w-full">
                                        <label for="degree" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenjang</label>
                                        <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input @checked($program->degree =='D3') id="horizontal-list-radio-license" type="radio" value="D3" name="degree" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="horizontal-list-radio-license" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">D3</label>
                                                </div>
                                            </li>
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input @checked($program->degree =='D4') id="horizontal-list-radio-id" type="radio" value="D4" name="degree" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
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
                                            <option @selected($kaprodi->lecturer_id == $program->head_of_program_id) value="{{ $kaprodi->lecturer_id }}">{{ $kaprodi->lecturer_name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" onclick="console.log('Button clicked!');" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
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
