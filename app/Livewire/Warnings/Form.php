<?php

namespace App\Livewire\Warnings;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentResignation;
use App\Models\Warning;
use Carbon\Carbon;
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

    public $warning;
    public $warningId;
    public $warningType;
    public $reason;
    public $date;

    public $dateStart;
    public $dateEnd;

    public $formTitle = 'Tambah Data';

    public function rules()
    {
        return  [
            'warningType' => 'required',
            'studentId' => 'required',
            'reason' => 'required',
            'date' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'warningType',
            'studentId',
            'reason',
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function mount($dateStart = null, $dateEnd = null, $showReport = null)
    {
        if ($showReport) {
            $this->studentClass = collect([StudentClass::where('id', $this->studentClassId)->first()]);
            // $this->studentClassId = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->first()->id;
            $this->updatedStudentClassId($this->studentClassId);
        } else {
            $this->studentClass = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->where('status', 'active')->get();
        }
        // $this->students = Student::where('student_class_id', Auth::user()->lecturer->student_class->id)->where('status', 'active')->get();
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public function updatedStudentClassId($value)
    {
        if ($value) {
            $studentClass = StudentClass::find($value);

            if (!$this->dateEnd) {
                $entryYear = $studentClass->entry_year;
                $currentSemester = $studentClass->current_semester;

                // ===== Menentukan dateStart (Awal Semester 1 - Agustus) =====
                $this->dateStart = Carbon::create($entryYear, 8, 1)->startOfMonth(); // 1 Agustus tahun masuk

                // ===== Menentukan dateEnd (Akhir Semester Sekarang) =====
                // $semesterCount = $currentSemester;

                // Hitung tahun tambahan berdasarkan semester
                // Tiap 2 semester = 1 tahun akademik
                $additionalYears = intdiv($currentSemester, 2);

                // Jika semester genap (berarti terakhir adalah semester genap: Juli)
                if ($currentSemester % 2 == 0) {
                    $endMonth = 7; // Juli
                    $endYear = $entryYear + $additionalYears;
                } else {
                    // Semester ganjil (berakhir di Januari)
                    $endMonth = 1; // Januari
                    $endYear = $entryYear + $additionalYears + 1;
                }

                $dateEnd = Carbon::create($endYear, $endMonth, 1)->endOfMonth();
                $this->dateEnd = Carbon::create($endYear, $endMonth, 1)->endOfMonth();
            } else {
                $dateEnd = $this->dateEnd;
            }

            // dd($this->dateEnd, $additionalYears);

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
        } else {
            $this->students = null;
        }
    }

    #[On('create-warning')]
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    #[On('edit-warning')]
    public function edit($id)
    {
        $this->warning = Warning::find($id);
        $this->warningId = $this->warning->id;

        // dd($this->warning);
    
        $this->studentId = $this->warning->student_id;
        $this->warningType = $this->warning->warning_type;
        $this->reason = $this->warning->reason;
        $this->date = $this->warning->date;

        $this->formTitle = 'Edit Data';
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $this->warning = Warning::find($this->warningId);

            $this->warning->update([
                'student_id' => $this->studentId,
                'warning_type' => $this->warningType,
                'reason' => $this->reason,
                'date' => $this->date,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil diubah.';
        } else {
            $this->warning = Warning::create([
                'student_id' => $this->studentId,
                'warning_type' => $this->warningType,
                'reason' => $this->reason,
                'date' => $this->date,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ]);

            $this->message = 'Data berhasil ditambahkan.';

        }

        if ($this->warningType == 'SP 3') {
            StudentResignation::updateOrcreate([
                'student_id' => $this->studentId,
                'resignation_type' => 'DO',
                'decree_number' => null,
                'reason' => $this->reason,
                'date' => $this->date,
            ]);

        } else {
            $resignation = StudentResignation::where('student_id', $this->studentId)->first();
            if ($resignation) {
                $resignation->delete();
            }
        }
        
        $this->showModal = false;   
        $this->dispatch('saved', message: $this->message);
    }

    #[On('delete-warning')]
    public function delete($id)
    {
        $this->warning = Warning::find($id);
            if ($this->warning->student->student_resignation) {
                $resignation = StudentResignation::where('student_id', $this->warning->student_id)->first();
                if ($resignation) {
                    $resignation->delete();
                }
            }
        $this->warning->delete();
        $this->dispatch('deleted', message: 'Data berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.warnings.form');
    }
}
