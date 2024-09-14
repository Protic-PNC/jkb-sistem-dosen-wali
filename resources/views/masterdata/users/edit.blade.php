<x-app-layout>
    
    @section('main_folder', '/ Master Data')
    @section('href_descendant_folder', route('masterdata.users.index')) 
    @section('descendant_folder', '/ Users')
    @section('href_breadcrumb_extra', route('masterdata.users.edit', $user->id))
    @section('breadcrumb_extra', '/ Modify User')
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

    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit User</h2>
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
                    <form method="POST" action="{{ route('masterdata.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dawerk:text-white">Nama</label>
                                <input value="{{ $user->name }}" type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rayhan Afrizal" required="">
                            </div>
                            <div class="w-full">
                                <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                <input value="{{ $user->email }}" type="text" name="email" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="rayhan@yahoo.com" required="">
                            </div>
                            <div class="w-full">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Abaikan jika tidak ingin mengubah password">
                            
                                    <!-- Icon untuk menampilkan password -->
                                    <button  id="eye-icon" type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            
                                <script>
                                    var passwordInput = document.getElementById('password');
                                    var eyeIcon = document.getElementById('eye-icon');
                                
                                    // Event listener untuk mendeteksi perubahan pada input password
                                    passwordInput.addEventListener('input', function() {
                                        if (passwordInput.value.length > 0) {
                                            eyeIcon.classList.add('visible'); // Tampilkan ikon mata jika ada input
                                        } else {
                                            eyeIcon.classList.remove('visible'); // Sembunyikan ikon mata jika tidak ada input
                                        }
                                    });
                                
                                    function togglePassword() {
                                        if (passwordInput.type === 'password') {
                                            passwordInput.type = 'text';
                                        } else {
                                            passwordInput.type = 'password';
                                        }
                                    }
                                </script>
                            </div>
                            <div>
                                <label for="avatar" class="block mb-2 text-sm font-medium text-gray-700">Upload Profile</label>
                                <input type="file" id="avatar" name="avatar"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                @if ($user->avatar)
                                <!-- Elemen gambar untuk menampilkan preview -->
                                <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Avatar Preview" class=" mt-2 w-20 h-auto object-cover">
                                                                    
                                @endif
                            </div>
                        
                            <script>
                                // Mendapatkan elemen input file dan elemen gambar
                                const fileInput = document.getElementById('avatar');
                                const imagePreview = document.getElementById('avatar-preview');
                        
                                // Event listener untuk input file
                                fileInput.addEventListener('change', function(event) {
                                    const file = event.target.files[0]; // Mengambil file pertama yang dipilih
                                    if (file) {
                                        const reader = new FileReader();
                        
                                        // Ketika file selesai dibaca
                                        reader.onload = function(e) {
                                            imagePreview.src = e.target.result; // Menampilkan gambar pada elemen img
                                        };
                        
                                        reader.readAsDataURL(file); // Membaca file sebagai data URL
                                    }
                                });
                            </script>
                            <div>
                                <label for="role"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                <select id="role" name="role"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @foreach ($role as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', $role->name)) }}
                                    </option>                                    
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
</x-app-layout>
