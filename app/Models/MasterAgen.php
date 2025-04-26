<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterAgen extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_agen';

    protected $primaryKey = 'agen_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'agen_id',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->agen_id)) {
                $model->agen_id = (string) Str::uuid();
            }
        });
    }
}
