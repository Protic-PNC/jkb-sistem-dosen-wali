<?php

namespace App\Livewire\Guidances;

use App\Models\Guidance;
use App\Models\Student;
use App\Models\StudentClass;
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
    public $currentSemester;

    public $guidance;
    public $guidanceId;
    public $problem;
    public $solution;
    public $problemDate;
    public $solutionDate;
    public $createdBy;
    public $isValidated;
    public $validationNote;
    
    public $dateStart;
    public $dateEnd;

    public $role;

    public $formTitle = 'Tambah Data';

    public function rules()
    {
        $rules = [
            'studentId' => 'required',
            'problem' => 'required',
            'solution' => 'nullable',
            'problemDate' => 'required',
            'solutionDate' => 'nullable',
        ];

        if ($this->role == 'mahasiswa' && $this->isEdit && $this->createdBy !== Auth::id()) {
            $rules['isValidated'] = 'required|in:1,0';
            if ($this->isValidated === '0') {
                $rules['validationNote'] = 'required|string';
            }
        }

        return $rules;
    }

    public function resetForm()
    {
        $this->reset([
            'studentId',
            'problem',
            'solution',
            'problemDate',
            'solutionDate',
            'isValidated',
            'validationNote',
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
        $user = Auth::user();
        $this->role = $user->roles->first()->name;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;

        if ($this->role == 'dosenWali') {
            if ($showReport) {
                $this->studentClass = collect([StudentClass::where('id', $this->studentClassId)->first()]);
                // $this->studentClassId = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->first()->id;
                $this->updatedStudentClassId($this->studentClassId);
            } else {
                $this->studentClass = StudentClass::where('academic_advisor_id', Auth::user()->lecturer->id)->where('status', 'active')->get();
            }
        }

        if ($this->role == 'mahasiswa') {
            $this->students = collect([Student::find(Auth::user()->student->id)]);
            $studentClass = StudentClass::find($this->students->first()->student_class->id);
            $entryYear = $studentClass->entry_year;
            $currentSemester = $studentClass->current_semester;

            // ===== Menentukan dateStart (Awal Semester 1 - Agustus) =====
            $this->dateStart = Carbon::create($entryYear, 8, 1)->startOfMonth(); // 1 Agustus tahun masuk

            // ===== Menentukan dateEnd (Akhir Semester Sekarang) =====
            $semesterCount = $currentSemester;

            // Hitung tahun tambahan berdasarkan semester
            // Tiap 2 semester = 1 tahun akademik
            $additionalYears = intdiv($semesterCount, 2);

            // Jika semester genap (berarti terakhir adalah semester genap: Juli)
            if ($semesterCount % 2 == 0) {
                $endMonth = 7; // Juli
                $endYear = $entryYear + $additionalYears;
            } else {
                // Semester ganjil (berakhir di Januari)
                $endMonth = 1; // Januari
                $endYear = $entryYear + $additionalYears + 1;
            }

            $dateEnd = Carbon::create($endYear, $endMonth, 1)->endOfMonth();
            $this->dateEnd = Carbon::create($endYear, $endMonth, 1)->endOfMonth();
        }
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
                $semesterCount = $currentSemester;

                // Hitung tahun tambahan berdasarkan semester
                // Tiap 2 semester = 1 tahun akademik
                $additionalYears = intdiv($semesterCount, 2);

                // Jika semester genap (berarti terakhir adalah semester genap: Juli)
                if ($semesterCount % 2 == 0) {
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

    #[On('create-guidance')]
    public function create()
    {
        $this->resetForm();
        $this->formTitle = 'Tambah Data';
        $this->isEdit = false;
        $this->showModal = true;

        if ($this->role == 'mahasiswa') {
            $this->studentId = Auth::user()->student->id;
        }
    }

    #[On('edit-guidance')]
    public function edit($id)
    {
        $this->resetForm();
        $this->guidanceId = $id;
        $this->guidance = Guidance::find($id);
        $this->studentClassId = $this->guidance->student->student_class_id;
        if ($this->role == 'dosenWali') {
            $this->updatedStudentClassId($this->studentClassId);
        }
        $this->studentId = $this->guidance->student_id;
        $this->problem = $this->guidance->problem;
        $this->solution = $this->guidance->solution;
        $this->problemDate = $this->guidance->problem_date;
        $this->solutionDate = $this->guidance->solution_date;
        $this->createdBy = $this->guidance->created_by;
        $this->isValidated = $this->guidance->is_validated;
        $this->validationNote = $this->guidance->validation_note;

        $this->formTitle = 'Edit Data';
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        // dd($validated);
        $this->student = Student::find($this->studentId);

        if ($this->isEdit) {
            $updateData = [
                'student_id' => $this->studentId,
                'problem' => $this->problem,
                'solution' => $this->solution,
                'problem_date' => $this->problemDate,
                'solution_date' => $this->solutionDate,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ];

            if ($this->role == 'mahasiswa' && $this->createdBy !== Auth::user()->id) {
                $updateData['is_validated'] = $this->isValidated;
                $updateData['validation_note'] = $this->validationNote;
            }

            // Jika dosen wali mengedit data yang sebelumnya ditolak
            if ($this->role == 'dosenWali' && $this->guidance->is_validated === 0) {
                $updateData['is_validated'] = null;
                $updateData['validation_note'] = null;
            }

            // dd($updateData);

            $this->guidance->update($updateData);
            
            $this->message = 'Data berhasil diubah.';
            
        } else {
            $createData = [
                'student_id' => $this->studentId,
                'problem' => $this->problem,
                'solution' => $this->solution,
                'problem_date' => $this->problemDate,
                'solution_date' => $this->solutionDate ?? null,
                'created_by' => Auth::user()->id,
                'class_name' => $this->student->student_class->class_name,
                'entry_year' => $this->student->student_class->entry_year,
            ];

            // Jika mahasiswa yang mengisi, maka otomatis valid
            if ($this->role === 'mahasiswa') {
                $createData['is_validated'] = 1;
            }

            Guidance::create($createData);


            $this->message = 'Data berhasil ditambahkan.';

        }
        
        $this->dispatch('saved', message: $this->message);
        $this->resetForm();
        $this->showModal = false;
    }

    #[On('delete-guidance')]
    public function delete($id)
    {
        $this->guidanceId = $id;
        $this->guidance = Guidance::find($id);
        $this->guidance->delete();
        $this->message = 'Data berhasil dihapus.';

        $this->dispatch('deleted', messaga: $this->message);
    }

    public function render()
    {
        return view('livewire.guidances.form');
    }
}
