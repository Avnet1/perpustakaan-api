<?php

return [
    'required' => ':attribute wajib diisi.',
    'unique' => ':attribute sudah digunakan.',
    'exists' => ':attribute tidak ditemukan.',
    'image' => ':attribute wajib file image',
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
            'province' => [
                'create' => 'Data provinsi berhasil ditambahkan',
                'fetch' => 'Tarik data provinsi berhasil',
                'find' => 'Tarik data provinsi berhasil',
                'update' => 'Data provinsi berhasil diubah',
                'delete' => 'Data provinsi berhasil dihapus',
            ],
            'region' => [
                'create' => 'Data kabupaten/kota berhasil ditambahkan',
                'fetch' => 'Tarik data kabupaten/kota berhasil',
                'find' => 'Tarik data kabupaten/kota berhasil',
                'update' => 'Data kabupaten/kota berhasil diubah',
                'delete' => 'Data kabupaten/kota berhasil dihapus',
            ],
            'sub_district' => [
                'create' => 'Data kecamatan berhasil ditambahkan',
                'fetch' => 'Tarik kecamatan berhasil',
                'find' => 'Tarik kecamatan berhasil',
                'update' => 'Data kecamatan berhasil diubah',
                'delete' => 'Data kecamatan berhasil dihapus',
            ],
            'village' => [
                'create' => 'Data kelurahan berhasil ditambahkan',
                'fetch' => 'Tarik kelurahan berhasil',
                'find' => 'Tarik kelurahan berhasil',
                'update' => 'Data kelurahan berhasil diubah',
                'delete' => 'Data kelurahan berhasil dihapus',
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
                'existedRow' => 'Data :attribute duplikat. Data :attribute sudah terdaftar di sistem'
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
            'province' => [
                'create' => 'Data provinsi gagal ditambahkan',
                'update' => 'Data provinsi gagal diubah',
                'delete' => 'Data provinsi gagal dihapus',
            ],
            'region' => [
                'create' => 'Data kabupaten/kota gagal ditambahkan',
                'update' => 'Data kabupaten/kota gagal diubah',
                'delete' => 'Data kabupaten/kota gagal dihapus',
            ],
            'sub_district' => [
                'create' => 'Data kecamatan gagal ditambahkan',
                'update' => 'Data kecamatan gagal diubah',
                'delete' => 'Data kecamatan gagal dihapus',
            ],
            'village' => [
                'create' => 'Data kelurahan gagal ditambahkan',
                'update' => 'Data kelurahan gagal diubah',
                'delete' => 'Data kelurahan gagal dihapus',
            ],
            'organization' => [
                'create' => 'Data organisasi gagal ditambahkan',
                'update' => 'Data organisasi gagal diubah',
                'delete' => 'Data organisasi gagal dihapus',
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
