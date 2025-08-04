<?php

namespace App\Livewire\Warnings;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $studentClass;
    public $dateStart;
    public $dateEnd;
    public $semester;
    public $classFilters;

    public function mount()
    {
        $this->studentClass = Auth::user()->lecturer->student_class;

        $this->classFilters = Auth::user()->lecturer->student_class->map(function ($class) {
                                return [
                                    'class_name' => $class->class_name,
                                    'entry_year' => $class->entry_year,
                                ];
                            })->toArray();

        // $entryYear = $this->studentClass->entry_year;
        // $currentSemester = $this->studentClass->current_semester;

        // // ===== Menentukan dateStart (Awal Semester 1 - Agustus) =====
        // $this->dateStart = Carbon::create($entryYear, 8, 1)->startOfMonth(); // 1 Agustus tahun masuk

        // // ===== Menentukan dateEnd (Akhir Semester Sekarang) =====
        // $semesterCount = $currentSemester;

        // // Hitung tahun tambahan berdasarkan semester
        // // Tiap 2 semester = 1 tahun akademik
        // $additionalYears = intdiv($semesterCount, 2);

        // // Jika semester genap (berarti terakhir adalah semester genap: Juli)
        // if ($semesterCount % 2 == 0) {
        //     $endMonth = 7; // Juli
        //     $endYear = $entryYear + $additionalYears - 1;
        // } else {
        //     // Semester ganjil (berakhir di Januari)
        //     $endMonth = 1; // Januari
        //     $endYear = $entryYear + $additionalYears;
        // }

        // $this->dateEnd = Carbon::create($endYear, $endMonth, 1)->endOfMonth();

        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.warnings.index');
    }
}
