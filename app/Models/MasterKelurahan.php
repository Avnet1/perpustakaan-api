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

    protected $fillable = [
        'kelurahan_id',
        'kecamatan_id',
        'nama_kelurahan',
        'kode_kelurahan',
        'kode_dikti',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'kecamatan_id', 'kecamatan_id');
    }

    public function kabupatenKota()
    {
        return $this->hasOneThrough(
            MasterKabupatenKota::class, // model tujuan akhirnya
            MasterKecamatan::class,     // model perantaranya
            'kecamatan_id',             // foreign key di MasterKecamatan yang menunjuk ke MasterKelurahan
            'kabupaten_kota_id',        // foreign key di MasterKabupatenKota yang menunjuk ke MasterKecamatan
            'kecamatan_id',             // local key di MasterKelurahan yang menunjuk ke MasterKecamatan
            'kabupaten_kota_id'
        );
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
