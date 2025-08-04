<?php

namespace App\Livewire\Masterdata\Users;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $formTitle = 'Tambah Pengguna';
    public $message;
    public $showModal = false;
    public $isEdit = false;

    public $user;
    public $userId;
    public $userName;
    public $userEmail;
    public $password;
    public $role;

    public $student;
    public $studentId;
    public $studentClassId;
    public $studentClassName;
    public $nim;
    public $status;
    
    public $studentClasses;

    public $lecturer;
    public $lecturerId;
    public $nidn;
    public $nip;
    
    public $phoneNumber;
    public $address;
    public $signature = null;

    public function mount()
    {
        $this->studentClasses = StudentClass::all();
    }

    public function rules()
    {
        return [
            // Rules umum untuk user
            'userName' => ['required', 'string', 'min:4'],
            'userEmail' => ['required'],
            // 'password' => $this->isEdit ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'],
            'role' => ['nullable', 'in:admin,mahasiswa,dosenWali,kaprodi,jurusan'],
            'phoneNumber' => ['required', 'string', 'min:10'],
            'address' => ['required', 'string'],
            'signature' => ['nullable', 'string'],

            // Hanya validasi jika role adalah mahasiswa
            'nim' => [
                Rule::requiredIf(function () {
                    return $this->role === 'mahasiswa';
                }),
                'string',
                'min:5',
            ],
            'studentClassId' => [
                Rule::requiredIf(function () {
                    return $this->role === 'mahasiswa';
                }),
            ],

            // Hanya validasi jika role adalah dosenWali, kaprodi, atau jurusan
            'nidn' => [
                Rule::requiredIf(function () {
                    return in_array($this->role, ['dosenWali', 'kaprodi', 'jurusan']);
                }),
                'string',
                'min:4',
            ],
            'nip' => [
                Rule::requiredIf(function () {
                    return in_array($this->role, ['dosenWali', 'kaprodi', 'jurusan']);
                }),
                'string',
                'min:4',
            ],
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'userName', 'userEmail', 'password','role',
            'phoneNumber', 'address', 'signature',
            'nim', 'status', 'studentClassId',
            'nidn', 'nip'            
        ]);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }
    
    #[On('create-user')]
    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
        $this->formTitle = 'Tambah Pengguna';
        $this->role = 'admin';
    }

    #[On('edit-user')]
    public function edit($id)
    {
        $this->resetForm();

        $this->formTitle = 'Edit Pengguna';
        $this->isEdit = true;

        $this->user = User::find($id);

        $this->userId = $this->user->id;
        $this->userName = $this->user->name;
        $this->userEmail = $this->user->email;
        $this->role = $this->user->roles->first()->name;

        if ($this->role == 'mahasiswa') {

            $this->student = Student::find($this->user->student->id);
            $this->studentId = $this->student->id;
            $this->studentClassId = $this->student->student_class_id;
            $this->nim = $this->student->nim;
            $this->phoneNumber = $this->student->student_phone_number;
            $this->address = $this->student->student_address;

        } else if ($this->role == 'dosenWali' || $this->role == 'kaprodi' || $this->role == 'jurusan') {

            $this->lecturer = Lecturer::find($this->user->lecturer->id);
            $this->lecturerId = $this->lecturer->id;
            $this->nidn = $this->lecturer->nidn;
            $this->nip = $this->lecturer->nip;
            $this->phoneNumber = $this->lecturer->lecturer_phone_number;
            $this->address = $this->lecturer->lecturer_address;
            $this->signature = $this->lecturer->lecturer_signature;

        }

        $this->showModal = true;
    }

    public function save()
    {
        // dd($this->studentClassId);

        if (!empty($this->signature) && $this->signature instanceof \Illuminate\Http\UploadedFile) {
            if ($this->isEdit) {
                $userOld = User::find($this->userId);
                $oldSignature = $userOld->lecturer->lecturer_signature ?? null;

                if ($oldSignature && Storage::disk('public')->exists($oldSignature)) {
                    Storage::disk('public')->delete($oldSignature);
                }
            }

            // Simpan file signature
            $storedPath = $this->signature->store('signatures', 'public');

            // Baru simpan path-nya ke signature property
            $this->signature = $storedPath;
        }

        if ($this->isEdit) {

            $this->validate([
                'userName' => 'required',
                'userEmail' => 'required',
                'role' => 'required',
            ], [
                'userName.required' => 'Nama harus diisi',
                'userEmail.required' => 'Email harus diisi',
                'role.required' => 'Role harus diisi',
            ]);

            $this->user = User::find($this->userId);
            $this->user->update([
                'name' => $this->userName,
                'email' => $this->userEmail
            ]);

            if ($this->role != 'admin') {
                if ($this->role == 'mahasiswa') {

                    $this->student = Student::find($this->studentId);
                    $this->validate([
                        'studentClassId' => 'required',
                        'nim' => 'required|unique:students,nim,'. $this->student->id . ',id',
                        'phoneNumber' => 'required',
                        'address' => 'required'
                    ], [
                        'studentClassId.required' => 'Kelas harus dipilih',
                        'nim.required' => 'NIM harus diisi',
                        'nim.unique' => 'NIM sudah ada',
                        'phoneNumber.required' => 'Nomor HP harus diisi',
                        'address.required' => 'Alamat harus diisi',
                    ]);

                    $this->student->update([
                        'student_class_id' => $this->studentClassId,
                        'nim' => $this->nim,
                        'student_phone_number' => $this->phoneNumber,
                        'student_address' => $this->address
                    ]);

                } else {

                    $this->lecturer = Lecturer::find($this->lecturerId);

                    $this->validate([
                        'nidn' => 'required|unique:lecturers,nidn,'. $this->lecturer->id . ',id',
                        'nip' => 'required|unique:lecturers,nip,'. $this->lecturer->id . ',id',
                        'phoneNumber' => 'required',
                        'address' => 'required',
                        'signature' => 'nullable'
                    ], [
                        'nidn.required' => 'NIDN harus dipilih',
                        'nidn.unique' => 'NIDN sudah ada',
                        'nip.required' => 'NIP harus diisi',
                        'nip.unique' => 'NIP sudah ada',
                        'phoneNumber.required' => 'Nomor HP harus diisi',
                        'address.required' => 'Alamat harus diisi',
                    ]);
                    $this->lecturer->update([
                        'nidn' => $this->nidn,
                        'nip' => $this->nip,
                        'lecturer_phone_number' => $this->phoneNumber,
                        'lecturer_address' => $this->address,
                        'lecturer_signature' => $this->signature,
                    ]);
                    
                }
            }

            $this->message = 'Pengguna berhasil diperbarui.';

        } else {

            $this->validate([
                'userName' => 'required',
                'userEmail' => 'required',
                'role' => 'required',
            ], [
                'userName.required' => 'Nama harus diisi',
                'userEmail.required' => 'Email harus diisi',
                'role.required' => 'Role harus diisi',
            ]);

            $this->user = User::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'password' => Hash::make('12345678'),
            ]);

            $this->user->assignRole($this->role);

            if ($this->role != 'admin') {

                if ($this->role == 'mahasiswa') {

                    $this->validate([
                        'studentClassId' => 'required',
                        'nim' => 'required|unique:students,nim',
                        'phoneNumber' => 'required',
                        'address' => 'required'
                    ], [
                        'studentClassId.required' => 'Kelas harus dipilih',
                        'nim.required' => 'NIM harus diisi',
                        'nim.unique' => 'NIM sudah ada',
                        'phoneNumber.required' => 'Nomor HP harus diisi',
                        'address.required' => 'Alamat harus diisi',
                    ]);

                    $this->student = Student::create([
                        'user_id' => $this->user->id,
                        'student_class_id' => $this->studentClassId,
                        'nim' => $this->nim,
                        'student_phone_number' => $this->phoneNumber,
                        'student_address' => $this->address,
                    ]);

                } else {

                    $this->validate([
                        'nidn' => 'required|unique:lecturers,nidn',
                        'nip' => 'required|unique:lecturers,nip',
                        'phoneNumber' => 'required',
                        'address' => 'required'
                    ], [
                        'nidn.required' => 'NIDN harus dipilih',
                        'nidn.unique' => 'NIDN sudah ada',
                        'nip.required' => 'NIP harus diisi',
                        'nip.unique' => 'NIP sudah ada',
                        'phoneNumber.required' => 'Nomor HP harus diisi',
                        'address.required' => 'Alamat harus diisi',
                    ]);

                    $this->lecturer = Lecturer::create([
                        'user_id' => $this->user->id,
                        'nidn' => $this->nidn,
                        'nip' => $this->nip,
                        'lecturer_phone_number' => $this->phoneNumber,
                        'lecturer_address' => $this->address,
                        'lecturer_signature' => $this->signature
                    ]);
                }

            }

            $this->message = 'Pengguna berhasil dibuat.';

        }

        $this->showModal = false;
        $this->dispatch('saved', message: $this->message);
    }

    #[On('delete-user')]
    public function delete($id)
    {
        $this->user = User::find($id);
        $this->role = $this->user->roles->first()->name;

        if ($this->role != 'admin') {
            if ($this->role == 'mahasiswa') {

                $this->student = $this->user->student;
                if ($this->student) {
                    $this->student->delete();
                }

            } else {

                $this->lecturer = $this->user->lecturer;
                if ($this->lecturer) {
                    $this->lecturer->delete();
                }

            }
        }
        
        $this->user->delete();
        $this->message = 'Pengguna berhasil dihapus.';

        $this->dispatch('saved', message: $this->message);
    }

    public function render()
    {
        return view('livewire.masterdata.users.form');
    }
}
