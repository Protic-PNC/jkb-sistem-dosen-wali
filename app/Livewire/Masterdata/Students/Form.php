<?php

namespace App\Livewire\Masterdata\Students;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    
    public $showModal = false;
    public $isEdit = false;

    //data user(relasi)
    public $studentId;
    public $userName;
    public $userEmail;
    // public $userPassword;

    //data mahasiswa
    public $student;
    public $studentClass = [];
    public $studentClassId = null;
    public $studentClassName = null;
    public $studentPhoneNumber;
    public $nim;
    public $studentAddress;
    public $status;
    public $studentSignature;

    // protected $listeners = ['create-student' => 'create', 'edit-student' => 'edit', 'delete-student' => 'delete'];

    public function rules()
    {
        return [
            'userName' => 'required|string',
            'userEmail' => 'required|email',
            'studentClassId' => 'nullable|integer|exists:student_classes,id',
            'studentPhoneNumber' => 'required|string',
            'nim' => 'required|size:9|unique:students,nim,' . $this->studentId . ',id',
            'studentAddress' => 'nullable|string',
            'status' => 'required|in:active,graduated,dropout,resign,academic_leave',
            'studentSignature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', //maksimal 2MB
        ];
    }

    #[On('create-student')]
    public function create()
    {
        // dd('create clicked!');
        $this->studentClass = StudentClass::all();
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    #[On('edit-student')]
    public function edit($id)
    {
        // dd('edit clicked!');
        $this->student = Student::with('user', 'student_class')->find($id);
        $this->studentClass = StudentClass::where('status', 'active')->get();
        // dd($this->student->student_class_id);

        $this->studentId = $this->student->id;
        $this->studentClassId = $this->student->student_class_id;
        $this->studentClassName = $this->student->class_name;
        $this->studentPhoneNumber = $this->student->student_phone_number;
        $this->nim = $this->student->nim;
        $this->studentAddress = $this->student->student_address;
        $this->studentSignature = $this->student->student_signature;
        $this->status = $this->student->status;

        $this->userName = $this->student->user->name;
        $this->userEmail = $this->student->user->email;

        // $this->userPassword = '';

        $this->showModal = true;
        $this->isEdit = true;        
    }


    public function save()
    {
        $validated = $this->validate();
        // dd($validated);

        if ($this->isEdit) {

            if ($this->status == 'academic_leave' ) {
                $updateStudent = [
                    'student_class_id' => null,
                    'student_phone_number' => $this->studentPhoneNumber,
                    'nim' => $this->nim,
                    'student_address' => $this->studentAddress,
                    'status' => $this->status,
                ];
            } else {
                $updateStudent = [
                    'student_class_id' => $this->studentClassId,
                    'student_phone_number' => $this->studentPhoneNumber,
                    'nim' => $this->nim,
                    'student_address' => $this->studentAddress,
                    'status' => $this->status,
                ];
            }

            // dd($updateStudent);

            $student = Student::find($this->studentId);
            
            if (!$student->student_class_id) {
                $studentClass = StudentClass::find($this->studentClassId);
                $updateStudent['active_at_semester'] = $studentClass->current_semester;
            }
            $student->update($updateStudent);

            $user = $student->user;
            $user->update([
                'name' => $this->userName,
                'email' => $this->userEmail,
            ]);
            $message = 'Mahasiswa berhasil diperbarui.';
        } else {
            $user = User::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'password' => Hash::make('12345678'),
                // 'role' => 'student',
            ]);
            $user->assignRole('mahasiswa');

            $student = Student::create([
                'user_id' => $user->id,
                'student_class_id' => $this->studentClassId,
                'student_phone_number' => $this->studentPhoneNumber,
                'nim' => $this->nim,
                'student_address' => $this->studentAddress,
                'status' => $this->status,
                'active_at_semester' => 1,
            ]);
            $message = 'Mahasiswa berhasil dibuat.';
        }

        $this->showModal = false;
        $this->dispatch('saved', message: $message);
        // $this->dispatch('student-saved', student: $student->id, message: $message)->to('students.index');
    }

    public function resetForm()
    {
        $this->reset([
            'studentId',
            'userName', 'userEmail',
            'studentClassId', 'studentPhoneNumber', 'nim', 'studentAddress', 'status', 'studentSignature'
        ]);
    }

    #[On('delete-student')]
    public function delete($id)
    {
        $student = Student::find($id);
        
        if ($student) {
            if ($student->user) {
                $student->user->delete();
            } 
            
            $student->delete();
        }
        $message = 'Student succesfully deleted!';
        $this->dispatch('saved', message: $message);
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.masterdata.students.form');
    }
}
