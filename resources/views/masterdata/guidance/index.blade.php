<x-app-layout>
    
    @section('content')
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="col" class="px-6 py-3 border-b">NIM</th>
                        <th scope="col" class="px-6 py-3 border-b">Nama</th>
                        <th scope="col" class="px-6 py-3 border-b">Masalah</th>
                        <th scope="col" class="px-6 py-3 border-b">Solusi</th>
                        <th scope="col" class="px-6 py-3 border-b">Aksi</th>
                </thead>
                <tbody>
                    @foreach ($guidance as $data)

                        @foreach ($data->guidance_detail as $detail)

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $detail->student->nim }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $detail->student->student_name }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $detail->problem }}</td>
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white border-b">{{ $detail->solution }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a> <br>
                                    <a href="#" class="font-medium text-yellow-200 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                            
                        @endforeach
                        
                    @endforeach
                </tbody>
            </table>

        </div>
    @endsection

</x-app-layout>