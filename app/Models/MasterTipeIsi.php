<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTipeIsi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_isi';

    protected $primaryKey = 'tipe_isi_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'kode',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
