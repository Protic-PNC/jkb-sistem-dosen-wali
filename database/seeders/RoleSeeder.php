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
        $JurusanRole = Role::create([
            'name' => 'jurusan',
        ]);
        $mahasiswaRole = Role::create([
            'name' => 'mahasiswa',
        ]);



        //insert into user
        $userAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);

        $userDosenWali1 = User::create([
            'name' => 'Lutfi Syafirullah, S.T., M.Kom.',
            'email' => 'lutfi@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);

        $userKaprodi1 = User::create([
            'name' => 'Cahya Vikasari, S.T., M.Eng.',
            'email' => 'cahya@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);
        $userKaprodi2 = User::create([
            'name' => 'Abdul Rohman Supriyono, S.T., M.Kom.',
            'email' => 'abdul@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);
        $userKaprodi3 = User::create([
            'name' => 'Faizin Firdaus',
            'email' => 'faizin@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);
        $userKaprodi4 = User::create([
            'name' => 'Nur Wachid Adi Prasetya, S.Kom., M.Kom.',
            'email' => 'wachid@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);
        $userKaprodi5 = User::create([
            'name' => 'Prih Diantono Abda`u, S.Kom., M.Kom.',
            'email' => 'abdau@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);

        $userJurusan = User::create([
            'name' => 'Dwi Novia Prasetyanti, S.Kom., M.Cs.',
            'email' => 'novi@gmail.com',
            // 'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);



        //assign role each user
        $userAdmin->assignRole($adminRole);
        // $userMahasiswa->assignRole($mahasiswaRole);
        $userDosenWali1->assignRole($dosenWaliRole);
        $userKaprodi1->assignRole($kaprodiRole);
        $userKaprodi2->assignRole($kaprodiRole);
        $userKaprodi3->assignRole($kaprodiRole);
        $userKaprodi4->assignRole($kaprodiRole);
        $userKaprodi5->assignRole($kaprodiRole);
        $userJurusan->assignRole($JurusanRole);


        //add debug information
        Log::info('User created with ID: ' . $userAdmin->id);
        Log::info('Assigned role: ' . $userAdmin->name);
        Log::info('User roles after assigment: ' . $userAdmin->roles->pluck('name'));
    }
}
