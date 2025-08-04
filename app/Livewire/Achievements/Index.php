<?php

namespace App\Livewire\Achievements;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $classFilters;

    public function mount()
    {
        $this->classFilters = Auth::user()->lecturer->student_class->map(function ($class) {
                                return [
                                    'class_name' => $class->class_name,
                                    'entry_year' => $class->entry_year,
                                ];
                            })->toArray();
    }

    public function render()
    {
        return view('livewire.achievements.index');
    }
}
