<x-app-layout>

    @section('main_folder', '/ Master Data')
    @section('href_descendant_folder', route('masterdata.users.index')) 
    @section('descendant_folder', '/ Users')
    @section('breadcrumb_extra', '/ Add New User')

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Tambah User</h2>
                        <form method="POST" action="{{ route('masterdata.users.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="userForm">
                                <div class="user-entry">
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div class="sm:col-span-2">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dawerk:text-white">Nama</label>
                                            <input type="text" name="users[0][name]" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="Rayhan Afrizal" required="">
                                        </div>
                                        <div class="w-full">
                                            <label for="brand"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                            <input type="text" name="users[0][email]" id="brand"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="rayhan@yahoo.com" required="">
                                        </div>
                                        <div class="w-full">
                                            <label for="price"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                            <input type="password" name="users[0][password]" id="price"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="tulis password" required="">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                                for="user_avatar">Upload profil</label>
                                            <input name="users[0][avatar]"
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                aria-describedby="user_avatar_help" id="avatar" type="file">
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-300"
                                                id="user_avatar_help">A profile picture is useful to confirm your are logged
                                                into your account</div>
                                        </div>
                                        <div>
                                            <label for="role"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                            <select name="users[0][role]" id="role"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                <option selected="">Pilih role</option>
                                                <option value="dosenWali">Dosen Wali</option>
                                                <option value="kaprodi">Kaprodi</option>
                                                <option value="Kajur">Kajur</option>
                                                <option value="mahasiswa">Mahasiswa</option>
                                            </select>
                                            <div class="mt-2">
                                                <button type="button" onclick="removeUserEntry(this)"
                                                    class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-800">
                                                    Hapus Input
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit"
                        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Tambah User
                    </button>
                    <button type="button" onclick="addUserEntry()"
                        class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-500 dark:focus:ring-green-800">
                        Tambah Input
                    </button>
                </div>
            </div>
        </div>
        </form>


        <script>
            let userIndex = 1;

            // Function to add a new user entry
            function addUserEntry() {
                const userEntryTemplate = `
            <div class="user-entry">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" name="users[${userIndex}][name]" id="name_${userIndex}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama" required>
                    </div>
                    <div class="w-full">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="email" name="users[${userIndex}][email]" id="email_${userIndex}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Email" required>
                    </div>
                    <div class="w-full">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="users[${userIndex}][password]" id="password_${userIndex}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Password" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload profil</label>
                        <input name="users[${userIndex}][avatar]" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
                    </div>
                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select name="users[${userIndex}][role]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Pilih role</option>
                            <option value="dosenWali">Dosen Wali</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="kajur">Kajur</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="button" onclick="removeUserEntry(this)" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-800">
                            Hapus Input
                        </button>
                    </div>
                </div>
            </div>
        `;


                document.querySelector('.userForm').insertAdjacentHTML('beforeend', userEntryTemplate);
                userIndex++; // Increment the index for the next user input
                toggleRemoveButtons(); // Recalculate visibility of remove buttons
            }


            // Function to remove user entry
            function removeUserEntry(button) {
                const entry = button.closest('.user-entry');
                entry.remove();
                toggleRemoveButtons(); // Recalculate the remove button visibility
            }


            // Function to show/hide remove buttons based on number of entries
            function toggleRemoveButtons() {
                const entries = document.querySelectorAll('.user-entry');
                const removeButtons = document.querySelectorAll('.user-entry button');

                if (entries.length === 1) {
                    removeButtons.forEach(button => button.style.display = 'none');
                } else {
                    removeButtons.forEach(button => button.style.display = 'inline-block');
                }
            }

            // Initial call to hide remove button if only one row
            toggleRemoveButtons();
        </script>
    @endsection
</x-app-layout>
