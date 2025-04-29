<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterKabupatenKota extends Model
{

    use SoftDeletes;

    protected $table = 'master_kabupaten_kota';
    protected $primaryKey = 'kabupaten_kota_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kabupaten_kota_id',
        'provinsi_id',
        'nama_kabupaten_kota',
        'status_administrasi',
        'kode_kabupaten_kota',
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

    public function listKecamatan()
    {
        return $this->hasMany(MasterKecamatan::class, 'kabupaten_kota_id', 'kabupaten_kota_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kabupaten_kota_id)) {
                $model->kabupaten_kota_id = (string) Str::uuid();
            }
        });
    }
}
