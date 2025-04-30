<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterKelurahan extends Model
{
    use SoftDeletes;

    protected $table = 'master_kelurahan';
    protected $primaryKey = 'kelurahan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    // Disables the updated_at field
    const UPDATED_AT = null;

    protected $fillable = [
        'kelurahan_id',
        'provinsi_id',
        'kabupaten_kota_id',
        'kecamatan_id',
        'nama_kelurahan',
        'kode_kelurahan',
        'kode_dikti',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];


    public function provinsi()
    {
        return $this->belongsTo(MasterProvinsi::class, 'provinsi_id', 'provinsi_id');
    }

    public function kabupatenKota()
    {
        return $this->belongsTo(MasterKabupatenKota::class, 'kabupaten_kota_id', 'kabupaten_kota_id');
    }


    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'kecamatan_id', 'kecamatan_id');
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kelurahan_id)) {
                $model->kelurahan_id = (string) Str::uuid();
            }
        });
    }
}
