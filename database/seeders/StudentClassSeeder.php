<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Str;
use App\Models\Lecturer;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = Program::all();
        $prefixMap = [
            'Teknik Informatika' => 'TI',
            'Rekayasa Keamanan Siber' => 'RKS',
            'Akuntansi Lembaga Keuangan Syariah' => 'ALKS',
            'Teknologi Rekayasa Multimedia' => 'TRM',
            'Teknologi Rekayasa Perangkat Lunak' => 'TRPL',
            'Prodi Baru' => 'PB',
        ];

        $availableLecturers = Lecturer::whereNotIn('id', function ($query) {
            $query->select('academic_advisor_id')->from('student_classes');
        })->whereNotIn('id', function ($query) {
            $query->select('head_of_program_id')->from('programs')->whereNotNull('head_of_program_id');
        })->get()->filter(function ($lecturer) {
            return !$lecturer->user->hasRole('jurusan');
        })->values();

        $usedLecturerIds = [];

        foreach ($programs as $program) {
            $prefix = $prefixMap[$program->program_name] ?? 'XXX';
            $maxTingkat = $program->degree === 'D3' ? 3 : 4;

            for ($tingkat = 1; $tingkat <= $maxTingkat; $tingkat++) {
                foreach (['A', 'B'] as $abjad) {
                    $lecturer = $availableLecturers->first(fn($l) => !in_array($l->id, $usedLecturerIds));
                    if (!$lecturer) break 2;

                    $usedLecturerIds[] = $lecturer->id;

                    StudentClass::create([
                        'class_name' => "$prefix-$tingkat$abjad",
                        'program_id' => $program->id,
                        'academic_advisor_id' => $lecturer->id,
                        'academic_advisor_decree' => fake()->bothify('###/PL.##/HK.##.##/20##'),
                        'entry_year' => fake()->numberBetween(2020, 2024),
                    ]);
                }
            }
        }
        
        // StudentClass::factory()->count(20)->create();

        // $academicAdvisorId = Lecturer::where('id', 1)->first()->id;
        // $programTI = Program::where('program_name', 'Teknik Informatika')->first()->id;
        // $programRKS = Program::where('program_name', 'Teknik Informatika')->first()->id;
        // $programALKS = Program::where('program_name', 'Teknik Informatika')->first()->id;
        // $programTRM = Program::where('program_name', 'Teknik Informatika')->first()->id;
        // $programTRPL = Program::where('program_name', 'Teknik Informatika')->first()->id;
        // $programPB = Program::where('program_name', 'Teknik Informatika')->first()->id;

        // $class1 = StudentClass::create([
        //     //'student_class_id' => Str::uuid(),
        //     'class_name' => 'TI-3A',
        //     'program_id' => $programTI,
        //     'academic_advisor_id' => $academicAdvisorId,
        //     'academic_advisor_decree' => '221/PL.43/HK.03.01/2023',
        //     'entry_year' => 2022,
        // ]);

        // $class1 = StudentClass::create([
        //     //'student_class_id' => Str::uuid(),
        //     'class_name' => 'TI-3A',
        //     'program_id' => $programTI,
        //     'academic_advisor_id' => $academicAdvisorId,
        //     'academic_advisor_decree' => '221/PL.43/HK.03.01/2023',
        //     'entry_year' => 2022,
        // ]);

        // $class1 = StudentClass::create([
        //     //'student_class_id' => Str::uuid(),
        //     'class_name' => 'TI-3B',
        //     'program_id' => $programTI,
        //     'academic_advisor_id' => $academicAdvisorId,
        //     'academic_advisor_decree' => '221/PL.43/HK.03.01/2023',
        //     'entry_year' => 2022,
        // ]);

        // $class1 = StudentClass::create([
        //     //'student_class_id' => Str::uuid(),
        //     'class_name' => 'TI-3C',
        //     'program_id' => $programTI,
        //     'academic_advisor_id' => $academicAdvisorId,
        //     'academic_advisor_decree' => '221/PL.43/HK.03.01/2023',
        //     'entry_year' => 2022,
        // ]);

        // $class1 = StudentClass::create([
        //     //'student_class_id' => Str::uuid(),
        //     'class_name' => 'TI-3D',
        //     'program_id' => $programTI,
        //     'academic_advisor_id' => $academicAdvisorId,
        //     'academic_advisor_decree' => '221/PL.43/HK.03.01/2023',
        //     'entry_year' => 2022,
        // ]);
    }
}
