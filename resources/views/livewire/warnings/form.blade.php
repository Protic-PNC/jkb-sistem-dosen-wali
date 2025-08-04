<div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }} {{ $warning->student->user->name ?? '' }}</h2>
                    <form wire:submit.prevent="save">

                        @if (!$isEdit)
                            <!-- Nama mahasiswa -->
                            <div class="mb-4">
                                <label for="studentId" class="block text-sm font-medium text-gray-700">kelas</label>
                                <select id="studentId" wire:model.live='studentClassId' name="studentId" class="mt-1 block w-full border rounded-md p-2">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($studentClass as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                @error('studentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <!-- Nama mahasiswa -->
                            <div class="mb-4">
                                <label for="studentId" class="block text-sm font-medium text-gray-700">Mahasiswa</label>
                                <select id="studentId" wire:model.defer='studentId' name="studentId" class="mt-1 block w-full border rounded-md p-2">
                                    <option value="">Pilih Mahasiswa</option>
                                    @if ($students)
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('studentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input 
                                type="date" 
                                wire:model="date" 
                                {{-- @if ($dateStart && $dateEnd) --}}
                                    @if ($dateStart)
                                        min="{{ $dateStart->toDateString()?? '' }}" 
                                    @endif
                                    @if ($dateEnd)
                                        max="{{ $dateEnd->toDateString()?? '' }}"
                                    @endif
                                {{-- @endif --}}
                                class="mt-1 block w-full border rounded-md p-2"
                            />
                        </div>

                        <!-- SK Penetapan -->
                        <div class="mb-4">
                            <label for="warningType" class="block text-sm font-medium text-gray-700">Jenis Peringatan</label>
                            <select id="warningType" wire:model.defer='warningType' name="studentId" class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Jenis Peringatan</option>
                                <option value="SP 1">SP 1</option>
                                <option value="SP 2">SP 2</option>
                                <option value="SP 3">SP 3</option>
                            </select>
                            @error('warningType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alasan -->
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Alasan</label>
                            <textarea wire:model.defer='reason' class="mt-1 block w-full border rounded-md p-2"></textarea>
                            @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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