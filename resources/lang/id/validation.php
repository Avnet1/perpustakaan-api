<?php

return [
    'required' => ':attribute wajib diisi.',
    'unique' => ':attribute sudah digunakan.',
    'exists' => ':attribute tidak ditemukan.',
    'image' => ':attribute wajib file image',
    'mimes' => ':attribute harus berupa file dengan format: :value.',
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
    'array' => ':attribute harus berupa array.',
    'min' => [
        'array' => ':attribute minimal harus memiliki :min item.',
    ],
    'email' => 'Format :attribute tidak valid.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'custom' => [
        'success' => [
            'default' => [
                'uploadImage' => 'Upload :attribute berhasil',
                'findImage' => 'Data :attribut berhasil ditemukan',
                'approved' => ':attribute berhasil disetujui'
            ],
            'role' => [
                'create' => 'Data role berhasil ditambahkan',
                'fetch' => 'Tarik data role berhasil',
                'find' => 'Tarik data role berhasil',
                'update' => 'Data role berhasil diubah',
                'delete' => 'Data role berhasil dihapus',
            ],
            'user' => [
                'create' => 'Data user berhasil ditambahkan',
                'fetch' => 'Tarik user role berhasil',
                'find' => 'Tarik user role berhasil',
                'update' => 'Data user berhasil diubah',
                'delete' => 'Data user berhasil dihapus',
            ],
            'module' => [
                'create' => 'Data modul berhasil ditambahkan',
                'fetch' => 'Tarik data modul berhasil',
                'find' => 'Tarik data modul berhasil',
                'update' => 'Data modul berhasil diubah',
                'delete' => 'Data modul berhasil dihapus',
            ],
            'menu' => [
                'create' => 'Data menu berhasil ditambahkan',
                'upload-icon' => 'Data icon menu berhasil diupload',
                'fetch' => 'Tarik data menu berhasil',
                'find' => 'Tarik data menu berhasil',
                'update' => 'Data menu berhasil diubah',
                'delete' => 'Data menu berhasil dihapus',
            ],
            'auth' => [
                'login' => 'Login berhasil',
                'refreshToken' => 'Refresh token berhasil',
                'fetch' => 'Data :attribute berhasil ditemukan',
                'resetPassword' => 'Reset password berhasil, Silahkan check email anda',
                'verifiedOtp' => 'Data :attribute berhasil diverifikasi',
                'changePassword' => 'Password berhasil diubah',
                'update' => 'Data :attribute berhasil diubah'
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
                'fetch' => 'Tarik data kecamatan berhasil',
                'find' => 'Tarik data kecamatan berhasil',
                'update' => 'Data kecamatan berhasil diubah',
                'delete' => 'Data kecamatan berhasil dihapus',
            ],
            'village' => [
                'create' => 'Data kelurahan berhasil ditambahkan',
                'fetch' => 'Tarik data kelurahan berhasil',
                'find' => 'Tarik data kelurahan berhasil',
                'update' => 'Data kelurahan berhasil diubah',
                'delete' => 'Data kelurahan berhasil dihapus',
            ],
            'grade' => [
                'create' => 'Data jenjang berhasil ditambahkan',
                'fetch' => 'Tarik data jenjang berhasil',
                'find' => 'Tarik data jenjang berhasil',
                'update' => 'Data jenjang berhasil diubah',
                'delete' => 'Data jenjang berhasil dihapus',
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
            'identity' => [
                'create' => 'Data identitas berhasil ditambahkan',
                'fetch' => 'Tarik data identitas berhasil',
                'find' => 'Tarik data identitas berhasil',
                'update' => 'Data identitas berhasil diubah',
                'delete' => 'Data identitas berhasil dihapus',
            ],
            'socialMedia' => [
                'create' => 'Data social media berhasil ditambahkan',
                'fetch' => 'Tarik data social media berhasil',
                'find' => 'Tarik data social media berhasil',
                'update' => 'Data social media berhasil diubah',
                'delete' => 'Data social media berhasil dihapus',
            ],
            "tipeGMD" => [
                "store" => "Data GMD berhasil ditambahkan",
                "update" => "Data GMD berhasil diubah",
                "delete" => "Data GMD berhasil dihapus"
            ],
            'organizeModule' => [
                "assign" => "Data modul berhasil ditambahkan ke organisasi",
                "update" => "Data access modul berhasil diubah",
                'delete' => 'Data modul berhasil dihapus dari organisasi',
                "fetchModul" => 'Tarik data akses modul berhasil'
            ]
        ],
        'error' => [
            'default' => [
                'required' => 'Data :attribute wajib diisi',
                'unique' => 'Data :attribute wajib unik',
                'exists' => 'Data :attribute sudah digunakan',
                'minCharacter' => 'Jumlah karakter :attribute minimal adalah :number karakter',
                'notFound' => 'Data :attribute tidak ditemukan',
                'existedRow' => 'Data :attribute duplikat. Data :attribute sudah terdaftar di sistem',
                'uploadImage' => 'Upload :attribute gagal',
                'existApproved' => ':attribute telah disetujui',
                'approved' => ':attribute gagal disetujui'
            ],
            'role' => [
                'create' => 'Data role gagal ditambahkan',
                'update' => 'Data role gagal diubah',
                'delete' => 'Data role gagal dihapus',
            ],
            'user' => [
                'create' => 'Data user gagal ditambahkan',
                'update' => 'Data user gagal diubah',
                'delete' => 'Data user gagal dihapus',
            ],
            'module' => [
                'create' => 'Data modul gagal ditambahkan',
                'update' => 'Data modul gagal diubah',
                'delete' => 'Data modul gagal dihapus',
            ],
            'menu' => [
                'create' => 'Data menu gagal ditambahkan',
                'upload-icon' => 'Data icon menu gagal diupload',
                'update' => 'Data menu gagal diubah',
                'delete' => 'Data menu gagal dihapus',
            ],
            'auth' => [
                'passwordInvalid' => 'Password yang diinput tidak sesuai',
                'tokenGenerate' => 'Generate akses token gagal',
                'tokenInvalid' => 'Token tidak sesuai atau habis masa berlakunya',
                'unauthorized' => 'Proses Otentikasi gagal',
                'tokenUnprovide' => 'Token tidak tersedia',
                "notApproved" => "Akun anda bulum disetujui. Silahkan hubungi customer support",
                "expiredAccount" => "Masa aktif berlangganan anda telah jatuh tempo. Silahkan perpanjang layanan",
                "storeReset" => "Data :attribute gagal disimpan",
                "expiredOtp" => "Data :attribute telah kadaluarsa. Masa aktif hanya :num menit",
                "hadVerified" => "Data :attribute sudah pernah diverifikasi",
                "verifiedOtp" => "Data :attribute gagal diverifikasi",
                "permissionCode" => "Akses ubah password ditolak",
                "update" => "Data :attribute gagal diubah"
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
            'grade' => [
                'create' => 'Data jenjang gagal ditambahkan',
                'update' => 'Data jenjang gagal diubah',
                'delete' => 'Data jenjang gagal dihapus',
            ],
            'identity' => [
                'create' => 'Data identitas gagal ditambahkan',
                'update' => 'Data identitas gagal diubah',
                'delete' => 'Data identitas gagal dihapus',
            ],
            'socialMedia' => [
                'create' => 'Data social media gagal ditambahkan',
                'update' => 'Data social media gagal diubah',
                'delete' => 'Data social media gagal dihapus',
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
            ],
            'organizeModule' => [
                "assign" => "Data modul gagal ditambahkan ke organisasi",
                "update" => "Data access modul gagal diubah",
                'delete' => 'Data modul gagal dihapus dari organisasi',
                'create' => ':attribute gagal ditambahkan'
            ]
        ],
    ],
];
