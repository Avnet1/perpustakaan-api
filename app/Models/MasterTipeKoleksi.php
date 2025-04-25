<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTipeKoleksi extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_koleksi';

    protected $primaryKey = 'tipe_koleksi_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
