<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudentClass;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::where('email', 'rayhan@gmail.com')->first()->id;
        $classId = StudentClass::where('class_name', 'TI-3A')->first()->id;

        $student1 = Student::create([
            'user_id' => $userId,
            'student_class_id' => $classId,
            'student_phone_number' => '0895392167815',
            'nim' => '220102022',
            // 'student_name' => 'Rayhan',
            'student_address' => 'Banjaranyar',
        ]);
    }
}
