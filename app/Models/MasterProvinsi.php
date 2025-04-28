<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterProvinsi extends Model
{

    use SoftDeletes;

    protected $table = 'master_provinsi';
    protected $primaryKey = 'provinsi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'provinsi_id',
        'nama_provinsi',
        'kode_provinsi',
        'kode_dikti',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listKabupatenKota()
    {
        return $this->hasMany(MasterKabupatenKota::class, 'provinsi_id', 'provinsi_id');
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->provinsi_id)) {
                $model->provinsi_id = (string) Str::uuid();
            }
        });
    }
}
