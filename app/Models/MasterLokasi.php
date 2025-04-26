<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->lokasi_id)) {
                $model->lokasi_id = (string) Str::uuid();
            }
        });
    }
}
