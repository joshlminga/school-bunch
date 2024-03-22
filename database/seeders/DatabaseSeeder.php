<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $admin = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'admin@vormia.com',
            'password' => Hash::make('@Admin1234')
        ]);

        // Add RolesTableSeeder
        $roles = RolesTableSeeder::class;
        $this->call($roles);

        // ? Assign roles with ID 1 to the user
        $admin->roles()->attach(Config::get('roles.roles.admin'));
    }
}
