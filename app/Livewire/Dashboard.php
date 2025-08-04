<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GpaCumulative;
use App\Models\GpaStat;
use App\Models\Lecturer;
use App\Models\Report;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{

    public $users;
    public $lecturers;
    public $students;
    public $reports;
    public $studentClassName;
    public $programName;
    public $studentClass;
    public $lecturer;
    public $user;
    public $role;
    public $semester;
    public $degree;
    public $jumlahSemester;

    public $stats;

    public $chartByClassSemester = [];
    public $chartByProgramSemester = [];

    public $studentClasses;

    public function mount()
    {
        $this->users = \App\Models\User::count();
        $this->lecturers = \App\Models\Lecturer::count();
        $this->students = \App\Models\Student::count();
        $this->reports = \App\Models\Report::count();
        $this->user = Auth::user();
        $this->role = $this->user->roles->first()->name;

        if ($this->role == 'dosenWali') {
            $this->lecturer = Lecturer::find($this->user->lecturer->id);
            if ($this->lecturer->student_class)
            {
                $this->studentClass = StudentClass::find($this->lecturer->student_class->first()->id);
                $this->studentClasses = StudentClass::where('academic_advisor_id', $this->lecturer->id)->where('status', 'active')->get();
                $this->loadStatFromDatabase();
            }

            $this->degree = $this->lecturer->student_class->first()->program->degree ?? null;
            $this->jumlahSemester = match ($this->degree) {
                'D3' => 6,
                'D4' => 8,
                default => null
            };

            $this->semester = $this->studentClass->current_semester ?? null;

            $this->students = Student::where('student_class_id', $this->lecturer->student_class->first()->id)->count();
            $this->reports = Report::where('academic_advisor_id', $this->lecturer->id)->count();
        } else if ($this->role == 'kaprodi') {
            $this->lecturer = Lecturer::find($this->user->lecturer->id);
            $this->students = Student::whereHas('student_class', function ($query) {
                $query->where('program_id', $this->lecturer->program->id);
            })->count();
            $this->reports = Report::whereHas('student_class', function($q) {
                $q->where('program_id', $this->lecturer->program->id);
            })->count();
        } else if ($this->role == 'mahasiswa') {
            $this->studentClass = StudentClass::find(Auth::user()->student->student_class_id);
            $this->students = Student::where('student_class_id', $this->studentClass->id)->count();
        }
    }

    public function loadStatFromDatabase()
    {
        $stat = GpaStat::with('gpa_stat_semester')
            ->where('student_class_id', $this->studentClass->id)
            ->first();

        if (!$stat) return;

        $this->stats = [];

        foreach ($stat->gpa_stat_semester as $record) {
            $this->stats[$record->semester] = [
                'total' => $record->total,
                'avg' => $record->avg,
                'min' => $record->min,
                'max' => $record->max,
                'below_3' => $record->below_3,
                'below_3_percent' => $record->below_3_percent,
                'above_equal_3' => $record->above_equal_3,
                'above_equal_3_percent' => $record->above_equal_3_percent,
            ];

            $this->chartByClassSemester[$stat->student_class->class_name][$record->semester] = $record->avg;
        }

        $this->dispatch('updateChart', [
            'labels' => collect($this->stats)->keys()->map(fn($s) => 'Semester ' . $s)->toArray(),
            'values' => collect($this->stats)->pluck('avg')->toArray(),
        ]);
    }


    public function render()
    {
        return view('livewire.dashboard');
    }
}
