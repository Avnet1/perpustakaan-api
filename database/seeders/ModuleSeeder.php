<?php

namespace Database\Seeders;

use App\Models\MasterMenu;
use App\Models\MasterModule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                "urutan" => 1,
                "list_menu" => [
                    [
                        "nama_menu" => "Beranda",
                        "slug" => "/beranda",
                        "urutan" => 1,
                    ],
                    [
                        "nama_menu" => "Bibliografi",
                        "slug" => "/master-bibliografi",
                        "urutan" => 2,
                        "childrens" => [
                            [
                                "nama_menu" => "Bibliografi",
                                "slug" => "/bibliografi",
                                "urutan" => 1,
                                "childrens" => [
                                    [
                                        "nama_menu" => "Daftar Bibliografi",
                                        "slug" => "/daftar-bibliografi",
                                        "urutan" => 1,
                                    ],
                                    [
                                        "nama_menu" => "Tambah Bibliografi Baru",
                                        "slug" => "/tambah-bibliografi",
                                        "urutan" => 2,
                                    ]
                                ]
                            ],
                            [
                                "nama_menu" => "Eksemplar",
                                "slug" => "/eksemplar",
                                "urutan" => 2,
                            ],
                            [
                                "nama_menu" => "Perbaikan Buku",
                                "slug" => "/perbaikan-buku",
                                "urutan" => 3,
                            ],
                            [
                                "nama_menu" => "Tools",
                                "slug" => "/tools",
                                "urutan" => 4,
                            ]
                        ]
                    ],
                    [
                        "nama_menu" => "Sirkulasi",
                        "slug" => "/master-sirkulasi",
                        "urutan" => 3,
                        "childrens" => [
                            [
                                "nama_menu" => "Sirkulasi",
                                "slug" => "/sirkulasi",
                                "urutan" => 1,
                                "childrens" => [
                                    [
                                        "nama_menu" => "Mulai Transaksi",
                                        "slug" => "/mulai-transaksi",
                                        "urutan" => 1,
                                    ],
                                    [
                                        "nama_menu" => "Pengembalian Kilat",
                                        "slug" => "/pengembalian-kilat",
                                        "urutan" => 2,
                                    ],
                                    [
                                        "nama_menu" => "Aturan Peminjaman",
                                        "slug" => "/aturan-peminjaman",
                                        "urutan" => 3,
                                    ],
                                    [
                                        "nama_menu" => "Sejarah Peminjaman",
                                        "slug" => "/sejarah-peminjaman",
                                        "urutan" => 4,
                                    ],
                                    [
                                        "nama_menu" => "Peringatan Jatuh Tempo",
                                        "slug" => "/peringatan-jatuh-tempo",
                                        "urutan" => 5,
                                    ],
                                    [
                                        "nama_menu" => "Daftar Keterlambatan",
                                        "slug" => "/daftar-keterlambatan",
                                        "urutan" => 6,
                                    ],
                                    [
                                        "nama_menu" => "Cek Status Buku",
                                        "slug" => "/cek-status-buku",
                                        "urutan" => 7,
                                    ],
                                ]
                            ],
                            [
                                "nama_menu" => "Perbaikan dan Pengaktifan",
                                "slug" => "/perbaikan-dan-pengaktifan",
                                "urutan" => 2,
                            ],
                        ],

                    ]
                ]
            ],
            [
                "nama_modul" => "Perpustakaan Digital",
                "slug" => "/perpustakaan-digital",
                "urutan" => 2
            ],
        ];

        $user = User::where('email', 'admin@indosistem.com')->first();

        DB::transaction(function () use ($data, $user) {
            $this->seedModules($data, $user->user_id);
        });
    }

    private function seedModules(array $data, string $id)
    {
        foreach ($data as $modul) {
            // Cek apakah modul sudah ada
            $module = MasterModule::firstOrCreate(
                ['slug' => $modul['slug']],
                [
                    'nama_modul' => $modul['nama_modul'],
                    'slug' => $modul['slug'],
                    'urutan' => $modul['urutan'],
                    'created_at' => Carbon::now(),
                    'created_by' => $id
                ]
            );

            // Jika ada list_menu, simpan secara rekursif
            if (isset($modul['list_menu'])) {
                foreach ($modul['list_menu'] as $menu) {
                    $this->insertMenuRecursive($id, $menu, $module->modul_id, null);
                }
            }
        }
    }

    private function insertMenuRecursive(string $userId, array $menu, string $modulId, ?string $parentId = null)
    {
        // Cek apakah menu sudah ada
        $existingMenu = MasterMenu::where([
            'modul_id' => $modulId,
            'slug' => $menu['slug'],
            'parent_id' => $parentId
        ])->first();

        if ($existingMenu) {
            return; // Skip jika sudah ada
        }

        // Simpan menu
        $newMenu = MasterMenu::create([
            'modul_id' => $modulId,
            'nama_menu' => $menu['nama_menu'],
            'slug' => $menu['slug'],
            'urutan' => $menu['urutan'],
            'parent_id' => $parentId,
            'created_at' => Carbon::now(),
            'created_by' => $userId
        ]);

        // Jika punya children, lakukan rekursif
        if (isset($menu['childrens']) && is_array($menu['childrens'])) {
            foreach ($menu['childrens'] as $child) {
                $this->insertMenuRecursive($userId, $child, $modulId, $newMenu->menu_id);
            }
        }
    }
}
