<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterSocialMedia extends Model
{

    use SoftDeletes;

    protected $table = 'master_social_media';
    protected $primaryKey = 'social_media_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    // Disables the updated_at field
    const UPDATED_AT = null;


    protected $fillable = [
        'social_media_id',
        'identitas_id',
        'nama_sosmed',
        'link_sosmed',
        'logo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];

    public function identitas()
    {
        return $this->belongsTo(MasterIdentitas::class, 'identitas_id', 'identitas_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->social_media_id)) {
                $model->social_media_id = (string) Str::uuid();
            }
        });
    }
}
