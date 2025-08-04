<div class="flex flex-col gap-2">
    @section('main_folder', 'KRS')
    @section('title-page', 'Data KRS')
    <div class="flex flex-col sm:flex-row justify-between gap-2 w-full">
        <div class="flex flex-col gap-2 w-full overflow-x-hidden">
            <div class="bg-white border shadow p-4 rounded-lg flex items-center justify-between">
                Format KRS
                @role('admin')
                    <div>
                        <!-- Bagian tombol -->
                        <button
                            wire:click="$dispatch('create-format')"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">
                            Tambah
                        </button>
                        @livewire('krs.krs-format.form')
                    </div>
                @endrole
            </div>
            <div class="bg-white border shadow p-4 rounded-lg">
                @livewire('krs-format-table')
            </div>
        </div>
        <div class="flex flex-col gap-2 w-full overflow-x-hidden">
            <div class="bg-white border shadow p-4 rounded-lg flex items-center justify-between">
                <div>
                    Daftar KRS
                    @if ($selectedFormatId)
                        <span class="w-full font-bold text-gray-500 text-sm block">{{ $programName }}</span>
                        <span class="w-full font-bold text-gray-500 text-sm inline-flex items-center gap-1.5">
                            {{ $academicYear }}

                            <svg
                                class="stroke-current"
                                width="17"
                                height="16"
                                viewBox="0 0 17 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                stroke=""
                                stroke-width="1.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                />
                            </svg>

                            Semester {{ $semester }}
                        </span>
                    @endif
                </div>
                @role('mahasiswa')
                <div>
                    <!-- Bagian tombol -->
                    @if ($selectedFormatId)
                    {{-- {{ $krs }} --}}
                        <button
                            {{-- wire:key="tambah-krs-button-{{ $selectedFormatId }}" --}}
                            wire:click="$dispatch('create-krs', { krsFormatId: '{{ $selectedFormatId }}' })"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2">
                            Tambah
                        </button>
                        @livewire('krs.form', ['krsFormatId' => $selectedFormatId])
                    @endif
                </div>
                @endrole
            </div>
            @if ($selectedFormatId)
            <div class="bg-white border shadow p-4 rounded-lg">
                @livewire('krs-table', ['krsFormatId' => $selectedFormatId], key($selectedFormatId))
            </div>
            @else
            <div class="bg-white border shadow p-4 rounded-lg">
                <span class="text-gray-600">Pilih format untuk melihat daftar krs yang sudah diunggah.</span>
            </div>
            @endif
        </div>
    </div>
</div>