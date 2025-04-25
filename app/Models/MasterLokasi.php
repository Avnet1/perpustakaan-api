<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLokasi extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_lokasi';

    protected $primaryKey = 'lokasi_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
