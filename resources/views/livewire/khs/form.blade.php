<div>
    @if ($showModal)
    <!-- Modal -->
    <div
        id="userModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg px-2 py-1">
            <div
                class="modal-content p-6 max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                <h2 class="text-xl font-semibold mb-4">{{ $formTitle }}
                    {{ $userName ?? '' }}</h2>
                <form wire:submit="save" enctype="multipart/form-data">
                    @csrf

                    @if ($createMassal)
                        <div class="mb-4">
                            <label for="studentClassId" class="block font-medium">Kelas</label>
                            <select wire:model.live="studentClassId" class="w-full border rounded p-2">
                                <option value="">Pilih Kelas</option>
                                @foreach($studentClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('studentClassId')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="semester" class="block font-medium">Semester</label>
                            <select
                                id="semester"
                                wire:model.defer='semester'
                                name="semester"
                                class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Semester</option>
                                @for ($i = 1; $i <= $maxSemester; $i++ )
                                <option
                                    value="{{ $i }}"
                                    @disabled(!$studentClassId)>
                                    {{ $i }}
                                </option>
                                @endfor
                            </select>
                            @error('semester')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>

                            <input
                                wire:model="file" required
                                id="file"
                                type="file"
                                accept=".pdf"
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

                            @error('file')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div
                            class="bg-yellow-50 border-l-4 border-yellow-400 p-3 text-sm text-yellow-800 rounded-md">
                            <strong>Petunjuk:</strong>
                            File pdf yang diupload harus berisi data KHS semua mahasiswa aktif di kelas
                            {{ $studentClass ? $studentClass->class_name : 'tersebut' }}
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="studentClassId" class="block font-medium">Kelas</label>
                            <select wire:model.live="studentClassId" class="w-full border rounded p-2">
                                <option value="">Pilih Kelas</option>
                                @foreach($studentClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name ?? '-' }}</option>
                                @endforeach
                            </select>
                            @error('studentClassId')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="studentId" class="block font-medium">Mahasiswa</label>
                            <select wire:model.live="studentId" class="w-full border rounded p-2">
                                <option value="">Pilih Mahasiswa</option>
                                @if ($studentClassId)
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->user->name ?? '-' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('studentId')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">

                            <label for="semester" class="block font-medium">Semester</label>
                            <select
                                id="semester"
                                wire:model.defer='semester'
                                name="semester"
                                class="mt-1 block w-full border rounded-md p-2">
                                <option value="">Pilih Semester</option>
                                @for ($i = 1; $i <= $maxSemester; $i++ )
                                <option
                                    value="{{ $i }}"
                                    @disabled(!$studentClassId)
                                    @if ($usedSemesters) @disabled(in_array($i, $usedSemesters)) @endif>
                                    {{ $i }}
                                </option>
                                @endfor
                            </select>
                            @error('semester')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                            <input
                                wire:model="file"
                                id="file"
                                type="file"
                                accept=".pdf"
                                class="block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100
                                                cursor-pointer border border-gray-300 rounded-md bg-gray-50 focus:ring focus:ring-blue-200 transition"/>
                            @if($existingFile)
                            <p class="text-sm mt-1">File saat ini:
                                <a
                                    href="{{ asset('storage/' . $existingFile) }}"
                                    target="_blank"
                                    class="text-blue-600 underline">Lihat PDF</a>
                            </p>
                            @endif
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
                            <p class="mt-1 text-xs text-gray-500">File harus dalam format
                                <strong>.pdf</strong>.</p>
                            @error('file') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        {{-- <div
                            class="bg-yellow-50 border-l-4 border-yellow-400 p-3 text-sm text-yellow-800 rounded-md">
                            <strong>Petunjuk:</strong>
                            File pdf yang diupload harus berisi data KHS semua mahasiswa aktif di kelas
                            {{ $studentClass ? $studentClass->class_name : 'tersebut' }}
                        </div> --}}
                    @endif

                    <!-- Buttons -->
                    <div class="flex justify-end mt-4">
                        <button
                            type="button"
                            wire:click='cancel'
                            class="mr-2 px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>