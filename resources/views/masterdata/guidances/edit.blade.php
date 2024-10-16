<x-app-layout>
    @section('content')
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Edit Bimbingan</h2>
                        <form method="POST"
                            action="{{ route('masterdata.guidances.update', $guidanceDetail->guidance_detail_id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="guidanceForm">
                                <div class="guidance-entry">
                                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                        <div class="w-full">
                                            @role('dosenWali')
                                                <div class="">
                                                    <label for="brand"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                                    <input type="text" name="student_id" id="brand" disabled
                                                        value="{{ $guidanceDetail->student->student_name }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                        required="">
                                                </div>
                                            @endrole
                                            <div class="mt-3">
                                                <label for="message"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Permasalahan</label>
                                                <textarea name="problem" id="message" rows="4" @role('dosenWali') disabled @endrole @disabled($guidanceDetail->solution)
                                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Tulis permasalahan mahasiswa bimbingan...">{{ old('problem', $guidanceDetail->problem ?? '') }}</textarea>
                                            </div>
                                    </div>
                                    <div class="w-full">
                                        <label for="message"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Solusi</label>
                                        <textarea name="solution" id="message" rows="4" @role('mahasiswa') disabled @endrole
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Tulis solusi dari permasalahan tersebut...">{{ old('problem', $guidanceDetail->solution ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="mt-2">
                <button type="submit"
                    class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                    Edit Bimbingan
                </button>
            </div>
        </div>
    </div>
    </form>
@endsection
</x-app-layout>
