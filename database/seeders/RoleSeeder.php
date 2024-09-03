<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //create role
        $adminRole = Role::create([
            'name' => 'admin',
        ]);
        $dosenWaliRole = Role::create([
            'name' => 'dosenWali',
        ]);
        $kaprodiRole = Role::create([
            'name' => 'kaprodi',
        ]);
        $kajurRole = Role::create([
            'name' => 'kajur',
        ]);
        $mahasiswaRole = Role::create([
            'name' => 'mahasiswa',
        ]);



        //insert into user
        $userAdmin = User::create([
            'name' => 'rayhan',
            'email' => 'rayhan@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('12345678'),
        ]);

        $userMahasiswa = User::create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('12345678'),
        ]);

        $userDosenWali = User::create([
            'name' => 'Dosen Wali',
            'email' => 'dosenWali@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('12345678'),
        ]);

        $userKaprodi = User::create([
            'name' => 'kaprodi',
            'email' => 'kaprodi@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('12345678'),
        ]);

        //assign role each user
        $userAdmin->assignRole($adminRole);
        $userMahasiswa->assignRole($mahasiswaRole);
        $userDosenWali->assignRole($dosenWaliRole);
        $userKaprodi->assignRole($kaprodiRole);

        //add debug information
        Log::info('User created with ID: ' . $userAdmin->id);
        Log::info('Assigned role: ' . $userAdmin->name);
        Log::info('User roles after assigment: ' . $userAdmin->roles->pluck('name'));
    }
}
