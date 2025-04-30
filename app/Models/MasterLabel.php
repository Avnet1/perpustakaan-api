<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class MasterLabel extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_label';

    protected $primaryKey = 'label_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    // Disables the updated_at field
    const UPDATED_AT = null;

    protected $fillable = [
        'label_id',
        'nama',
        'deskripsi',
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
            if (empty($model->label_id)) {
                $model->label_id = (string) Str::uuid();
            }
        });
    }
}
