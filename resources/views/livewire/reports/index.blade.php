<div class="flex flex-col gap-2">
    @section('main_folder', 'Laporan')
    @section('title-page', 'Data Laporan Dosen Wali')
    <div
        class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
        <div class="flex gap-1 items-center justify-between w-full">
            <div class="flex items-center gap-1">
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
                <span class="text-sm">Menampilkan data Laporan</span>
            </div>
            {{-- @role('jurusan')
                <select id="programId" wire:model.live='programId' name="programId" class="mt-1 block border border-gray-500 text-gray-500 rounded-md p-2">
                    <option value="" selected>Pilih Program Studi</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->program_name }}</option>
                    @endforeach
                </select>
            @endrole --}}
        </div>
        <!-- Bagian tombol -->
        @role('dosenWali')
        <div class="flex gap-2">
            <button wire:click="$dispatch('create-report')"
                class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                    </svg>                              
                Tambah
            </button>
            @livewire('reports.form', [
                'studentClassId' => $studentClassId,
            ], key(implode('-', $studentClassId)))
        </div>
        @endrole
    </div>
    @role(['dosenWali', 'kaprodi', 'admin'])
    <div class="w-full bg-white border shadow p-4 rounded-lg">
            @livewire('report-table')
    </div>
    @endrole
    @role('jurusan')
    @foreach ($programs as $program)
    @php
        $jumlahSemester = match ($program->degree) {
            'D3' => 6,
            'D4' => 8,
            default => null
        };
    @endphp

    <div 
        x-data="{ open: false }" 
        class="mb-4 bg-white rounded-lg shadow border w-full"
    >
        <!-- Header Dropdown -->
        <button 
            @click="open = !open" 
            class="w-full text-left px-6 py-4 flex justify-between items-center"
        >
            <span class="font-semibold text-gray-800">{{ $program->program_name }}</span>
            <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Content Dropdown -->
        <div x-show="open" x-collapse class="px-4 pb-4">
            <livewire:report-table 
                :program-id="$program->id" 
                :jumlah-semester="$jumlahSemester"
                :key="'report-table-'.$program->id"
            />
        </div>
    </div>
@endforeach

    @endrole
</div>