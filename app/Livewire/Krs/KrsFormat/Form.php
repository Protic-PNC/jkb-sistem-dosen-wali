<?php

namespace App\Livewire\Krs\KrsFormat;

use App\Models\KrsFormat;
use App\Models\Program;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $isEdit = false;
    public $formTitle;
    public $message;

    public $krsFormat;
    public $krsFormatId;
    
    public $programs;
    public $programId;
    public $semester;
    public $academicYear;
    public $file;
    public $existingFile;

    public function rules()
    {
        return [
            'programId' => 'required',
            'semester' => 'required',
            'academicYear' => 'required',
            'file' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'programId',
            'semester',
            'academicYear',
            'file',
        ]);
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function mount()
    {
        $this->programs = Program::all();
    }

    #[On('create-format')]
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
        $this->formTitle = 'Tambah Format KRS';
    }

    #[On('edit-format')]
    public function edit($id)
    {
        $this->resetForm();
        $this->formTitle = 'Edit Format KRS';
        $this->krsFormatId = $id;
        $this->krsFormat = KrsFormat::find($id);
        $this->programId = $this->krsFormat->program_id;
        $this->semester = $this->krsFormat->semester;
        $this->academicYear = $this->krsFormat->academic_year;
        $this->file = $this->krsFormat->file;
        $this->existingFile = $this->krsFormat->file;
        $this->showModal = true;
        $this->isEdit = true;
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
        $this->validate();

        $filePath = $this->file;
        if ($this->file instanceof UploadedFile) {
            // Hapus file lama jika ada
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Simpan file baru
            $filePath = $this->file->store('krs_format', 'public');

            // ✅ Tambahkan ini supaya langsung update preview-nya
            $this->file = $filePath;
        }

        if ($this->isEdit) {
            $this->krsFormat = KrsFormat::find($this->krsFormatId);

            $this->krsFormat->update([
                'program_id' => $this->programId,
                'semester' => $this->semester,
                'academic_year' => $this->academicYear,
                'file' => $this->file,
            ]);

            $this->message = 'Format KRS berhasil diubah.';

        } else {
            $this->krsFormat = KrsFormat::create([
                'program_id' => $this->programId,
                'semester' => $this->semester,
                'academic_year' => $this->academicYear,
                'file' => $this->file,
            ]);

            $this->message = 'Format KRS berhasil ditambahkan.';

        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('savedTb1', message: $this->message);
    }


    #[On('delete-format')]
    public function delete($id)
    {
        $this->krsFormat = KrsFormat::find($id);
        $filePath = $this->krsFormat->file;

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $this->krsFormat->delete();
        $this->dispatch('deletedTb1', message: 'Format KRS berhasil dihapus.');
    }
    

    public function render()
    {
        return view('livewire.krs.krs-format.form');
    }
}
