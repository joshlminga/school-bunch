<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            "name" => 'Admin',
            "slug" => 'admin',
            "module" => 'dashboard,users,permissions,upload,update,download,delete',
        ]);

        Role::create([
            "name" => 'User',
            "slug" => 'user',
            "module" => 'dashboard,upload,update,download,delete',
        ]);

        Role::create([
            "name" => 'Manager',
            "slug" => 'manager',
            "module" => 'dashboard,upload,update,download',
        ]);

        Role::create([
            "name" => 'Access',
            "slug" => 'access',
            "module" => 'dashboard,download',
        ]);
    }
}
