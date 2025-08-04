<div>
    <div>
        @if ($showModal)
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                    <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                        <h2 class="text-xl font-semibold mb-4" id="modalTitle">{{ $formTitle }}</h2>
                        <form wire:submit="save">
                            <!-- Nama Program -->
                            <div class="mb-4">
                                <label for="userName" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" wire:model.defer='userName' class="mt-1 block w-full border rounded-md p-2">
                                @error('userName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="userEmail" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model.defer='userEmail' class="mt-1 block w-full border rounded-md p-2">
                                @error('userEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            @if (!$isEdit)
                            <div class="mb-4">
                                <label for="userPassword" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" wire:model.defer='userPassword' class="mt-1 block w-full border rounded-md p-2">
                                @error('userPassword') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="studentClass" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="studentClass" wire:model.defer='role' name="role" class="mt-1 block w-full border rounded-md p-2">
                                    <option value="" disabled>Pilih Role</option>
                                    <option value="dosenWali">Dosen Wali</option>
                                    <option value="kaprodi">Koordinator Program Studi</option>
                                    <option value="kajur">Ketua Jurusan</option>
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <div class="mb-4">
                                <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN</label>
                                <input type="number" wire:model.defer='nidn' class="mt-1 block w-full border rounded-md p-2">
                                @error('nidn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                <input type="number" wire:model.defer='nip' class="mt-1 block w-full border rounded-md p-2">
                                @error('nip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="lecturerPhoneNumber" class="block text-sm font-medium text-gray-700">No HP</label>
                                <input type="number" wire:model.defer='lecturerPhoneNumber' class="mt-1 block w-full border rounded-md p-2">
                                @error('lecturerPhoneNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="lecturerAddress" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea wire:model.defer='lecturerAddress' class="mt-1 block w-full border rounded-md p-2"></textarea>
                                @error('lecturerAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
</div>