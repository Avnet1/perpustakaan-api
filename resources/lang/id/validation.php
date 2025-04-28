<?php

return [
    'required' => ':attribute wajib diisi.',
    'unique' => ':attribute sudah digunakan.',
    'exists' => ':attribute tidak ditemukan.',
    'min' => [
        'string' => ':attribute minimal harus :min karakter.',
        'numeric' => ':attribute minimal :min.',
        'array' => ':attribute minimal harus memiliki :min item.',
        'file' => ':attribute minimal harus :min kilobytes.',
    ],
    'max' => [
        'string' => ':attribute maksimal :max karakter.',
        'numeric' => ':attribute maksimal :max.',
        'array' => ':attribute maksimal memiliki :max item.',
        'file' => ':attribute maksimal :max kilobytes.',
    ],
    'email' => 'Format :attribute tidak valid.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'custom' => [
        'success' => [
            'default' => [],
            'auth' => [
                'login' => 'Login berhasil',
                'refreshToken' => 'Refresh token berhasil',
            ],
            'organization' => [
                'create' => 'Data organisasi berhasil ditambahkan',
                'fetch' => 'Tarik data organisasi berhasil',
                'find' => 'Tarik data organisasi berhasil',
                'update' => 'Data organisasi berhasil diubah',
                'delete' => 'Data organisasi berhasil dihapus',
            ],
            'client' => [
                'create' => 'Data pelanggan berhasil ditambahkan',
                'fetch' => 'Tarik data pelanggan berhasil',
                'find' => 'Tarik data pelanggan berhasil',
                'update' => 'Data pelanggan berhasil diubah',
                'delete' => 'Data pelanggan berhasil dihapus',
            ],
            "tipeGMD" => [
                "store" => "Data GMD berhasil ditambahkan",
                "update" => "Data GMD berhasil diubah",
                "delete" => "Data GMD berhasil dihapus"
            ]
        ],
        'error' => [
            'default' => [
                'required' => 'Data :attribute wajib diisi',
                'unique' => 'Data :attribute wajib unik',
                'exists' => 'Data :attribute sudah digunakan',
                'minCharacter' => 'Jumlah karakter :attribute minimal :number karakge',
                'notFound' => 'Data :attribute tidak ditemukan',
            ],
            'auth' => [
                'passwordInvalid' => 'Password yang diinput tidak sesuai',
                'tokenGenerate' => 'Generate akses token gagal',
                'tokenInvalid' => 'Token tidak sesuai atau habis masa berlakunya',
                'unauthorized' => 'Proses Otentikasi gagal',
                'tokenUnprovide' => 'Token tidak tersedia',
                "notApproved" => "Akun anda bulum disetujui. Silahkan hubungi customer support",
                "expiredAccount" => "Masa aktif berlangganan anda telah jatuh tempo. Silahkan perpanjang layanan"
            ],
            'client' => [
                'create' => 'Data pelanggan gagal ditambahkan',
                'update' => 'Data pelanggan gagal diubah'
            ],
            "tipeGMD" => [
                "store" => "Data GMD gagal ditambahkan",
                "update" => "Data GMD gagal diubah",
                "delete" => "Data GMD gagal dihapus"
            ]
        ],
    ],
];
