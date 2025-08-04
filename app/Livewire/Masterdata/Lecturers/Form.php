<?php

namespace App\Livewire\Masterdata\Lecturers;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Form extends Component
{
    public $showModal = false;
    public $isEdit = false;
    public $message;
    public $formTitle;
    
    //data dosen
    public $lecturer;
    public $lecturerId;
    public $nidn;
    public $nip;
    public $lecturerPhoneNumber;
    public $lecturerAddress;
    public $lecturerSignature;

    //data user
    public $user;
    public $userName;
    public $userEmail;
    public $userPassword;
    public $role;

    protected $listeners = ['create-lecturer' => 'create', 'edit-lecturer' => 'edit', 'delete-lecturer' => 'delete'];

    public function rules()
    {
        return [
            'userName' => 'required|string',
            'userEmail' => 'required|email',
            'userPassword' => 'nullable|min:8',
            'nidn' => 'required|size:10|unique:lecturers,nidn, '. $this->lecturerId . ',id',
            'nip' => 'required|size:18',
            'lecturerPhoneNumber' => 'required|string',
            'lecturerAddress' => 'nullable|string',
            'lecturerSignature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'lecturerId',
            'userName', 'userEmail', 'userPassword',
            'nidn', 'nip', 'lecturerPhoneNumber', 'lecturerAddress', 'lecturerSignature', 'role'
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }


    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->formTitle = 'Tambah Dosen';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->formTitle = 'Edit Dosen';
        $this->lecturer = Lecturer::with('user')->find($id);
        
        $this->lecturerId = $this->lecturer->id;
        $this->nidn = $this->lecturer->nidn;
        $this->nip = $this->lecturer->nip;
        $this->lecturerPhoneNumber = $this->lecturer->lecturer_phone_number;
        $this->lecturerAddress = $this->lecturer->lecturer_address;

        $this->userName = $this->lecturer->user->name;
        $this->userEmail = $this->lecturer->user->email;
        $this->userPassword = '';
        
        $this->role = $this->lecturer->user->roles->first()->name;

        $this->showModal = true;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        $this->lecturer = Lecturer::find($id);
        $this->lecturer->delete();
        $message = 'Dosen berhasil dihapus.';

        $this->dispatch('saved', message: $message);
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $this->lecturer = Lecturer::find($this->lecturerId);
            $this->lecturer->update([
                'nidn' => $this->nidn,
                'nip' => $this->nip,
                'lecturer_phone_number' => $this->lecturerPhoneNumber,
                'lecturer_address' => $this->lecturerAddress,
            ]);

            $this->user = $this->lecturer->user;
            $this->user->update([
                'name' => $this->userName,
                'email' => $this->userEmail,
            ]);
            $this->message = 'Dosen berhasil diperbarui.';

        } else {
            $this->user = User::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'password' => Hash::make($this->userPassword ?? 'password123'),
            ]);

            $this->user->assignRole($this->role);
            
            $this->lecturer = Lecturer::create([
                'user_id' => $this->user->id,
                'nidn' => $this->nidn,
                'nip' => $this->nip,
                'lecturer_phone_number' => $this->lecturerPhoneNumber,
                'lecturer_address' => $this->lecturerAddress,
            ]);
            $this->message = 'Dosen berhasil dibuat.';

        }

        $this->dispatch('saved', message: $this->message);
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.masterdata.lecturers.form');
    }
}
