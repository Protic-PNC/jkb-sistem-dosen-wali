<div>
    @if ($showModal)
        <div id="programModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 px-2 ">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}</h2>
                    <form wire:submit="save">
                        <!-- Nama Program -->
                        <div class="mb-4">
                            <label for="programName" class="block text-sm font-medium text-gray-700">Nama prodi</label>
                            <input type="text" wire:model.defer='programName' class="mt-1 block w-full border rounded-md p-2">
                            @error('programName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="degree" class="block text-sm font-medium text-gray-700">Jenjang</label>
                            <select id="degree" wire:model.defer='degree' name="degree" class="mt-1 block w-full border rounded-md p-2">
                                <option value="" disabled>Pilih Jenjang</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                            </select>
                            @error('degree') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="headOfProgramId" class="block text-sm font-medium text-gray-700">Kaprodi</label>
                            <select id="headOfProgramId" wire:model.defer='headOfProgramId' name="headOfProgramId" class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Kaprodi</option>
                                @foreach ($headOfProgram as $kaprodi)
                                    <option value="{{ $kaprodi->id }}">{{ $kaprodi->user->name }}</option>
                                @endforeach
                            </select>
                            @error('headOfProgramId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

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