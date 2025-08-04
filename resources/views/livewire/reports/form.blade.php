<div>
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
                <div class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                    <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}</h2>
                    <form wire:submit="save">
                        <div class="mb-4">
                            <select wire:model.live='studentClassId' id="studentClassId" name="studentClassId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            <option selected="true">Pilih Kelas Perwalian</option>                                
                            @foreach ($studentClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                            @error('semester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <select wire:model.live='semester' id="semester" name="semester" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            <option selected="">Pilih Semester</option>                                
                            @for ($i = 1; $i <= $jumlahSemester; $i++)
                                <option value="{{ $i }}"
                                        @disabled($i > $currentSemester )
                                        @disabled(in_array($i, $usedSemesters))        
                                >Semester {{ $i }}</option>                                
                            @endfor
                        </select>
                            @error('semester') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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