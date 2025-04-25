<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTipeKeanggotaan extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_keanggotaan';

    protected $primaryKey = 'tipe_anggota_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'total_pinjaman',
        'lama_pinjaman',
        'status_reservasi',
        'total_reservasi',
        'masa_keanggotaan',
        'kali_perpanjangan',
        'denda',
        'total_toleransi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
