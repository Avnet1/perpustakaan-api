<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterAgen extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_agen';

    protected $primaryKey = 'agen_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'alamat',
        'kontak',
        'no_telepon',
        'no_fax',
        'no_akun',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
