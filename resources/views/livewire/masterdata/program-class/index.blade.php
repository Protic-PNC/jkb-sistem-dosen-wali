<div class="flex flex-col gap-2">
    @section('main_folder', 'Program & Class')
    @section('title-page', 'Data Program Studi & Kelas')
    <div
        class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
        <div class="flex gap-1 items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="size-4">
                <path
                    fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                    clip-rule="evenodd"/>
            </svg>
            <span class="text-sm">Menampilkan data program studi dan kelas</span>
        </div>
        <button
            class="bg-transparent border border-[#00593b] px-2 py-1 text-[#00593b] rounded-md hover:text-white hover:bg-[#00593b] hover:border-transparent">
            <div class="flex gap-1 justify-between items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="size-4">
                    <path
                        fill-rule="evenodd"
                        d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm5.845 17.03a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V12a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3Z"
                        clip-rule="evenodd"/>
                    <path
                        d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z"/>
                </svg>
            </div>
        </button>
    </div>
    <div
        class="flex flex-col sm:flex-row justify-between items-start gap-2 sm:gap-4">
        <div class="sm:w-[800px] w-full bg-white border shadow p-4 rounded-lg flex flex-col items-center justify-center">
            <div class="w-full flex items-center justify-between">
                <!-- Span tetap di tengah -->
                <span class="font-bold">
                    Daftar Program Studi
                </span>

                <div>
                    <!-- Bagian tombol -->
                    <button
                        wire:click="$dispatch('create-program')"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">
                        Tambah
                    </button>
                    @livewire('masterdata.program-class.form-program')
                </div>
            </div>
            <div class="w-full max-w-screen-xl mx-auto">
                @livewire('program-table')
            </div>
        </div>
        <div class="w-full  @if (!$selectedProgramId) flex flex-col gap-2 @endif">
            <div class="w-full bg-white border shadow rounded-lg">
                <div class="p-4 w-full flex justify-between items-center overflow-x-auto">
                    <div class="">
                        <span class="w-full font-bold">Daftar Kelas</span>
                        @if ($selectedProgramId)
                            <span class="w-full font-bold text-gray-500 text-sm block">{{ $programName }}</span>
                        @endif
                    </div>
                    <div>
                        <!-- Bagian tombol -->
                        @if ($selectedProgramId)
                            <button wire:click="$dispatch('create-student-class', { programId: '{{ $selectedProgramId }}' })"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">
                                Tambah
                            </button>
                            @livewire('masterdata.program-class.form-student-class', ['programId' => $selectedProgramId])
                        @endif
                    </div>
                </div>
            </div>
            @if($selectedProgramId)
                @livewire('student-class-table', ['programId' => $selectedProgramId, 'programName' => $programName], key($selectedProgramId))
            @else
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <span class="text-gray-600">Pilih program studi untuk melihat daftar kelas.</span>
                </div>
            @endif
        </div>
    </div>
</div>
</div>