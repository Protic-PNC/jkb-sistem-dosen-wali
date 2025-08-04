<?php

namespace App\Livewire\Resignations;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentResignation;
use App\Models\Warning;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    public $message;
    public $showReport;
    public $showModal = false;
    public $isEdit = false;

    public $students;
    public $student;
    public $studentId;

    public $studentClass;
    public $studentClassId;

    public $resignation;
    public $resignationType;
    public $decreeNumber;
    public $reason;
    public $date;

    public $dateStart;
    public $dateEnd;

    public $formTitle = 'Tambah Data';

    public function rules()
    {
        return  [
            'resignationType' => 'required',
            'studentClassId' => 'required',
            'studentId' => 'required',
            'decreeNumber' => 'required',
            'reason' => 'required',
            'date' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'resignationType',
            'studentId',
            'studentClassId',
            'decreeNumber',
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
            $this->studentClass = collect([StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->first()]);
            $this->studentClassId = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->first()->id;
            $this->updatedStudentClassId($this->studentClassId);
        } else {
            $this->studentClass = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->where('status', 'active')->get();
        }
        // $this->students = Student::where('student_class_id', Auth::user()->lecturer->student_class->id)->where('status', 'active')->get();
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public function updatedStudentClassId($value) {
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
                    $endYear = $entryYear + $additionalYears+1;
                }

                // dd($currentSemester);

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

    #[On('create-resignation')]
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    #[On('edit-resignation')]
    public function edit($id)
    {
        $resignation = StudentResignation::findOrFail($id);

        if (!$resignation) {
            $this->dispatch('error', message:'Data pengunduran diri tidak ditemukan.');
            return;
        }
        // dd($resignation);
    
        $this->studentId = $resignation->student_id;
        $this->resignationType = $resignation->resignation_type;
        $this->decreeNumber = $resignation->decree_number;
        $this->reason = $resignation->reason;
        $this->date = $resignation->date;

        $this->formTitle = 'Edit Data';
        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $this->resignation = StudentResignation::find($this->student->student_resignation->id);

            $this->resignation->update([
                'student_id' => $this->studentId,
                'resignation_type' => $this->resignationType,
                'decree_number' => $this->decreeNumber,
                'reason' => $this->reason,
                'date' => $this->date,
            ]);

            $this->message = 'Data berhasil diubah.';
        } else {
            $this->resignation = StudentResignation::create([
                'student_id' => $this->studentId,
                'resignation_type' => $this->resignationType,
                'decree_number' => $this->decreeNumber,
                'reason' => $this->reason,
                'date' => $this->date,
            ]);

            $this->message = 'Data berhasil ditambahkan.';

        }

        //Tambahkan ke tb warning dengan jenis peringatan untuk mhs dropout
        $warning = Warning::where('student_id', $this->studentId)->first();
        if ($this->resignationType == 'DO') {
            if (!$warning)
            {
                Warning::updateOrCreate([
                    'student_id' => $this->studentId,
                    'warning_type' => 'SP 3',
                    'reason' => $this->reason,
                    'date' => $this->date,
                ]);
            }

            $this->student = Student::find($this->studentId);

            if ($this->student) {
                $this->student->update([
                    'status' => 'dropout',
                    'inactive_at' => $this->date
                ]);
            }
        } else {
            if ($warning)
            {
                $warning->delete();
            }

            $this->student = Student::find($this->studentId);

            if ($this->student) {
                $this->student->update([
                    'status' => 'resign',
                    'inactive_at' => $this->date
                ]);
            }
        }
        
        $this->showModal = false;
        $this->dispatch('saved', message: $this->message);
    }

    #[On('delete-resignation')]
    public function delete($id)
    {
        $this->resignation = StudentResignation::find($id);
        if ($this->resignation->student->warning){
            $warning = Warning::where('student_id', $this->resignation->student_id)->first();
            if ($warning)
            {
                $warning->delete();
            }
        }

        if ($this->resignation->student){
            $student = Student::find($this->resignation->student_id);
            $student->update([
                'status' => 'active',
                'inactive_at' => null
            ]);
        }
        $this->resignation->delete();

        $this->student = Student::find($this->studentId);

        if ($this->student) {
            $this->student->update([
                'status' => 'active',
                'inactive_at' => null
            ]);
        }

        $this->dispatch('deleted', message: 'Data berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.resignations.form');
    }
}
