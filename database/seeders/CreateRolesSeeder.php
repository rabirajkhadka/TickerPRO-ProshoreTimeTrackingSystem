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
            'role' => 'member',
        ]);
        Role::create([
            'role' => 'admin',
        ]);
        Role::create([
            'role' => 'hr',
        ]);
        Role::create([
            'role' => 'dev',
        ]);
        Role::create([
            'role' => 'management',
        ]);

        User::create([
            'name' => 'Harry Larry',
            'email' => 'test@test.com',
            'password' => 'test123',
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
