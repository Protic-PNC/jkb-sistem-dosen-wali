<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Spatie\Permission\Models\Role;

class StudentsImport implements ToCollection, ToModel
{
    private $current = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    }

    public function model(array $row)
    {
        $this->current++;
        if($this->current > 1)
        {
            $class = StudentClass::where('class_name', $row[4])
            ->where('status', 'active')
            ->first();

            // Cek apakah email sudah ada
            $user = User::where('email', $row[5])->first();

            if (!$user) {
                // Jika belum ada, buat user baru
                $user = User::create([
                    'name' => $row[0],
                    'email' => $row[5],
                    'password' => bcrypt('123'),
                ]);

                // Assign role 'mahasiswa' ke user
                $user->assignRole('mahasiswa');
            }
            
             // Cek apakah mahasiswa dengan NIM atau nama yang sama sudah ada
             $studentExists = Student::where('nim', $row[2])
             ->orWhere('student_name', $row[0])
             ->first();

            // Jika mahasiswa sudah ada, lewati baris ini
            if ($studentExists) {
                return null;
            }

            // Menambahkan student baru dan kaitkan dengan user yang baru dibuat
            return new Student([
                'user_id' => $user->id,
                'class_id' => $class ? $class->class_id : null,
                'student_phone_number' => $row[1], // Kolom Excel untuk nomor telepon
                'nim' => $row[2], // Kolom Excel untuk NIM
                'student_name' => $row[0], // Nama yang sama dengan nama user
                'student_address' => $row[3], // Kolom Excel untuk alamat
            ]);
        }
    }
}
