<?php

namespace App\Livewire\Achievements;

use App\Models\Achievement;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    public $message;
    public $showModal = false;
    public $isEdit = false;

    public $students;
    public $student;
    public $studentId;

    public $studentClass;
    public $studentClassId;
    public $currentSemester;

    public $achievement;
    public $achievementId;
    public $achievementType;
    public $level;
    public $date;
    public $className;

    public $dateStart;
    public $dateEnd;
    public $semester;
    public $showReport;
    public $maxSemester;

    public $formTitle = 'Tambah Data';

    public function rules()
    {
        return  [
            'achievementType' => 'required',
            'studentId' => 'required',
            'level' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'achievementType',
            'studentId',
            // 'studentClassId'
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function mount($dateStart = null, $dateEnd = null, $semester = null, $showReport = null)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;

        $user = Auth::user();
        // $this->studentClass = StudentClass::where('academic_advisor_id', $user->lecturer->id)->get(); 
        $this->semester = $semester;
        $this->showReport = $showReport;
        if ($showReport) {
            // dd($this->semester);
            $this->studentClass = collect([StudentClass::where('id', $this->studentClassId)->first()]);
            // $this->studentClassId = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->first()->id;
            $this->updatedStudentClassId($this->studentClassId);
        } else {
            $this->studentClass = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->where('status', 'active')->get();
        } 
    }

    public function updatedStudentClassId($value)
    {
        if ($value) {
            $dateEnd = $this->dateEnd;
            $studentClass = StudentClass::find($value);
            $query = Student::where('student_class_id', $studentClass->id)
                ->where(function ($q) use ($dateEnd) {
                    $q->where('status', 'active');

                    if ($dateEnd) {
                        $q->orWhere(function ($subQ) use ($dateEnd) {
                            $subQ->whereNotNull('inactive_at')
                                ->where('inactive_at', '>', $dateEnd);
                        });
                    }
            });

            $this->students = $query->get(); // result disimpan sebagai Collection

            $this->maxSemester = match ($studentClass->program->degree) {
                'D3' => 6,
                'D4' => 8,
            };
            $this->currentSemester = $studentClass->current_semester;
        } else {
            $this->students = null;
        }
    }

    #[On('create-achievement')]
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    #[On('edit-achievement')]
    public function edit($id)
    {
        $this->achievement = Achievement::find($id);
        $this->achievementId = $this->achievement->id;

        // dd($this->achievement);
    
        $this->studentId = $this->achievement->student_id;
        $this->achievementType = $this->achievement->achievement_type;
        $this->level = $this->achievement->level;
        $this->semester = $this->achievement->semester;

        $this->formTitle = 'Edit Data';
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $this->achievement = achievement::find($this->achievementId);

            $this->achievement->update([
                'student_id' => $this->studentId,
                'achievement_type' => $this->achievementType,
                'level' => $this->level,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil diubah.';
        } else {
            $this->achievement = achievement::create([
                'student_id' => $this->studentId,
                'achievement_type' => $this->achievementType,
                'level' => $this->level,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil ditambahkan.';

        }
        
        $this->showModal = false;
        $this->dispatch('saved', message: $this->message);
    }

    #[On('delete-achievement')]
    public function delete($id)
    {
        $this->achievement = achievement::find($id);
        $this->achievement->delete();

        $this->dispatch('deleted', message: 'Data berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.achievements.form');
    }
}
