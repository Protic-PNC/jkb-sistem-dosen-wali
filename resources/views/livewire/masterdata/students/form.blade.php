<div>
    @if ($showModal)
        <div id="programModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4" id="modalTitle">Edit Mahasiswa</h2>
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

                        <div class="mb-4">
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                            <input type="number" wire:model.defer='nim' class="mt-1 block w-full border rounded-md p-2">
                            @error('nim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="studentPhoneNumber" class="block text-sm font-medium text-gray-700">No HP</label>
                            <input type="number" wire:model.defer='studentPhoneNumber' class="mt-1 block w-full border rounded-md p-2">
                            @error('studentPhoneNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="studentAddress" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea wire:model.defer='studentAddress' class="mt-1 block w-full border rounded-md p-2"></textarea>
                            @error('studentAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" wire:model.live='status' name="status" class="mt-1 block w-full border rounded-md p-2">
                                <option value="" disabled>Pilih Status</option>
                                <option value="active">Aktif</option>
                                <option value="graduated">Lulus</option>
                                <option value="academic_leave">Cuti</option>
                                <option value="dropout">Drop Out</option>
                                <option value="resign">Mengundurkan Diri</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="studentClass" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select id="studentClass" wire:model.defer='studentClassId' name="studentClassId" class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Kelas</option>
                                @if ($status != 'academic_leave')
                                    @foreach ($studentClass as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>                                    
                                    @endforeach
                                @endif
                            </select>
                            @error('studentClassId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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