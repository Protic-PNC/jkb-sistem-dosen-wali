<div>   
    @section('main_folder', 'Laporan') @section('main_folder-link', route(Auth::user()->roles->first()->name . '-reports.index'))
    @section('sub_folder', 'Detail Laporan')
    @section('title-page', 'Detail Laporan')

    <div
        class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6 mb-4">
        <div class="flex gap-1 items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                class="size-4">
                <path
                    fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                    clip-rule="evenodd"/>
            </svg>
            <span class="text-sm">Menampilkan data Laporan</span>
        </div>
        <!-- Bagian tombol -->
        <div x-data="{ exportDropdownOpen: false }" class="relative inline-block text-left">
            <button @click="exportDropdownOpen = !exportDropdownOpen" type="button"
                class="text-white bg-[#3cba64] hover:bg-[#3cba64]/90 focus:ring-4 focus:outline-none focus:ring-[#1da1f2]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                <svg class="size-6 me-2" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13 11.15V4a1 1 0 1 0-2 0v7.15L8.78 8.374a1 1 0 1 0-1.56 1.25l4 5a1 1 0 0 0 1.56 0l4-5a1 1 0 1 0-1.56-1.25L13 11.15Z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M9.657 15.874 7.358 13H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.358l-2.3 2.874a3 3 0 0 1-4.685 0ZM17 16a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd"/>
                </svg>
                Unduh Laporan
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button> 
            <input type="hidden" id="chartBase64" wire:model.defer="chartBase64">
            <!-- Dropdown menu -->
            <div x-show="exportDropdownOpen" @click.away="exportDropdownOpen = false" class="transition duration-150 ease-in-out absolute z-10 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                <div class="py-1 text-sm text-gray-700">
                    <button onclick="captureChartBeforeExport()" wire:click='exportPdf' class=" w-full block px-4 py-2 hover:bg-gray-100">Export ke PDF</button>
                    <button onclick="captureChartBeforeExport()" wire:click='exportWord' class="w-full block px-4 py-2 hover:bg-gray-100">Export ke Word</button>
                    {{-- Spinner Loading --}}
                    <div wire:loading wire:target="exportPdf, exportWord"
                        class="hidden px-4 py-2 text-sm text-blue-600">
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Menggenerasi file...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'indeks' }" class="w-full flex flex-col md:flex-row gap-4 overflow-hidden">
        <div class="w-full sm:w-[40vh]">
            <div class="bg-white border shadow rounded-lg p-4 flex flex-wrap sm:flex-col gap-2 text-xs">
                <button
                    @click="tab = 'indeks'"
                    :class="tab === 'indeks' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Indeks Prestasi
                </button>
                <button
                    @click="tab = 'resignations'"
                    :class="tab === 'resignations' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Undur Diri
                </button>
                <button
                    @click="tab = 'scholarships'"
                    :class="tab === 'scholarships' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Beasiswa
                </button>
                <button
                    @click="tab = 'achievements'"
                    :class="tab === 'achievements' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Pencapaian
                </button>
                <button
                    @click="tab = 'warnings'"
                    :class="tab === 'warnings' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Peringatan
                </button>
                <button
                    @click="tab = 'tuition-arrears'"
                    :class="tab === 'tuition-arrears' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Tunggakan UKT
                </button>
                <button
                    @click="tab = 'guidances'"
                    :class="tab === 'guidances' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300'"
                    class="inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 flex-1 sm:w-full">
                    Bimbingan
                </button>
            </div>
        </div>

        <div class="flex-grow p-4 rounded-lg mt-2 md:mt-0 border w-full sm:w-[40vh]">
            <div class="bg-white p-6 border shadow rounded-lg mb-4 flex items-start justify-between w-full">
                <table class="table-auto text-left text-gray-600 w-2/3">
                    <tbody>
                        <tr class="">
                            <td class="font-medium ">Nama Dosen Wali</td>
                            <td class="font-medium ">:</td>
                            <td class="text-gray-700 font-medium">{{ $report->academic_advisor->user->name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Program Studi</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">
                                {{ $program->degree }}-{{ $program->program_name }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Nomor SK Dosen Wali</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ $report->academic_advisor_decree }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Kelas/Angkatan</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ $report->class_name }}/{{ $report->entry_year }}
                            </td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Semester</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ convertToRoman($report->semester) }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Tahun Akademik</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ $report->academic_year }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Status Laporan</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">
                                @if ($reportStatus == 'draft')
                                    <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Draf</span>
                                @elseif ($reportStatus == 'submitted')
                                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Dikirim</span>
                                @elseif ($reportStatus == 'approved')
                                    <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">Disetujui</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Dikirim tanggal</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ $reportSubmittedAt ?? '-' }}</td>
                        </tr>
                        <tr class="">
                            <td class="font-medium">Disetujui tanggal</td>
                            <td class="font-medium">:</td>
                            <td class="text-gray-700 font-medium">{{ $reportApprovedAt ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex flex-col items-end w-1/3">
                    @if (Auth::user()->roles->first()->name == 'dosenWali' && $report->status != 'approved')
                        <div class="flex flex-col items-end">
                            <label for="reportStatus" class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                            <select wire:model.live="reportStatus"
                                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="draft" @selected($report->status == 'draft')>Draf</option>
                                <option value="submitted" @selected($report->status == 'submitted')>Kirim</option>
                            </select>
                        </div>
                    @endif

                    @if (Auth::user()->roles->first()->name == 'kaprodi')
                        @if ($report->status == 'submitted')
                            <button type="button" wire:click='approve' class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">Setujui Laporan</button>
                        @elseif ($report->status == 'approved')
                            <button type="button" wire:click='cancelApproval' class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2">Batal</button>
                        @endif
                    @endif
                </div>
            </div>
            <div x-show="tab === 'indeks'" x-cloak>
                <livewire:gpas.gpa-table :date-start="$dateStart" :date-end="$dateEnd" :jumlah-semester="$jumlahSemester" :detail-report="true" :semester="$semester ?? null" :class-id="$studentClass->id ?? null" />
            </div>
            <div x-show="tab === 'resignations'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Undur Diri Mahasiswa</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-resignation')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:resignations.form :show-report="true" :date-start="$dateStart" :date-end="$dateEnd" />
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:resignation-table :show-report="true" :student-class-id="$report->student_class_id" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
            <div x-show="tab === 'scholarships'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Penerima Beasiswa</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-scholarship')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:scholarships.form :show-report="true" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd"  :student-class-id="$report->student_class_id" />
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:scholarship-table :class-name="$report->class_name" :entry-year="$report->entry_year" :show-report="true" :student-class-id="$report->student_class_id" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
            <div x-show="tab === 'achievements'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Prestasi dan Keaktifan Organisasi</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-achievement')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:achievements.form :show-report="true" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd" :student-class-id="$report->student_class_id"/>
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:achievement-table :class-name="$report->class_name" :entry-year="$report->entry_year" :show-report="true" :student-class-id="$report->student_class_id" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
            <div x-show="tab === 'warnings'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Peringatan Mahasiswa</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-warning')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:warnings.form :date-start="$dateStart" :date-end="$dateEnd" :show-report="true"  :student-class-id="$report->student_class_id"/>
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:warning-table :class-name="$report->class_name" :entry-year="$report->entry_year" :show-report="true" :student-class-id="$report->student_class_id" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
            <div x-show="tab === 'tuition-arrears'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Tunggakan UKT semester {{ $semester }}</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-tuition-arrear')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:tuition-arrears.form :show-report="true" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd" :student-class-id="$report->student_class_id"/>
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:tuition-arrear-table :class-name="$report->class_name" :entry-year="$report->entry_year" :show-report="true" :student-class-id="$report->student_class_id" :semester="$semester" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
            <div x-show="tab === 'guidances'" x-cloak>
                @role('dosenWali')
                    <div class="bg-white rounded-lg shadow border w-full flex justify-between items-center p-6">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">Menampilkan data Bimbingan Mahasiswa</span>
                        </div>
                        <!-- Bagian tombol -->
                        @if (Auth::user()->lecturer->student_class)
                            <div class="flex gap-2">
                                <button id="openCreateModal" wire:click="$dispatch('create-guidance')" class="bg-[#00593b] px-3 py-2 text-xs sm:font-semibold text-white rounded-lg flex justify-center items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 ">
                                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>                              
                                    Tambah
                                </button>
                                <livewire:guidances.form :show-report="true" :date-start="$dateStart" :date-end="$dateEnd" :student-class-id="$report->student_class_id"/>
                            </div>
                        @endif
                    </div>
                @endrole
                <livewire:guidance-table :class-name="$report->class_name" :entry-year="$report->entry_year" :show-report="true" :student-class-id="$report->student_class_id" :date-start="$dateStart" :date-end="$dateEnd" />
            </div>
        </div>
    </div>
</div>

<script>
    function captureChartBeforeExport() {
        if (window.gpaChartInstance) {
            const imageUri = window.gpaChartInstance.getImageURI();
            @this.set('chartBase64', imageUri);
        } else {
            console.error('Chart belum digambar!');
        }
    }
</script>
