<?php

return [
    "success"=> [
        "default" => [],
        "auth" => [
            "refreshToken" => "Refresh token berhasil",
            "login" => "Login berhasil"
        ],
        "tipeGMD" => [
            "store" => "Data GMD berhasil ditambahkan",
            "update" => "Data GMD berhasil diubah",
            "delete" => "Data GMD berhasil dihapus"
        ]
    ],
    "error" =>  [
        "default" => [
            "required" => "Data :attribute wajib diisi",
            "email" => "Data :attribute wajib diisi dengan email yang benar",
            "max" => [
                "string" => "Data :attribute tidak boleh lebih besar dari :max karakter"
            ],
            "notFound" => "Data :attribute tidak ditemukan",
            "existed" => "Data :attribute sudah tersedia"

        ],
        "auth" => [
            "invalidPassword" => "Password yang diinput tidak sesuai",
            "generateToken" => "Generate akses token gagal",
            "noProvideToken" => "Token tidak tersedia",
            "invalidToken" => "Akses token tidak sesuai atau sudah tidak aktif",
            "unauthorized" => "Unauthorized",
        ],
        "tipeGMD" => [
            "store" => "Data GMD gagal ditambahkan",
            "update" => "Data GMD gagal diubah",
            "delete" => "Data GMD gagal dihapus"
        ]
    ]
];
