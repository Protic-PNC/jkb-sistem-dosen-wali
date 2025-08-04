<div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }} {{ $guidance->student->user->name ?? '' }}</h2>
                    <form wire:submit="save">

                        @role('dosenWali')
                        @if ($isValidated == 0)
                            <div class="bg-red-100 border-l-4 border-red-500 p-3 text-sm text-red-800 rounded-md">
                            <strong>Catatan:</strong>
                            {{ $validationNote }}
                        </div>
                        @endif
                        <!-- Pilih Kelas -->
                        <div class="mb-4">
                            <label for="studentId" class="block text-sm font-medium text-gray-700">kelas</label>
                            <select id="studentId" wire:model.live='studentClassId' name="studentId"
                                @if ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id())
                                    disabled
                                @endif
                                @class([
                                            'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                            'bg-gray-100' => ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id())
                                        ])>
                                <option value="">Pilih Kelas</option>
                                @foreach ($studentClass as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            @error('studentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        @endrole
                        <!-- Nama mahasiswa -->
                        <div class="mb-4">
                            <label for="studentId" class="block text-sm font-medium text-gray-700">Mahasiswa</label>
                            <select id="studentId" wire:model.defer='studentId' name="studentId"
                                @class([
                                            'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                            'bg-gray-100' => ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) ||
                                                            ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id)
                                        ])
                                @if($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) disabled @endif
                                @if ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id) disabled @endif>
                                <option value="">Pilih Mahasiswa</option>
                                @if ($students)
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('studentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="problem_date" class="block text-sm font-medium text-gray-700">Tanggal Permasalahan</label>
                            <input  @if (
                                    ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) ||
                                    ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id) ||
                                    ($isEdit && $role === 'mahasiswa' && $solution)  // kondisi tambahan: ada solusi
                                ) readonly @endif
                                type="date" 
                                wire:model.live="problemDate" 
                                @if ($dateStart)
                                    min="{{ $dateStart->toDateString()?? '' }}" 
                                @endif
                                @if ($dateEnd)
                                    max="{{ $dateEnd->toDateString()?? '' }}"
                                @endif
                                @class([
                                        'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                        'bg-gray-100' => ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) ||
                                                        ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id) ||
                                                        ($isEdit && $role === 'mahasiswa' && $solution)  // kondisi tambahan: ada solusi
                                    ])
                            />
                        </div>

                        <!-- Problem -->
                        <div class="mb-4">
                            <label for="problem" class="block text-sm font-medium text-gray-700">Masalah</label>
                            <textarea type="text" wire:model.defer='problem'
                                @class([
                                        'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                        'bg-gray-100' => ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) ||
                                                         ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id) ||
                                                         ($isEdit && $role === 'mahasiswa' && $solution)  // kondisi tambahan: ada solusi
                                    ])
                                @if (
                                    ($isEdit && $role === 'dosenWali' && $createdBy !== Auth::id()) ||
                                    ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::user()->id) ||
                                    ($isEdit && $role === 'mahasiswa' && $solution)  // kondisi tambahan: ada solusi
                                ) readonly @endif></textarea>
                            @error('problem') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if ($isEdit || $role == 'dosenWali')
                            <div class="mb-4">
                                <label for="decreeNumber" class="block text-sm font-medium text-gray-700">Tanggal Solusi</label>
                                <input  @if ($role === 'mahasiswa') readonly @endif
                                    type="date" 
                                    wire:model="solutionDate" 
                                    @if ($problemDate)
                                        min="{{ $problemDate?? '' }}"
                                    @endif
                                    @if ($dateEnd)
                                        max="{{ $dateEnd->toDateString()?? '' }}"
                                    @endif
                                    @class([
                                        'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                        'bg-gray-100' => $role === 'mahasiswa'
                                    ])
                                />
                            </div>
                            <!-- Solution -->
                            <div class="mb-4">
                                <label for="solution" class="block text-sm font-medium text-gray-700">Solusi</label>
                                <textarea type="text" wire:model.defer='solution'
                                @class([
                                        'mt-1', 'block', 'w-full', 'border', 'rounded-md', 'p-2',
                                        'bg-gray-100' => $role === 'mahasiswa'
                                    ])
                                @if ($role === 'mahasiswa') readonly @endif></textarea>
                                @error('solution') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if ($isEdit && $role === 'mahasiswa' && $createdBy !== Auth::id())
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Validasi</label>
                                <div class="flex gap-2 items-center mt-2">
                                    <label><input type="radio" wire:model.live="isValidated" value="1"> Setuju</label>
                                    <label><input type="radio" wire:model.live="isValidated" value="0"> Tidak Setuju</label>
                                </div>
                            </div>

                            @if ($isValidated === "0")
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Catatan Koreksi</label>
                                    <textarea wire:model.defer="validationNote" class="mt-1 block w-full border rounded-md p-2"></textarea>
                                    @error('validationNote') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
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