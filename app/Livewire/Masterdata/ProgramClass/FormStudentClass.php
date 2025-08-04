<?php

namespace App\Livewire\Masterdata\ProgramClass;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\StudentClass;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class FormStudentClass extends Component
{
    public $manualInput = false;
    public $generateInput = true;
    public $createType = 0;
    public $formTitle;
    public $message;

    public $showModal = false;
    public $isEdit = false;

    public $program;
    public $programId;

    public $entryYearSelect;
    public $totalClasses;

    public $studentClass;
    public $studentClassId;
    public $academicAdvisor;
    public $academicAdvisorId;
    public $academicAdvisorDecree;
    public $className;
    public $entryYear;
    public $status;
    

    protected $listeners = [ 'edit-student-class' => 'edit'];

    public function rules()
    {
        return [
            'academicAdvisorId' => 'nullable',
            'academicAdvisorDecree' => 'nullable',
            'className' => 'nullable',
            'entryYear' => 'nullable',
            'totalClasses' => 'nullable',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'studentClassId',
            'studentClass',
            'programId',
            'academicAdvisorId',
            'academicAdvisorDecree',
            'className',
            'entryYear',
            'status',
            'totalClasses'
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function loadAvailableAcademicAdvisor()
    {
        $this->academicAdvisor = Lecturer::whereHas('user.roles', function ($query) {
            $query->where('name', 'dosenWali');
        })->get();
    }
    
    // public function loadAvailableAcademicAdvisor()
    // {
    //     $this->academicAdvisor = Lecturer::whereHas('user.roles', function ($query) {
    //         $query->where('name', 'dosenWali');
    //     })->where(function ($query) {
    //         $query->whereDoesntHave('student_class')
    //               ->orWhereHas('student_class', function ($query) {
    //                   $query->where('id', $this->studentClassId);
    //               });
    //     })->get();
    // }

    public function mount()
    {
        // if ($this->createType === null) {
        //     $this->createType = 1;
        // }
        $this->loadAvailableAcademicAdvisor();
    }

    #[On('create-student-class')]
    public function create($programId)
    {
        $this->program = Program::find($programId);
        $this->programId = $this->program->id;
        $this->entryYearSelect = now()->year;
        $this->totalClasses = 1;

        $this->loadAvailableAcademicAdvisor();
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
        $this->formTitle = 'Buat Kelas';
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetForm();

        $this->createType = 0; // edit berarti manual

        $this->studentClass = StudentClass::find($id);
        $this->program = Program::find($this->studentClass->program_id);
        $this->programId = $this->program->id;
        // dd($this->studentClass);
        $this->studentClassId = $this->studentClass->id;
        $this->className = $this->studentClass->class_name;
        $this->academicAdvisorId = $this->studentClass->academic_advisor_id;
        $this->academicAdvisorDecree = $this->studentClass->academic_advisor_decree;
        $this->entryYear = $this->studentClass->entry_year;
        $this->status = $this->studentClass->status;

        $this->loadAvailableAcademicAdvisor();

        $this->showModal = true;
        $this->isEdit = true;
        $this->formTitle = 'Ubah Kelas';
    }

    public function generateProgramCode($programName)
    {
        $words = explode(' ', $programName);
        $code = '';
        foreach ($words as $word) {
            $code .= strtoupper(substr($word, 0, 1));
        }
        return $code;
    }

    public function save()
    {   
        $validated = $this->validate();
        // dd($this->academicAdvisorId);

        $program = $this->program;

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $yearDiff = $currentYear - $this->entryYear;
        if ($currentMonth >= 8) {
            $yearDiff += 1;
        }
        
        if ($yearDiff <= 0) {
            $this->addError('entryYear', 'Tahun akademik tidak valid.');
            return;
        }
        
        $status = 'active';
        $graduated_at = null;
        
        if ($program->degree === 'D3' && $yearDiff > 3) {
            $status = 'graduated';
            $graduated_at = now();
            $yearDiff = 3;
        } elseif ($program->degree === 'D4' && $yearDiff > 4) {
            $status = 'graduated';
            $graduated_at = now();
            $yearDiff = 4;
        }
        // dd($validated);
        if ($this->isEdit) {
            $this->studentClass->update([
                'class_name' => $this->className,
                'academic_advisor_id' => $this->academicAdvisorId !== '' ? $this->academicAdvisorId : null,
                'academic_advisor_decree' => $this->academicAdvisorDecree,
                'entry_year' => $this->entryYear,
                'status' => $status,
            ]);

            // dd($this->studentClass->lecturer->user->name);
            $this->message = 'Kelas berhasil diperbarui!';
        } else {
            $program_code = $this->generateProgramCode($program->program_name);

                
            $existingClass = StudentClass::where('program_id', $program->id)
            ->where('entry_year', $this->entryYear)
            ->latest()->first();
            
            $lastClassLetter = 'A';
            if ($existingClass) {
                $lastLetter = substr($existingClass->class_name, -1);
                $lastClassLetter = chr(ord($lastLetter) + 1);
            }

            if ($this->createType == 0) {
                $classLetter = chr(ord($lastClassLetter));
                $className = "{$program_code}-{$yearDiff}{$classLetter}";

                StudentClass::create([
                    'program_id' => $this->program->id,
                    'class_name' => $className,
                    'academic_advisor_id' => $this->academicAdvisorId,
                    'academic_advisor_decree' => $this->academicAdvisorDecree,
                    'entry_year' => $this->entryYear,
                    'status' => $status,
                    'graduated_at' => $graduated_at,
                ]);
            } else { //generate input          
                for ($i = 0; $i < $this->totalClasses; $i++) {
                    $classLetter = chr(ord($lastClassLetter) + $i);
                    $className = "{$program_code}-{$yearDiff}{$classLetter}";

                    $lastStudentCreated = StudentClass::create([
                        'program_id' => $program->id,
                        'class_name' => $className,
                        'academic_advisor_id' => $this->academicAdvisorId,
                        'academic_advisor_decree' => $this->academicAdvisorDecree,
                        'entry_year' => $this->entryYear,
                        'status' => $status,
                        'graduated_at' => $graduated_at,
                    ]);
                }
                $this->studentClass = $lastStudentCreated;
            }

            $this->message = 'Data kelas berhasil dibuat.';
        }

        $this->dispatch('saved', message: $this->message);
        $this->resetForm();
        $this->showModal = false;
    }

    #[On('delete-student-class')]
    public function delete($id)
    {
        $this->studentClass = StudentClass::find($id);
        $this->studentClass->delete();
        // $this->dispatch('student-class-deleted', studentClass: $this->studentClass->id);
        $this->resetForm();
        $this->showModal = false;

        $this->message = 'Kelas berhasil dihapus.';

        $this->dispatch('saved', $this->message);
    }

    public function render()
    {
        return view('livewire.masterdata.program-class.form-student-class');
    }
}
