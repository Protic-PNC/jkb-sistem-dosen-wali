<?php

namespace App\Livewire\Masterdata\Lecturers;

use App\Imports\LecturersImport;
use App\Models\Lecturer;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithFileUploads;

    public $lecturer;
    public $file;

    #[On('lecturer-saved')]
    public function updateLecturerList($lecturer, $message)
    {
        
    }

    public function updatedFile()
    {
        $this->importLecturer();
    }

    public function importLecturer()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $importer = new LecturersImport();

        Excel::import($importer, $this->file);

        $successCount = $importer->getSuccessCount();
        
        $this->resetFile();        

        if ($successCount > 0) {
            $message = "$successCount Dosen berhasil diimport!";
            $this->dispatch('saved', message:$message);
        } else {
            $message = "Tidak ada Dosen baru yang berhasil diimport. Periksa apakah NIDN sudah pernah diinput sebelumnya.";
            $this->dispatch('error', message:$message);
        }
    }

    public function resetFile()
    {
        $this->file = null;
    }

    public function mount()
    {
        $this->lecturer = Lecturer::all();
    }

    public function render()
    {
        return view('livewire.masterdata.lecturers.index');
    }
}
