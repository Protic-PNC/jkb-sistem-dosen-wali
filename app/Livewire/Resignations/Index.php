<?php

namespace App\Livewire\Resignations;

use App\Models\StudentClass;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $studentClass;
    public $dateStart;
    public $dateEnd;
    public $semester;

    public function mount()
    {
        $this->studentClass = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->get();

        $this->dispatch('refresh');
    }


    public function render()
    {
        return view('livewire.resignations.index');
    }
}
