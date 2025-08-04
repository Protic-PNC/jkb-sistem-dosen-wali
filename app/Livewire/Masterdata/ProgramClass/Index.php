<?php

namespace App\Livewire\Masterdata\ProgramClass;

use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public $selectedProgramId = null;
    public $programName = '';

    protected $listeners = ['programSelected'];
    public function programSelected($programId, $programName)
    {
        $this->selectedProgramId = $programId;
        $this->programName = $programName;
    }

    public function render()
    {
        return view('livewire.masterdata.program-class.index');
    }
}
