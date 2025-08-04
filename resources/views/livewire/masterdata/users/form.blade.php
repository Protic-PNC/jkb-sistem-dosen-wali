<div>
    @if ($showModal)
        <!-- Modal -->
        <div id="userModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}</h2>
                    <form wire:submit='save' enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" wire:model.defer='userName' id="name" name="name" class="mt-1 block w-full border rounded-md p-2">
                            @error('userName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model.defer='userEmail' id="email" name="email" class="mt-1 block w-full border rounded-md p-2">
                            @error('userEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if (!$isEdit)
                            <!-- Role -->
                            <div class="mb-4">
                                <label for="role" id="role-label" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="role" wire:model.live='role' name="role" class="mt-1 block w-full border rounded-md p-2">
                                    <option value="" disabled>Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                    <option value="dosenWali">Dosen Wali</option>
                                    <option value="jurusan">Jurusan</option>
                                    <option value="kaprodi">Kaprodi</option>
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Extra Form (Tampil berdasarkan Role) -->
                        @if ($role != 'admin')
                        <div id="extraFields" class="">
                            @if ($role == 'mahasiswa')
                            <div id="mahasiswaFields" class="">
                                <!-- Student Class -->
                                <div class="mb-4">
                                    <label for="student_class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                                    <select wire:model.defer='studentClassId' id="student_class_id" name="student_class_id" class="mt-1 block w-full border rounded-md p-2">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($studentClasses as $data)
                                        <option value="{{ $data->id }}">{{ $data->class_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('studentClassId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- NIM -->
                                <div class="mb-4">
                                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                                    <input type="number" wire:model.defer='nim' id="nim" name="nim" class="mt-1 block w-full border rounded-md p-2">
                                    @error('nim') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                            </div>
                            @endif

                            @if ($role == 'dosenWali' || $role == 'kaprodi' || $role == 'kajur')
                            <div id="dosenFields" class="">
                                <div class="mb-4">
                                    <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN</label>
                                    <input type="number" wire:model.defer='nidn' id="nidn" name="nidn" class="mt-1 block w-full border rounded-md p-2">
                                    @error('nidn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                    <input type="number" wire:model.defer='nip' id="nip" name="nip" class="mt-1 block w-full border rounded-md p-2">
                                    @error('nip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Tanda Tangan</label>
                                    <input type="file" wire:model.defer='signature' class="mt-1 block w-full border rounded-md p-2">
                                    @error('signature') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                                <input type="number" wire:model.defer='phoneNumber' class="mt-1 block w-full border rounded-md p-2">
                                @error('phoneNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" wire:model.defer='address' class="mt-1 block w-full border rounded-md p-2">
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex justify-end mt-4">
                            <button type="button" id="closeCreateModal" wire:click='cancel' class="mr-2 px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                            <button type="submit" id="saveUser" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>