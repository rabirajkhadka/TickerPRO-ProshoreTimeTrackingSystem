<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the roles
        Role::create([
            'role' => 'user',
        ]);
        Role::create([
            'role' => 'admin',
        ]);
        Role::create([
            'role' => 'hr',
        ]);

        User::create([
            'name' => 'Risab Shres',
            'email' => 'test@test.com',
            'password' => 'test123',
            'confirmPass' => 'test123',
        ]);
        UserRole::create([
            'user_id' => 1,
            'role_id' => 1,
        ]);
        UserRole::create([
            'user_id' => 1,
            'role_id' => 2,
        ]);
    }
}
