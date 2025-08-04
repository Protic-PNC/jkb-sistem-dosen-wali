<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Str;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $dosenWaliId = Position::where('position_name', 'Dosen Wali')->first()->position_id;
        $lutfiId = User::where('email', 'lutfi@gmail.com')->first()->id;
        
        // $kaprodiId = Position::where('position_name', 'Koordinator Program Studi')->first()->position_id;
        $cahyaId = User::where('email', 'cahya@gmail.com')->first()->id;
        
        $abdulId = User::where('email', 'abdul@gmail.com')->first()->id;
        
        $wachidId = User::where('email', 'wachid@gmail.com')->first()->id;
        
        $faizinId = User::where('email', 'faizin@gmail.com')->first()->id;
        
        $abdauId = User::where('email', 'abdau@gmail.com')->first()->id;
        
        // $kajurId = Position::where('position_name', 'Ketua Jurusan')->first()->position_id;
        $noviId = User::where('email', 'novi@gmail.com')->first()->id;

        $lecturer1 = Lecturer::create([
            'nidn' => '1192813218',
            'nip' => '112342112342112342',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            'user_id' => $lutfiId
        ]);

        $lecturer2 = Lecturer::create([
            //'lecturer_id' => Str::uuid(),
            'nidn' => '1192827890',
            'nip' => '112343112343112343',
            // 'lecturer_name' => 'Cahya Vikasari',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kaprodiId,
            'user_id' => $abdulId
        ]);

        $lecturer3 = Lecturer::create([
            //'lecturer_id' => Str::uuid(),
            'nidn' => '1192838532',
            'nip' => '112344112344112344',
            // 'lecturer_name' => 'Cahya Vikasari',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kaprodiId,
            'user_id' => $wachidId
        ]);

        $lecturer4 = Lecturer::create([
            //'lecturer_id' => Str::uuid(),
            'nidn' => '1192845678',
            'nip' => '112344112344112344',
            // 'lecturer_name' => 'Cahya Vikasari',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kaprodiId,
            'user_id' => $abdauId
        ]);

        $lecturer5 = Lecturer::create([
            //'lecturer_id' => Str::uuid(),
            'nidn' => '1192858763',
            'nip' => '112346112346112346',
            // 'lecturer_name' => 'Cahya Vikasari',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kaprodiId,
            'user_id' => $faizinId
        ]);

        $lecturer6 = Lecturer::create([
            //'lecturer_id' => Str::uuid(),
            'nidn' => '1192862345',
            'nip' => '112347112347112347',
            // 'lecturer_name' => 'Cahya Vikasari',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kaprodiId,
            'user_id' => $cahyaId
        ]);

        $lecturer7 = Lecturer::create([
            'nidn' => '1192878765',
            'nip' => '112348112348112348',
            // 'lecturer_name' => 'Dwi Novia Prasetyanti',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            // 'position_id' => $kajurId,
            'user_id' => $noviId
        ]);

        Lecturer::factory()
        ->count(20)
        ->create()
        ->each(function ($lecturer) {
            $lecturer->user->assignRole('dosenWali'); // atau 'kaprodi', tergantung kebutuhan
        });
    }
}
