<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where("role_slug", "admin")->first();

        User::create([
            'name' => 'Lorem Ipsum',
            'identity_code' => 'admin001',
            'password' => Hash::make('password123'),
            'role_id' => $role->role_id ,
            'email' => 'admin@example.com',
        ]);
    }
}
