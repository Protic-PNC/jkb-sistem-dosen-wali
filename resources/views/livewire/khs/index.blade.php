<div class="flex flex-col gap-2">
    @section('main_folder', 'KHS')
    @section('title-page', 'Data KHS')
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
            <span class="text-sm">Menampilkan data KHS</span>
        </div>
        <!-- Bagian tombol -->
        @role('admin')
        <div class="flex gap-2">
            <button id="openCreateModal" wire:click="$dispatch('create')"
                class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                    </svg>                              
                Tambah
            </button>
            <button id="openCreateModal" wire:click="$dispatch('createMassal')"
                class="hover:bg-[#00593b] bg-white px-3 py-2 text-xs sm:font-semibold hover:text-white text-[#00593b] border border-[#00593b] rounded-lg flex justify-center items-center gap-1">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Zm3 2h2.01v2.01h-2V8h2v2.01h-2V12h2v2.01h-2V16h2v2.01h-2v2H12V18h2v-1.99h-2V14h2v-1.99h-2V10h2V8.01h-2V6h2V4Z" clip-rule="evenodd"/>
                </svg>       
                Tambah 1 Kelas
            </button>
            <livewire:khs.form />
        </div>
        @endrole
    </div>
    <div
        class="w-full bg-white border shadow p-4 rounded-lg">
        @livewire('khs-table')
    </div>
</div>