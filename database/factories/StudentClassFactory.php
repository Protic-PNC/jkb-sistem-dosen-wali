<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentClass>
 */
class StudentClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $programPrefixMap = [
            'Teknik Informatika' => 'TI',
            'Rekayasa Keamanan Siber' => 'RKS',
            'Akuntansi Lembaga Keuangan Syariah' => 'ALKS',
            'Teknologi Rekayasa Multimedia' => 'TRM',
            'Teknologi Rekayasa Perangkat Lunak' => 'TRPL',
            'Prodi Baru' => 'PB',
        ];

        $program = Program::inRandomOrder()->first();
        $prefix = $programPrefixMap[$program->program_name] ?? 'XXX';

        $maxTingkat = $program->degree === 'D3' ? 3 : 4;
        $tingkat = fake()->numberBetween(1, $maxTingkat);
        $abjad = chr(fake()->numberBetween(65, 68)); // A-D

        return [
            'class_name' => "$prefix-$tingkat$abjad",
            'program_id' => $program->id,
            'academic_advisor_id' => Lecturer::inRandomOrder()->first()?->id ?? Lecturer::factory(),
            'academic_advisor_decree' => fake()->bothify('###/PL.##/HK.##.##/20##'),
            'entry_year' => fake()->numberBetween(2020, 2024),
        ];
    }
}
