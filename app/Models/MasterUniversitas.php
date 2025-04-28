<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class MasterUniversitas extends Model
{

    use SoftDeletes;

    protected $table = 'master_universitas';
    protected $primaryKey = 'universitas_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'universitas_id',
        'jenis_universitas_id',
        'nama_universitas',
        'singkatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function jenisUniversitas()
    {
        return $this->belongsTo(MasterJenisUniversitas::class, 'jenis_universitas_id', 'jenis_universitas_id');
    }

    public function listOrganisasi()
    {
        return $this->hasMany(MasterOrganisasi::class, 'organisasi_id', 'organisasi_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->universitas_id)) {
                $model->universitas_id = (string) Str::uuid();
            }
        });
    }
}
