<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Lecturer;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Contoh dosen sebagai Kepala Prodi
         $headOfProgram = Lecturer::first(); // Ambil dosen pertama sebagai contoh

         Program::create([
             'program_name' => 'Teknik Informatika',
             'degree' => 'D3',
             'head_of_program_id' => $headOfProgram->lecturer_id,
         ]);
         Program::create([
             'program_name' => 'Rekayasa Keamanan Siber',
             'degree' => 'D4',
             'head_of_program_id' => $headOfProgram->lecturer_id,
         ]);
         Program::create([
             'program_name' => 'Akuntansi Keuangan Lembaga Syariah',
             'degree' => 'D4',
             'head_of_program_id' => $headOfProgram->lecturer_id,
         ]);
         Program::create([
             'program_name' => 'Teknologi Rekayasa MUltimedia',
             'degree' => 'D4',
             'head_of_program_id' => $headOfProgram->lecturer_id,
         ]);
    }
}
