<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecturer;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturerId = Lecturer::where('lecturer_name', 'Bulianto Denis Notokusumo')->first()->lecturer_id;

        $class1 = StudentClass::create([
            'class_name' => 'TI-3A',
            'program_id' => 1,
            'entry_year' => 2022,
            'academic_advisor_id' => $lecturerId
        ]);
    }
}
