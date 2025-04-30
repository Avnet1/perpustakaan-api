<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterGmd extends Model
{
    use SoftDeletes;

    protected $table = 'master_tipe_gmd';

    protected $primaryKey = 'tipe_gmd_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    // Disables the updated_at field
    const UPDATED_AT = null;

    protected $fillable = [
        'tipe_gmd_id',
        'nama',
        'kode',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->tipe_gmd_id)) {
                $model->tipe_gmd_id = (string) Str::uuid();
            }
        });
    }
}
