<?php

namespace App\Livewire\Masterdata\Students;

use App\Models\Student;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $file;

    public function updatedFile()
    {
        $this->importStudent();
    }

    public function importStudent()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $importer = new StudentsImport();

        Excel::import($importer, $this->file); // jalankan import

        $successCount = $importer->getSuccessCount(); // ambil jumlah berhasil
        // dd($successCount);

        $this->resetFile(); // reset file input

        if ($successCount > 0) {
            $this->dispatch('saved', message: "$successCount mahasiswa berhasil diimport!");
        } else {
            $this->dispatch('error', message: 'Tidak ada mahasiswa baru yang berhasil diimport. Periksa apakah NIM sudah pernah diinput sebelumnya.');
        }
    }

    public function resetFile()
    {
        $this->file = null;
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.masterdata.students.index');
    }
}
