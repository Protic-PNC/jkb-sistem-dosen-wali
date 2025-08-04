<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    public $createType = 0;
    public $formTitle;
    public $message;

    public $showModal = false;
    public $isEdit = false;

    public $reports;
    public $report;
    public $user;
    public $lecturer;
    public $studentClass;
    public $jumlahSemester;
    public $currentSemester;
    public $usedSemesters;

    public $academicAdvisorId;
    public $studentClassId;
    public $academicAdvisorDecree;
    public $academicAdvisorName;
    public $className;
    public $entryYear;
    public $academicYear;
    public $status;
    
    public $semester;
    public $semesterStart;
    public $semesterEnd;
    public $studentClasses;

    protected $listeners = ['delete-report' => 'delete'];

    public function mount($studentClassId = [])
    {
        $this->studentClasses = StudentClass::whereIn('id', $studentClassId)->get();
        $this->studentClassId = null;
        // dd($this->studentClasses);
    }

    public function updatedStudentClassId($value) {
        $this->studentClass = StudentClass::find($value);
        $this->reports = Report::where('student_class_id', $this->studentClass->id)->get();
        $this->currentSemester = $this->studentClass->current_semester;
        $this->usedSemesters = $this->reports->pluck('semester')->toArray();

        $this->jumlahSemester = match ($this->studentClass->program->degree) {
            'D3' => 6,
            'D4' => 8,
        };
    }

    public function rules()
    {
        return [
            'studentClassId' => 'required',
            'semester' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'studentClassId.required' => 'Semester wajib dipilih',
            'semester.required' => 'Semester wajib dipilih',
        ];
    }

    public function updatedCreateType()
    {
        $this->resetSemesterFields();
        $this->resetValidation();
    }

    public function resetSemesterFields()
    {
        $this->reset([
            'semester',
            'semesterStart',
            'semesterEnd',
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'studentClassId',
            'semester',
        ]);
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->isEdit = false;
        $this->formTitle = '';
    }

    #[On('create-report')]
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
        $this->formTitle = 'Buat Laporan';
    }

    #[On('edit-report')]
    public function edit($id)
    {
        $this->report = Report::find($id);
        
    }

    public function delete($id)
    {
        $this->report = Report::find($id);

        $this->report->delete();
        $this->message = 'Laporan berhasil dihapus.';

        $this->dispatch('saved', message:$this->message);
    }

    public function save()
    {
        // dd($this->createType, $this->semester, $this->semesterStart, $this->semesterEnd);
        $this->validate();
        if (!$this->isEdit) {
            $this->user = Auth::user();
            $this->lecturer = $this->user->lecturer;
            $this->studentClass = StudentClass::find($this->studentClassId);

            $currentYear = now()->year;
            $currentMonth = now()->month;

            if ($this->semester %2 == 1) {
                $startYear = $currentYear;

                if ($currentMonth < 8) {
                    $startYear--;
                }
                $this->academicYear = $startYear . '/' . ($startYear + 1);
            } else {
                $startYear = $currentYear - 1;

                if ($currentMonth >= 8) {
                    $startYear++;
                }
                $this->academicYear = $startYear . '/' . ($startYear + 1);
            }

            $this->report = $this->studentClass->report()->create([
                'academic_advisor_id' => $this->lecturer->id,
                'student_class_id' => $this->studentClass->id,
                'academic_advisor_decree' => $this->studentClass->academic_advisor_decree,
                'academic_advisor_name' => $this->lecturer->user->name,
                'class_name' => $this->studentClass->class_name,
                'entry_year' => $this->studentClass->entry_year,
                'semester' => $this->semester,
                'academic_year' => $this->academicYear,
            ]);
            $this->message = 'Laporan berhasil dibuat.';
        }

        $this->showModal = false;
        $this->isEdit = false;
        $this->resetForm();
        $this->dispatch('saved', message:$this->message);
    }

    public function render()
    {
        return view('livewire.reports.form');
    }
}
