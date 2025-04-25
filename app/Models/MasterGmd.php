<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterGmd extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_gmd';

    protected $primaryKey = 'tipe_gmd_id';

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
