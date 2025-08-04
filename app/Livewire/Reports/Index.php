<?php

namespace App\Livewire\Reports;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Report;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $user;
    public $lecturer;
    public $studentClass;
    public $studentClassId;
    public $degree;
    public $jumlahSemester;
    public $semester;
    public $currentSemester;
    public $usedSemester;
    public $reports;

    public $programs;
    public $program;
    public $programId;

    #[On('saved')]
    public function refresh()
    {
        $this->mount();
    }

    public function mount()
    {
        $this->user = User::find(Auth::user()->id);
        
        if ($this->user->hasRole('dosenWali')) {
            $this->lecturer = Lecturer::find($this->user->lecturer->id);
            $studentClass = StudentClass::where('academic_advisor_id', $this->lecturer->id)->where('status', 'active');
            $this->studentClassId = $studentClass->pluck('id')->toArray();

            $this->reports = Report::where('academic_advisor_id', $this->lecturer->id)->get();
            // $this->usedSemester = $this->reports->pluck('semester')->toArray();
            
            // dd($this->jumlahSemester, Auth::user());
        } else if ($this->user->hasRole('jurusan')) {
            $this->programs = Program::all();
            // $this->updatedProgramId($this->programs->first()->id);
        } else {
            $this->reports = Report::all();
        }
    }

    public function updatedProgramId($value) {
        $this->program = Program::find($value);

        $this->jumlahSemester = match ($this->program->degree) {
            'D3' => 6,
            'D4' => 8,
            default => null
        };
        // $this->studentClassId = $this->studentClass->pluck('id')->toArray();
    // $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.reports.index');
    }
}
