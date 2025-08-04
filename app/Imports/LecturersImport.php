<?php

namespace App\Imports;

use App\Models\Lecturer;
use App\Models\Program;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class LecturersImport implements ToModel
{
    private $current = 0;
    private $successCount = 0;

    public function model(array $row)
    {
        $this->current++;
        if ($this->current > 1) {

            if (count($row) < 9) {
                return null;
            }
            // Cek apakah lecturer sudah ada berdasarkan NIP
            $existing = Lecturer::where('nip', $row[2])->first();

            if (!$existing) {
                // Cek apakah user dengan email tersebut sudah ada
                $user = User::where('email', $row[8])->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $row[0],
                        'email' => $row[8],
                        'password' => Hash::make('12345678'),
                    ]);
                }

                // Tambah jumlah berhasil
                $this->successCount++;

                // Buat lecturer baru
                $lecturer = Lecturer::create([
                    'user_id' => $user->id,
                    'lecturer_phone_number' => $row[1],
                    'lecturer_address' => $row[7],
                    'nip' => $row[2],
                    'nidn' => $row[3],
                ]);
            } else {
                $lecturer = $existing;
                $user = $lecturer->user;
            }

            // Penentuan role dan update ke class/program jika perlu
            $role = null;

            if ($row[5] === 'Dosen Wali') {
                $role = 'dosenWali';

                if (!empty($row[6])) {
                    $studentClass = StudentClass::where('class_name', $row[6])->first();
                    if ($studentClass) {
                        $studentClass->update(['academic_advisor_id' => $lecturer->id]);
                    }
                }
            } elseif ($row[5] === 'Koordinator Program Studi') {
                $role = 'kaprodi';

                $program = Program::where('program_name', $row[4])->first();
                if ($program) {
                    $program->update(['head_of_program_id' => $lecturer->id]);
                }
            } elseif ($row[5] === 'Ketua Jurusan') {
                $role = 'kajur';
            }

            // Assign role jika ada
            if ($role && !$user->hasRole($role)) {
                $user->assignRole($role);
            }

            return $lecturer;
        }

        return null;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}
