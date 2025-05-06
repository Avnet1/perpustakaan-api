<?php

namespace Database\Seeders;

use App\Models\MasterModule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                "nama_modul" => "Admin Perpustakaan",
                "slug" => "/admin-perpustakaan",
                "urutan" => 1
            ],
            [
                "nama_modul" => "Perpustakaan Digital",
                "slug" => "/perpustakaan-digital",
                "urutan" => 2
            ],
        ];

        $user = User::where('email', 'admin@indosistem.com')->first();

        for ($i = 0; $i < count($data); $i++) {
            $element = $data[$i];

            $row = MasterModule::whereNull('deleted_at')
                ->where('nama_modul', 'ilike', '%' . $element['nama_modul'] . '%')
                ->first();

            if (!$row) {
                MasterModule::store(array_merge($element, [
                    'created_by' => $user->user_id
                ]));
            }
        }
    }
}
