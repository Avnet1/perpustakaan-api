<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterJenjang extends Model
{

    use SoftDeletes;

    protected $table = 'master_jenjang';
    protected $primaryKey = 'jenjang_id';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'jenjang_id',
        'nama_jenjang',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->jenjang_id)) {
                $model->jenjang_id = (string) Str::uuid();
            }
        });
    }
}
