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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('12345678'),
        ]);

        $userDosenWali = User::create([
            'name' => 'Bulianto Denis Notokusumo',
            'email' => 'bul@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);

        $userKaprodi = User::create([
            'name' => 'vika',
            'email' => 'vika@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);

        $userKajur = User::create([
            'name' => 'novi',
            'email' => 'novi@gmail.com',
            'avatar' => 'images/avatar-default.svg',
            'password' => bcrypt('123'),
        ]);



        //assign role each user
        $userAdmin->assignRole($adminRole);
        // $userMahasiswa->assignRole($mahasiswaRole);
        $userDosenWali->assignRole($dosenWaliRole);
        $userKaprodi->assignRole($kaprodiRole);
        $userKajur->assignRole($kajurRole);

        //add debug information
        Log::info('User created with ID: ' . $userAdmin->id);
        Log::info('Assigned role: ' . $userAdmin->name);
        Log::info('User roles after assigment: ' . $userAdmin->roles->pluck('name'));
    }
}
