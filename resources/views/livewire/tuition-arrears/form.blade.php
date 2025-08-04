<div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }} {{ $tuitionArrear->student->user->name ?? '' }}</h2>
                    <form wire:submit="save">

                        @if (!$isEdit)
                            <!-- PilihKelas -->
                            <div class="mb-4">
                                <label for="studentId" class="block text-sm font-medium text-gray-700">Kelas</label>
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

                        <!-- Jumlah Tunggakan -->
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Tunggakan</label>
                            <input type="number" wire:model.defer='amount' class="mt-1 block w-full border rounded-md p-2">
                            @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal -->
                        @if (!$isEdit)
                            <div class="mb-4">
                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                                <select id="semester" wire:model.defer='semester' name="semester" class="mt-1 block w-full border rounded-md p-2">
                                    @if (!$showReport)
                                        <option value="">Pilih Semester</option>
                                        @for ($i = 1; $i <= $maxSemester; $i++)
                                            <option @selected($i == $semester) @disabled($i > $currentSemester) value="{{ $i }}">Semester {{ $i }}</option>
                                        @endfor
                                    @else
                                        <option selected value="{{ $semester }}">Semester {{ $semester }}</option>
                                    @endif
                                </select>
                                @error('semester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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