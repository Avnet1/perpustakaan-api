<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'role_name' => 'Super Admin',
                'role_slug' => 'superadmin',
            ],
            [
                'role_name' => 'Admin',
                'role_slug' => 'admin',
            ],
            [
                'role_name' => 'Developer',
                'role_slug' => 'developer',
            ]
        ];

        for ($i = 0; $i < count($data); $i++) {
            $element = $data[$i];

            $result = Role::whereNull('deleted_at')->where('role_slug', $element['role_slug'])->first();

            if (!$result) {
                Role::create($element);
            }
        }
    }
}
