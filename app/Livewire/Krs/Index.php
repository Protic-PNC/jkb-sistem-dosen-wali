<?php

namespace App\Livewire\Krs;

use App\Models\Krs;
use App\Models\KrsFormat;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $selectedFormatId = null;
    public $krsFormatSelected;
    public $krsFormats;
    public $formatName = '';
    public $semester;
    public $academicYear;
    public $program;
    public $programName;

    public $student;
    public $krs;
    public $isKrsExist = false;

    #[On('formatSelected')] 
    public function formatSelected($krsFormatId, $semester, $academicYear)
    {
        $this->selectedFormatId = $krsFormatId;
        $this->semester = $semester;
        $this->academicYear = $academicYear;

        $this->krsFormatSelected = KrsFormat::find($this->selectedFormatId);

        // if (!$this->krsFormatSelected) {
        //     $this->dispatch('error', message: 'Format KRS tidak ditemukan.');
        //     return;
        // }

        $this->program = Program::find($this->krsFormatSelected->program_id);

        if (!$this->program) {
            $this->dispatch('error', message: 'Program tidak ditemukan.');
            return;
        }

        $this->programName = $this->program->degree . ' ' . $this->program->program_name;

        if (Auth::user()->roles->first()->name == 'mahasiswa') {
            $this->student = Auth::user()->student;
            $this->krs = Krs::where('student_id', $this->student->id)
                ->where('krs_format_id', $this->selectedFormatId)
                ->first();
        }
    }

    public function mount()
    {
        $role = Auth::user()->roles->first()->name;

        if ($role == 'mahasiswa') {

        }

        $this->krsFormats = KrsFormat::all();
    }

    public function getSemesterRange()
    {
        
    }


    public function render()
    {
        return view('livewire.krs.index');
    }
}
