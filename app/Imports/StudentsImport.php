<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    private $current = 0;
    private $successCount = 0;

    public function model(array $row)
    {
        $this->current++;
        if ($this->current > 1) {
            $existing = Student::where('nim', $row[2])->first();

            if (!$existing) {
                $studentClass = StudentClass::where('class_name', $row[4])
                    ->where('status', 'active')
                    ->first();

                $user = User::where('email', $row[5])->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $row[0],
                        'email' => $row[5],
                        'password' => bcrypt('password123'),
                    ]);
                    $user->assignRole('mahasiswa');
                }

                $this->successCount++; // ✅ Tambah counter jika berhasil

                return new Student([
                    'user_id' => $user->id,
                    'student_class_id' => $studentClass->id ?? null,
                    'student_phone_number' => $row[1],
                    'nim' => $row[2],
                    'student_address' => $row[3],
                    'active_at_semester' => 1,
                ]);
            }
        }

        return null;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}

