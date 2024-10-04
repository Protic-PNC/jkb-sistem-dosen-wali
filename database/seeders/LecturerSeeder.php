<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Position;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positionId = Position::where('position_name', 'Dosen Wali')->first()->position_id;
        $userId = User::where('name', 'Bulianto Denis Notokusumo')->first()->id;

        $lecturer1 = Lecturer::create([
            'nidn' => '119281',
            'nip' => '112342',
            'lecturer_name' => 'Bulianto Denis Notokusumo',
            'lecturer_phone_number' => '08976589032',
            'lecturer_address' => 'Madiun',
            'position_id' => $positionId,
            'user_id' => $userId
        ]);
    }
}
