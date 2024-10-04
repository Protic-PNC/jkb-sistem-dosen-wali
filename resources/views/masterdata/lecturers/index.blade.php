<x-app-layout>

    @section('descendant_folder')
        > &nbsp;&nbsp;Dosen
    @endsection
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

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            @if ($errors->any())
                <div id="error-message"
                    class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
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

            <script>
                function closeAlert(id) {
                    var alert = document.getElementById(id);
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 200); // Menunggu transisi opacity selesai
                }
            </script>
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
                <div class="flex">
                    <a href="{{ route('masterdata.lecturers.create', 'null') }}" class="inline-block">
                        <button type="button"
                            class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Tambah
                            Dosen</button>
                    </a>
                    <div class="inline-block">
                        <form action="{{ route('masterdata.lecturers.import') }}" method="post"
                            enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <input type="file" id="fileInput" name="file" accept=".xlsx, .csv, .xls"
                                style="display: none" onchange="submitForm()">

                            <button type="button" onclick="document.getElementById('fileInput').click()"
                                class="text-white bg-green-800 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg class="me-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40"
                                    height="40" viewBox="0 0 48 48">
                                    <path fill="#169154" d="M29,6H15.744C14.781,6,14,6.781,14,7.744v7.259h15V6z"></path>
                                    <path fill="#18482a" d="M14,33.054v7.202C14,41.219,14.781,42,15.743,42H29v-8.946H14z">
                                    </path>
                                    <path fill="#0c8045" d="M14 15.003H29V24.005000000000003H14z"></path>
                                    <path fill="#17472a" d="M14 24.005H29V33.055H14z"></path>
                                    <g>
                                        <path fill="#29c27f" d="M42.256,6H29v9.003h15V7.744C44,6.781,43.219,6,42.256,6z">
                                        </path>
                                        <path fill="#27663f"
                                            d="M29,33.054V42h13.257C43.219,42,44,41.219,44,40.257v-7.202H29z"></path>
                                        <path fill="#19ac65" d="M29 15.003H44V24.005000000000003H29z"></path>
                                        <path fill="#129652" d="M29 24.005H44V33.055H29z"></path>
                                    </g>
                                    <path fill="#0c7238"
                                        d="M22.319,34H5.681C4.753,34,4,33.247,4,32.319V15.681C4,14.753,4.753,14,5.681,14h16.638 C23.247,14,24,14.753,24,15.681v16.638C24,33.247,23.247,34,22.319,34z">
                                    </path>
                                    <path fill="#fff"
                                        d="M9.807 19L12.193 19 14.129 22.754 16.175 19 18.404 19 15.333 24 18.474 29 16.123 29 14.013 25.07 11.912 29 9.526 29 12.719 23.982z">
                                    </path>
                                </svg>
                                Import Dosen
                            </button>
                            <a href="{{ asset('storage/exports/dosen.xlsx') }}" download type="button"
                                class="text-green-800 bg-white hover:text-white hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg class="me-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40"
                                    height="40" viewBox="0 0 48 48">
                                    <path fill="#169154" d="M29,6H15.744C14.781,6,14,6.781,14,7.744v7.259h15V6z"></path>
                                    <path fill="#18482a" d="M14,33.054v7.202C14,41.219,14.781,42,15.743,42H29v-8.946H14z">
                                    </path>
                                    <path fill="#0c8045" d="M14 15.003H29V24.005000000000003H14z"></path>
                                    <path fill="#17472a" d="M14 24.005H29V33.055H14z"></path>
                                    <g>
                                        <path fill="#29c27f" d="M42.256,6H29v9.003h15V7.744C44,6.781,43.219,6,42.256,6z">
                                        </path>
                                        <path fill="#27663f"
                                            d="M29,33.054V42h13.257C43.219,42,44,41.219,44,40.257v-7.202H29z"></path>
                                        <path fill="#19ac65" d="M29 15.003H44V24.005000000000003H29z"></path>
                                        <path fill="#129652" d="M29 24.005H44V33.055H29z"></path>
                                    </g>
                                    <path fill="#0c7238"
                                        d="M22.319,34H5.681C4.753,34,4,33.247,4,32.319V15.681C4,14.753,4.753,14,5.681,14h16.638 C23.247,14,24,14.753,24,15.681v16.638C24,33.247,23.247,34,22.319,34z">
                                    </path>
                                    <path fill="#fff"
                                        d="M9.807 19L12.193 19 14.129 22.754 16.175 19 18.404 19 15.333 24 18.474 29 16.123 29 14.013 25.07 11.912 29 9.526 29 12.719 23.982z">
                                    </path>
                                </svg>
                                Unduh template
                            </a>
                        </form>

                        <script>
                            function submitForm() {
                                document.getElementById('uploadForm').submit();
                            }
                        </script>
                    </div>
                </div>
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search for items">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        {{-- <th scope="col" class="px-6 py-3">
                        NIP
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NIDN
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Prodi
                    </th> --}}
                        <th scope="col" class="px-6 py-3">
                            Jabatan
                        </th>
                        {{-- <th scope="col" class="px-6 py-3">
                        Nomor HP
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Alamat
                    </th> --}}
                        <th scope="col" class="px-6 py-3">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($lecturers->isEmpty())
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td colspan="6" scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                Tidak ada data Dosen</td>
                        </tr>
                    @else
                        @foreach ($lecturers as $data)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $data->lecturer_name }}
                                </td>
                                {{-- <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $data->nip }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $data->nidn }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $data->program->program_name ?? '-' }}
                            </td> --}}
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $data->position->position_name ?? '-' }}
                                </td>
                                {{-- <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $data->lecturer_phone_number }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $data->address }}
                            </td> --}}
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $data->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <button
                                        onclick="confirmDelete('{{ route('masterdata.lecturers.destroy', $data->lecturer_id) }}')"
                                        type="button"
                                        class="font-medium text-red-600 dark:text-blue-500 hover:underline">Hapus</button>
                                    <a href="{{ route('masterdata.lecturers.edit', $data->lecturer_id) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Modal Konfirmasi Delete -->
        <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div id="alert-additional-content-2"
                class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Konfirmasi</span>
                    <h3 class="text-lg font-medium">Hapus Dosen?</h3>
                </div>
                <div class="mt-2 mb-4 text-sm">
                    Apakah Anda yakin ingin menghapus Dosen ini? Tindakan ini tidak dapat diurungkan.
                </div>
                <div class="flex">
                    <form id="deleteForm" method="post"
                        action="{{ route('masterdata.lecturers.destroy', $data->lecturer_id ?? '') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 14">
                                <path
                                    d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                            </svg>
                            Delete
                        </button>
                    </form>
                    <button onclick="hideModal()"
                        class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800"
                        data-dismiss-target="#alert-additional-content-2" aria-label="Close">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>

        <script>
            function confirmDelete(actionUrl) {
                // Tampilkan modal
                document.getElementById('deleteModal').classList.remove('hidden');
                // Set form action dengan URL delete
                document.getElementById('deleteForm').setAttribute('action', actionUrl);
            }

            function hideModal() {
                // Sembunyikan modal
                document.getElementById('deleteModal').classList.add('hidden');
            }
        </script>

    @endsection
</x-app-layout>
