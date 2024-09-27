<x-app-layout>

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit Tuition Arrear</h2>
                        <form method="POST" action="{{ route('masterdata.tuition_arrears.update', $tuitionArrearDetail->tuition_arrear_details_id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="guidanceForm">
                                <div class="guidance-entry">
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div class="w-full">
                                            <div class="">
                                                <label for="select_program"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                                                    Mahasiswa</label>
                                                    <input type="name" name="student_name" id="name" value="{{ $tuitionArrearDetail->student->student_name }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="" required="" disabled>
                                            </div>
                                        </div>

                                        <div class="w-full">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Tunggakan</label>
                                            <input type="number" name="amount" id="name" value="{{ $tuitionArrearDetail->amount }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="mt-2">
                    <button type="submit"
                        class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        </form>
    @endsection

</x-app-layout>
