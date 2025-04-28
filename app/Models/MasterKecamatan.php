<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterKecamatan extends Model
{

    use SoftDeletes;

    protected $table = 'master_kecamatan';
    protected $primaryKey = 'kecamatan_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kecamatan_id',
        'kabupaten_kota_id',
        'nama_kecamatan',
        'kode_kecamatan',
        'kode_dikti',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function kabupatenKota()
    {
        return $this->belongsTo(MasterKabupatenKota::class, 'kabupaten_kota_id', 'kabupaten_kota_id');
    }

    public function provinsi()
    {
        return $this->hasOneThrough(
            MasterProvinsi::class, // model tujuan akhirnya
            MasterKabupatenKota::class,     // model perantaranya
            'kabupaten_kota_id',
            'provinsi_id',
            'kabupaten_kota_id',
            'provinsi_id'
        );
    }

    public function listKelurahan()
    {
        return $this->hasMany(MasterKelurahan::class, 'kecamatan_id', 'kecamatan_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kecamatan_id)) {
                $model->kecamatan_id = (string) Str::uuid();
            }
        });
    }
}
