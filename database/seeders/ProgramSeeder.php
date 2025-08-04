<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Lecturer;
use Illuminate\Support\Str;
use App\Models\User;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kaprodiTI = Lecturer::where('user_id', User::where('name', 'Cahya Vikasari, S.T., M.Eng.')->value('id'))->first();
        $kaprodiRKS = Lecturer::where('user_id', User::where('name', 'Abdul Rohman Supriyono, S.T., M.Kom.')->value('id'))->first();
        $kaprodiTRPL = Lecturer::where('user_id', User::where('name', 'Prih Diantono Abda`u, S.Kom., M.Kom.')->value('id'))->first();
        $kaprodiTRM = Lecturer::where('user_id', User::where('name', 'Nur Wachid Adi Prasetya, S.Kom., M.Kom.')->value('id'))->first();
        $kaprodiALKS = Lecturer::where('user_id', User::where('name', 'Faizin Firdaus')->value('id'))->first();

        $program1 = Program::create([
            'program_name' => 'Teknik Informatika',
            'head_of_program_id' => $kaprodiTI->id,
            'degree' => 'D3',
        ]);
        $program2 = Program::create([
            'program_name' => 'Rekayasa Keamanan Siber',
            'head_of_program_id' => $kaprodiRKS->id,
            'degree' => 'D4',
        ]);
        $program3 = Program::create([
            'program_name' => 'Akuntansi Lembaga Keuangan Syariah',
            'head_of_program_id' => $kaprodiALKS->id,
            'degree' => 'D4',
        ]);
        $program4 = Program::create([
            'program_name' => 'Teknologi Rekayasa Multimedia',
            'head_of_program_id' => $kaprodiTRM->id,
            'degree' => 'D4',
        ]);
        $program5 = Program::create([
            'program_name' => 'Teknologi Rekayasa Perangkat Lunak',
            'head_of_program_id' => $kaprodiTRPL->id,
            'degree' => 'D4',
        ]);
        $program6 = Program::create([
            'program_name' => 'Prodi Baru',
            'head_of_program_id' => null,
            'degree' => 'D4',
        ]);
    }
}
