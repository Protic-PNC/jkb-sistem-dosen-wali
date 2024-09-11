<x-app-layout>
    {{-- @section('main_folder', '/ Master Data')
    @section('descendant_folder', '/ Mahasiswa') --}}

    @section('content')
        <section class="bg-white dark:bg-gray-900">
            <div class="py-4 px-2 mx-auto lg:m-8 sm:m-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 ">Data Mahasiswa</h3>
                    <hr class="border-t-4 my-2 mb-10 rounded-sm bg-gray-300">
                </div>
                <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white dark:from-gray-900">
                </div>
            </div>

            @if (session('success'))
                <div id="success-message"
                    class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            <div class="px-8 pb-8 -mt-20 relative">
                <div class="flex flex-col md:flex-row items-center md:items-end mb-6 mt-6">
                    
                    <img src="{{ Storage::url($student->user->avatar) }}"
                    
                        class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg mb-4 md:mb-0 md:mr-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $student->student_name }}</h2>
                        <p class="text-xl text-gray-950 dark:text-gray-300">{{ $student->nim }}</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-950 dark:text-gray-200 mb-2">Email</h3>
                    <p class="text-gray-950 dark:text-gray-400">{{ $student->user->email }}</p>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-gray-200 mb-2">Alamat</h3>
                        <p class="text-gray-950 dark:text-gray-400">{{ $student->student_address }}</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-gray-200 mb-2">No Telefon</h3>
                        <p class="text-gray-950 dark:text-gray-400">{{ $student->student_phone_number }}</p>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                <div class="flex flex-wrap justify-between items-center">
                    <div class="flex space-x-3 mb-4 md:mb-0">
                        <a href="{{ route('masterdata.users.index') }}"
                                class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-300">
                                Kembali
                            </a>
                        <a href="{{ route('masterdata.students.edit', $student->student_id) }}">
                            <button
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </a>

                    </div>

                </div>
            </div>
        </section>
        
    @endsection
</x-app-layout>
