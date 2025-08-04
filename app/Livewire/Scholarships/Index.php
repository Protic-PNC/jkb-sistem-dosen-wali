<?php

namespace App\Livewire\Scholarships;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $className;
    public $entryYear;

    public $classFilters;

    public function mount()
    {
        // $this->className = Auth::user()->lecturer->student_classes->pluck('class_name')->toArray();
        // $this->entryYear = Auth::user()->lecturer->student_classes->pluck('entry_year')->toArray();

        $this->classFilters = Auth::user()->lecturer->student_class->map(function ($class) {
                                return [
                                    'class_name' => $class->class_name,
                                    'entry_year' => $class->entry_year,
                                ];
                            })->toArray();

        // dd($this->classFilters);

        // $this->title = 'Scholarships';
    }
    
    public function render()
    {
        return view('livewire.scholarships.index');
    }
}
