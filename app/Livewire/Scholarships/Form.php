<?php

namespace App\Livewire\Scholarships;

use App\Models\Scholarship;
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

    public $scholarship;
    public $scholarshipId;
    public $scholarshipType;

    public $dateStart;
    public $dateEnd;
    public $semester;
    public $showReport;
    public $maxSemester;


    public $formTitle = 'Tambah Data';

    public function rules()
    {
        return  [
            'scholarshipType' => 'required',
            'studentId' => 'required',
            'semester' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'scholarshipType',
            'studentId',
            'studentClassId',
            'semester',
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
        // $classId = $user->lecturer->student_class->id;
        
        $this->semester = $semester;
        $this->showReport = $showReport;
        if ($showReport) {
            // dd($this->semester);
            $this->studentClass = collect([StudentClass::where('id', $this->studentClassId)->first()]);
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

    public function updatedStudentId($value)
    {
        $this->student = Student::find($value);
    }

    #[On('create-scholarship')]
    public function create()
    {
        // $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    #[On('edit-scholarship')]
    public function edit($id)
    {
        $this->scholarship = Scholarship::find($id);
        $this->scholarshipId = $this->scholarship->id;

        // dd($this->scholarship);
    
        $this->studentId = $this->scholarship->student_id;
        $this->scholarshipType = $this->scholarship->scholarship_type;
        $this->semester = $this->scholarship->semester;

        $this->formTitle = 'Edit Data';
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $this->scholarship = Scholarship::find($this->scholarshipId);

            $this->scholarship->update([
                'student_id' => $this->studentId,
                'scholarship_type' => $this->scholarshipType,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil diubah.';
        } else {
            $this->scholarship = Scholarship::create([
                'student_id' => $this->studentId,
                'scholarship_type' => $this->scholarshipType,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil ditambahkan.';

        }
        
        $this->showModal = false;
        $this->dispatch('saved', message: $this->message);
    }

    #[On('delete-scholarship')]
    public function delete($id)
    {
        $this->scholarship = Scholarship::find($id);
        $this->scholarship->delete();

        $this->dispatch('deleted', message: 'Data berhasil dihapus.');
    }
    
    public function render()
    {
        return view('livewire.scholarships.form');
    }
}
