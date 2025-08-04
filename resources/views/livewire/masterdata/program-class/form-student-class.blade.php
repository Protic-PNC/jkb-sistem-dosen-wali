<div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 px-2">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}</h2>
                    @if (!$isEdit)
                        <div class="flex gap-2 justify-between mb-4" id="create_type">
                            <div class="flex w-full items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                                <input id="bordered-radio-1" type="radio" value="0" name="create_type"  wire:model.live='createType'
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="bordered-radio-1" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Input Manual</label>
                            </div>
                            <div class="flex w-full items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                                <input id="bordered-radio-2" type="radio" value="1" name="create_type" wire:model.live='createType'
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="bordered-radio-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Generate</label>
                            </div>
                        </div>

                    @endif
                    <form wire:submit="save">
                        @if ($createType == 1)
                            <div class="mb-4">
                                <label for="entryYear" class="block text-sm font-medium text-gray-700">Angkatan</label>
                                <input type="number" wire:model.defer='entryYear' name="entryYear" id="entry_year_select"
                                    class="mt-1 block w-full border rounded-md p-2">
                                @error('entryYear') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="mb-4">
                                <label for="    " class="block text-sm font-medium text-gray-700">Banyaknya Kelas</label>
                                <input type="number" wire:model.defer='totalClasses' name="totalClasses" id="totalClasses"
                                    class="mt-1 block w-full border rounded-md p-2">
                                @error('totalClasses') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div> 
                        @elseif ($createType == 0)
                            {{-- <div class="mb-4">
                                <label for="className" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                <input type="text" wire:model.defer='className' name="className" id="className"
                                    class="mt-1 block w-full border rounded-md p-2">
                                @error('className') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="mb-4">
                                <label for="entryYear" class="block text-sm font-medium text-gray-700">Angkatan</label>
                                <input type="number" wire:model.defer='entryYear' name="entryYear" id="entryYear"
                                    class="mt-1 block w-full border rounded-md p-2">
                                @error('entryYear') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="mb-4">
                                <label for="academicAdvisorId" class="block text-sm font-medium text-gray-700">Dosen Wali</label>
                                <select id="academicAdvisorId" wire:model.defer='academicAdvisorId' name="academic_advisor_id"
                                    class="mt-1 block w-full border rounded-md p-2">
                                    <option value="">Pilih Dosen Wali</option>
                                    @foreach ($academicAdvisor as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('academicAdvisorId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="mb-4">
                                <label for="academicAdvisorDecree" class="block text-sm font-medium text-gray-700">Nomor SK Dosen Wali</label>
                                <input type="text" wire:model.defer='academicAdvisorDecree' name="academicAdvisorDecree" id="academicAdvisorDecree"
                                    class="mt-1 block w-full border rounded-md p-2">
                                @error('academicAdvisorDecree') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        <!-- Buttons -->
                        <div class="flex justify-end mt-4 gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
                            <button wire:click.prevent="cancel" class="close-modal px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>