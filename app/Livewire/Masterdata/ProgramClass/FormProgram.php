<?php

namespace App\Livewire\Masterdata\ProgramClass;

use App\Models\Lecturer;
use App\Models\Program;
use Livewire\Component;

class FormProgram extends Component
{
    public $showModal = false;
    public $isEdit = false;
    
    public $program;
    public $programId;
    public $programName;
    public $degree;
    public $headOfProgram;
    public $headOfProgramId;
    public $message;

    public $formTitle = 'Tambah Prodi';

    protected $listeners = ['create-program' => 'create', 'edit-program' => 'edit', 'delete-program' => 'delete'];

    public function rules()
    {
        return [
            'programName' => 'required|unique:programs,program_name,'. $this->programId .',id',
            'degree' => 'required',
            'headOfProgramId' => 'nullable'
        ];
    }


    public function resetForm()
    {
        $this->reset([
            'programId',
            'programName',
            'degree',
            'headOfProgramId'
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function loadAvailableHeadOfProgram()
    {
        $this->headOfProgram = Lecturer::whereHas('user.roles', function ($query) {
            $query->where('name', 'kaprodi');
        })->where(function ($query) {
            $query->whereDoesntHave('program')
                ->orWhereHas('program', function ($subQuery) {
                    $subQuery->where('id', $this->programId);
                });
        })->get();
    }


    public function mount()
    {
        $this->loadAvailableHeadOfProgram();
    }
    
    public function create()
    {
        $this->resetForm();
        $this->loadAvailableHeadOfProgram();
        $this->showModal = true;
        $this->isEdit = false;
        $this->formTitle = 'Tambah Prodi';
    }
    
    public function edit($id)
    {
        $this->formTitle = 'Edit Prodi';
        $this->program = Program::with('head_of_program')->find($id);

        $this->programId = $this->program->id;
        $this->programName = $this->program->program_name;
        $this->degree = $this->program->degree;
        $this->headOfProgramId = $this->program->head_of_program_id;

        $this->loadAvailableHeadOfProgram();

        $this->showModal = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $validated = $this->validate();
        // dd($this->headOfProgramId);

        if ($this->isEdit) {
            $this->program = Program::find($this->programId);
            $this->program->update([
                'program_name' => $this->programName,
                'degree' => $this->degree,
                'head_of_program_id' => $this->headOfProgramId
            ]);

            $this->message = 'Program Studi berhasil diperbarui.';
          
        } else {
            $this->program = Program::create([
                'program_name' => $this->programName,
                'degree' => $this->degree,
                'head_of_program_id' => $this->headOfProgramId
            ]);

            $this->message = 'Program Studi berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('saved', message: $this->message);
    }

    public function delete($id)
    {
        $this->program = Program::find($id);
        $this->program->delete();
        $this->message = 'Program Studi berhasil dihapus.';

        $this->dispatch('saved', message: $this->message);
    }

    public function render()
    {
        return view('livewire.masterdata.program-class.form-program');
    }
}
