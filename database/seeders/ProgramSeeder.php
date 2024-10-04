<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program1 = Program::create([
            'program_name' => 'Teknik Informatika',
            'degree' => 'D3',
        ]);
        $program2 = Program::create([
            'program_name' => 'Rekayasa Keamanan Siber',
            'degree' => 'D4',
        ]);
        $program3 = Program::create([
            'program_name' => 'Akuntansi Lembaga Keuangan Syariah',
            'degree' => 'D4',
        ]);
        $program4 = Program::create([
            'program_name' => 'Teknologi Rekayasa Multimedia',
            'degree' => 'D4',
        ]);
    }
}
