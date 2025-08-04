<?php

namespace App\Livewire\Gpas;

use App\Models\Lecturer;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $currentSemester;
    public $studentClass;
    public $jumlahSemester;
    public $semester;
    public $program;
    public $degree;
    public $user;
    public $lecturer;

    public function mount()
    {
        $this->user = User::find(Auth::user()->id);
        $this->lecturer = Lecturer::find($this->user->lecturer->id);

        if ($this->user->hasRole('dosenWali')) {
            $this->studentClass = StudentClass::where('academic_advisor_id', $this->lecturer->id ?? null)->get();
            // dd($this->studentClass);
        }
    }


    public function render()
    {
        return view('livewire.gpas.index');
    }
}
