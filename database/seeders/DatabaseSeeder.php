<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ProgramSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(LecturerSeeder::class);
        $this->call(StudentClassSeeder::class);
        // $this->call(StudentSeeder::class);
    }
}
