<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin = Role::where("nama_role", "Administrator")->first();
        $role_petugas = Role::where("nama_role", "Petugas")->first();

        User::create([
            "nama" => "Super Admin",
            "email" => "superadmin@test.com",
            "username" => "superadmin",
            "password" => bcrypt("password"),
            "role_id" => $role_admin->id
        ]);

        User::create([
            "nama" => "Hanif",
            "email" => "hanif@test.com",
            "username" => "hanif123",
            "password" => bcrypt("password"),
            "role_id" => $role_petugas->id
        ]);

        User::create([
            "nama" => "Nurul",
            "email" => "nurul@gmail.com",
            "username" => "nurul123",
            "password" => bcrypt("password"),
            "role_id" => $role_petugas->id
        ]);

        User::create([
            "nama" => "Lisa",
            "email" => "lisa@gmail.com",
            "username" => "lisa123",
            "password" => bcrypt("password"),
            "role_id" => $role_petugas->id
        ]);
    }
}
