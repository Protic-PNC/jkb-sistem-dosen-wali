<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Lecturer;
use App\Models\StudentClass;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data program dan dosen untuk digunakan dalam kelas
        $program = Program::first(); // Ambil program pertama
        $academicAdvisor = Lecturer::first(); // Ambil dosen pertama sebagai contoh

        StudentClass::create([
            'program_id' => $program->program_id,
            'academic_advisor_id' => $academicAdvisor->lecturer_id,
            'class_name' => 'TI-1',
            'academic_year' => 2024,
        ]);

        StudentClass::create([
            'program_id' => $program->program_id,
            'academic_advisor_id' => $academicAdvisor->lecturer_id,
            'class_name' => 'SI-1',
            'academic_year' => 2024,
        ]);
    }
}
