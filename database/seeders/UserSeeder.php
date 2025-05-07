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
        $role = Role::where("role_slug", "superadmin")->first();

        $user = User::whereNull('deleted_at')->where('email', 'admin@indosistem.com')->first();
        if (!$user) {
            User::create([
                'name' => 'Indosistem',
                'username' => 'indosistem',
                'password' => Hash::make('password123'),
                'role_id' => $role->role_id,
                'email' => 'admin@indosistem.com',
            ]);
        }
    }
}
