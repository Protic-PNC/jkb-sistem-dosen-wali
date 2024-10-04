<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

use App\Models\Lecturer;
use App\Models\User;
use App\Models\Program;
use App\Models\StudentClass;
use App\Models\Position;

class LecturerImport implements ToCollection, ToModel
{
    private $current = 0;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        $this->current++;
        if ($this->current <= 1) {
            return;
        }

        // Get class, user, and position based on input data
        if($row[6])
        {
            $class = StudentClass::where('class_name', $row[6])->first();
        }
        $position = Position::where('position_name', $row[5])->first();
        $program = Program::where('program_name', $row[4])->first();

        //dd($class, $position, $program);
        
        // Cek apakah email sudah ada
        $user = User::where('email', $row[8])
        ->where('name', $row[0])
        ->first();

        if (!$user) {
            // Jika belum ada, buat user baru
            $user = User::create([
                'name' => $row[0],
                'email' => $row[8],
                'password' => bcrypt('123'),
            ]);

            if($row[5] == 'Dosen Wali')
            {
                $role = 'dosenWali';
            }
            else
            {
                $role = 'kaprodi';
            }
            $user->assignRole($role);
        }

        $lecturer = Lecturer::where('lecturer_name', $row[0])->first();

        if(!$lecturer)
        {
            $lecturer = new Lecturer();
            $lecturer->user_id = $user->id;
            $lecturer->lecturer_name = $row[0];
            $lecturer->lecturer_phone_number = $row[1];
            $lecturer->lecturer_address = $row[7];
            $lecturer->nip = $row[2];
            $lecturer->nidn = $row[3];
            $lecturer->position_id = $position ? $position->position_id : null;
            $lecturer->save();
        }

        //Update class or program based on position
        if ($position && $position->position_name == 'Dosen Wali') {
            if ($class) {
                $class->update(['academic_advisor_id' => $lecturer->lecturer_id]);
            }
        } elseif ($position && $position->position_name == 'Koordinator Program Studi') {
            if ($program) {
                $program->update(['head_of_program_id' => $lecturer->lecturer_id]);
            }
        }

        //dd($user, $lecturer, $class, $program);

        return $lecturer;
    }
}
