<?php

namespace App\Livewire\Krs;

use App\Models\Krs;
use App\Models\KrsFormat;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
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

    public $krsFormatId;
    public $krsFormat;
    
    public $krs;
    public $krsId;
    public $student;
    public $studentId;
    public $file;    

    public function rules()
    {
        return [
            'file' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'file'
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
        $this->student = Auth::user()->student;
        if ($this->student) {
            $this->studentId = $this->student->id;
        }

        $this->krsFormat = KrsFormat::find($this->krsFormatId);
    }

    #[On('create-krs')]
    public function create($krsFormatId)
    {
        $this->krsFormat = KrsFormat::find($krsFormatId);
        $this->krsFormatId = $krsFormatId;

        $this->resetForm();
        $this->formTitle = 'Upload KRS';
        $this->isEdit = false;
        $this->showModal = true;
    }

    #[On('edit-krs')]
    public function edit($id)
    {
        $this->resetForm();
        $this->formTitle = 'Edit KRS';

        $this->krsId = $id;
        $this->krs = Krs::find($id);
        $this->file = $this->krs->file;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Simpan file baru (jika ada)
        if ($this->file instanceof UploadedFile) {
            // Kalau sedang edit, hapus file lama
            if ($this->isEdit) {
                $krsLama = Krs::find($this->krsId);

                if ($krsLama && $krsLama->file && Storage::disk('public')->exists($krsLama->file)) {
                    Storage::disk('public')->delete($krsLama->file);
                }
            }

            // Upload file baru
            $filePath = $this->file->store('krs', 'public');
        } else {
            // Jika tidak mengupload file baru, gunakan file lama (untuk mode edit)
            $filePath = $this->file;
        }

        if ($this->isEdit) {
            $this->krs = Krs::find($this->krsId);

            $this->krs->update([
                'student_id' => $this->studentId,
                'krs_format_id' => $this->krsFormatId,
                'file' => $filePath,
            ]);

            $this->message = 'KRS berhasil diubah';
        } else {
            $this->krs = Krs::create([
                'student_id' => $this->studentId,
                'krs_format_id' => $this->krsFormatId,
                'file' => $filePath,
            ]);

            $this->message = 'KRS berhasil ditambahkan';
        }

        $this->showModal = false;
        $this->resetForm();

        $this->dispatch('savedTb2', message: $this->message);

        // $this->dispatch('formatSelected', [
        //     'krsFormatId' => $this->krsFormat->id, 
        //     'semester' => $this->krsFormat->semester, 
        //     'academicYear' => $this->krsFormat->academic_year
        // ]);
    }


    #[On('delete-krs')]
    public function delete($id)
    {
        $this->krs = Krs::find($id);
        $this->krsFormat = KrsFormat::find($this->krs->krs_format_id);

        if (!$this->krs) {
            $this->message = 'Data KRS tidak ditemukan.';
            $this->dispatch('error', message: $this->message);
            return;
        }

        $filePath = $this->krs->file;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $this->krs->delete();
        $this->message = 'KRS berhasil dihapus.';

        // Pastikan $this->krsFormat tidak null juga
        if ($this->krsFormat) {
            $this->dispatch('formatSelected', [
                'krsFormatId' => $this->krsFormat->id,
                'semester' => $this->krsFormat->semester,
                'academicYear' => $this->krsFormat->academic_year,
            ]);
        }

        $this->dispatch('savedTb2', message: $this->message);
    }


    public function render()
    {
        return view('livewire.krs.form');
    }
}
