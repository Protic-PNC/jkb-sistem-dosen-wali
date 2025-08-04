<?php

namespace App\Livewire\Masterdata\Students;

use App\Imports\StudentsImport;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class StudentImport extends Component
{
    use WithFileUploads;

    public $file;
    public $message;

    public function save()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        try {
            Excel::import(new StudentsImport, $this->file);
            dd('berhasil impor mahasiswa');
        } catch (\Exception $e) {
            dd('gagal impport');
        }
        
        $student = Student::latest()->first();
        $message = 'Mahasiswa berhasil diimport!';

        $this->dispatch('student-saved', student:$student->id, message:$message);
    }

    public function render()
    {
        return view('livewire.masterdata.students.student-import');
    }
}
