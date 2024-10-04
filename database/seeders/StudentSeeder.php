<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudentClass;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::where('name', 'Rayhan Afrizal Fajri')->first()->id;
        $classId = StudentClass::where('class_name', 'TI-3A')->first()->class_id;

        $student1 = Student::create([
            'user_id' => $userId,
            'class_id' => $classId,
            'student_phone_number' => '0895392167815',
            'nim' => '220302022',
            'student_name' => 'Rayhan',
            'student_address' => 'Banjaranyar',
        ]);
    }
}
