

<x-app-layout>

    @section('content')

        <section class="bg-white dark:bg-gray-900">
            <div class="py-4 px-2 mx-auto lg:m-8 sm:m-4">
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
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit Mahasiswa</h2>
                <form action="{{ route('masterdata.students.update', $student->student_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="w-full">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                            <input type="text" name="student_name" id="name" value="{{ $student->student_name }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                 required="">
                        </div>
                        <div class="w-full">
                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIM</label>
                            <input type="text" name="nim" id="nim" value="{{ $student->nim }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                 required="">
                        </div>
                        <div>
                            <label for="signature" class="block mb-2 text-sm font-medium text-gray-700">Upload
                                Tanda Tangan</label>
                            <input type="file" id="signature" name="student_signature"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                <img class="h-10 w-10" src="{{ Storage::url($student->student_signature) }}" alt="">
                        </div>
                        <div class="w-full">
                            <label for="address"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">student_address</label>
                            <textarea type="text" name="student_address" id="student_address"  
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required=""> {{ $student->student_address }}</textarea>
                        </div>
                        <div class="w-full">
                            <label for="student_phone_number"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">student_phone_number</label>
                            <input type="text" name="student_phone_number" id="student_phone_number" value="{{ $student->student_phone_number }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required="">
                        </div>
                        <div>
                            <label for="student_class_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                            <select id="class_id" name="class_id" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 darkw:focus:border-primary-500">
                                <option value="{{ $student->class_id ?? '-'}}" selected>{{ $student->student_classes->class_name ?? 'Pilih Kelas' }} </option>
                                @foreach ($student_class as $class)
                                    <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="user_id" class="hidden">User</label>
                            <input type="text" id="user_id" name="user_id" value="{{ $student->user_id }}"
                                class="hidden">
                        </div>

                    </div>
                    <div class="mt-2">
                        <button type="submit" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @endsection
</x-app-layout>
