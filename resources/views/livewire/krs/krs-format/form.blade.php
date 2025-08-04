<div>
    @if ($showModal)
        <div id="programModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 px-2 ">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}</h2>
                    <form wire:submit="save">
                        <!-- Nama Program -->
                        <div class="mb-4">
                            <label for="programId" class="block text-sm font-medium text-gray-700">Pilih Prodi</label>
                            <select id="programId" wire:model.defer='programId' name="programId" class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Prodi</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->degree }} {{ $program->program_name }}</option>
                                @endforeach
                            </select>
                            @error('programId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <input type="number" wire:model.defer='semester' class="mt-1 block w-full border rounded-md p-2">
                            @error('semester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="academicYear" class="block text-sm font-medium text-gray-700">Tahun Akademik</label>
                            <input type="text" wire:model.defer='academicYear' class="mt-1 block w-full border rounded-md p-2">
                            <p class="mt-1 text-xs text-gray-500">Contoh: <strong>2024/2025</strong></p>
                            @error(['academicYearStart', 'academicYearEnd']) <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                            <input wire:model.defer="file" id="file" type="file"
                                class="block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        cursor-pointer border border-gray-300 rounded-md bg-gray-50 focus:ring focus:ring-blue-200 transition" />

                            {{-- Spinner saat file sedang diupload --}}
                            <div wire:loading wire:target="file" class="hidden mt-2 text-sm text-blue-600 ">
                                <div class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span>Mengunggah file...</span>
                                </div>
                            </div>
                            
                            @if($existingFile)
                            <p class="text-sm mt-1">File saat ini:
                                <a
                                    href="{{ asset('storage/' . $existingFile) }}"
                                    target="_blank"
                                    class="text-blue-600 underline">Lihat PDF</a>
                            </p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">File harus dalam format <strong>.pdf</strong>.</p>
                            @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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