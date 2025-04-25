<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPeladenSalinKatalog extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_peladen_salin_katalog';

    protected $primaryKey = 'peladen_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'url',
        'jenis_peladen',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Jenis Peladen
     * 1. MARC SRU Server
     * 2. P2P Server
     * 3. Z3950 Server
     * 4. Z3950 SRU Server
     */
}
