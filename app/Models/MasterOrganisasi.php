<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterOrganisasi extends Model
{

    use SoftDeletes;

    protected $table = 'master_organisasi';
    protected $primaryKey = 'organisasi_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'organisasi_id',
        'universitas_id',
        'provinsi_id',
        'kabupaten_kota_id',
        'kecamatan_id',
        'kelurahan_id',
        'postal_code',
        'email',
        'address',
        'logo',
        'domain_admin_url',
        'domain_website_url',
        'license_code',
        'end_active_at',
        'db_user',
        'db_pass',
        'db_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'organisasi_id', 'organisasi_id');
    }

    public function universitas()
    {
        return $this->belongsTo(MasterUniversitas::class, 'universitas_id', 'universitas_id');
    }

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

    public function kelurahan()
    {
        return $this->belongsTo(MasterKelurahan::class, 'kelurahan_id', 'kelurahan_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->organisasi_id)) {
                $model->organisasi_id = (string) Str::uuid();
            }
        });
    }
}
