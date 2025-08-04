<?php

namespace App\Livewire\TuitionArrears;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\TuitionArrear;
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

    public $tuitionArrear;
    public $tuitionArrearId;
    public $amount;
    public $date;

    public $dateStart;
    public $dateEnd;
    public $semester;
    public $showReport;
    public $maxSemester;

    public $formTitle = 'Tambah Data';

    public function rules()
    {
        return  [
            'studentId' => 'required',
            'amount' => 'required|numeric|max:99999999.99', // batas dari decimal(10,2)
            // 'semester' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'studentId',
            'amount',
        ]);
    }

    public function cancel()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->resetForm();
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

    #[On('create-tuition-arrear')]
    public function create()
    {
        $this->resetForm();
        $this->formTitle = 'Tambah Data';
        $this->isEdit = false;
        $this->showModal = true;
    }

    #[On('edit-tuition-arrear')]
    public function edit($id)
    {
        $this->tuitionArrearId = $id;
        $this->tuitionArrear = TuitionArrear::find($id);
        $this->studentId = $this->tuitionArrear->student_id;
        $this->amount = $this->tuitionArrear->amount;
        $this->semester = $this->tuitionArrear->semester;

        $this->formTitle = 'Edit Data';
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $this->tuitionArrear->update([
                'student_id' => $this->studentId,
                'amount' => $this->amount,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil diubah.';

        } else {
            TuitionArrear::create([
                'student_id' => $this->studentId,
                'amount' => $this->amount,
                'semester' => $this->semester,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil ditambahkan.';

        }
        
        $this->dispatch('saved', message: $this->message);
        $this->resetForm();
        $this->showModal = false;
    }

    #[On('delete-tuition-arrear')]
    public function delete($id)
    {
        $this->tuitionArrearId = $id;
        $this->tuitionArrear = TuitionArrear::find($id);
        $this->tuitionArrear->delete();
        $this->message = 'Data berhasil dihapus.';

        $this->dispatch('deleted', messaga: $this->message);
    }


    public function render()
    {
        return view('livewire.tuition-arrears.form');
    }
}
