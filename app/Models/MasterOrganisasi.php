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
        'kode_member',
        'nama_organisasi',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan_desa',
        'kode_pos',
        'email',
        'alamat',
        'logo',
        'domain_admin_url',
        'domain_website_url',
        'db_user',
        'db_pass',
        'db_name',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'password',
        'deleted_at',
        'deleted_by',
    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }

    public static function getPrimaryKeyName()
    {
        return (new self())->getKeyName();
    }

    public static function store(array $payload)
    {
        $pkString = self::getPrimaryKeyName();
        $uuidString = (string) Str::uuid();

        $data = array_merge([
            "{$pkString}" => $uuidString,
            'created_at' => now(),
            'updated_at' => null,
        ], $payload);

        // Perform the insert operation
        $inserted = self::insert($data);
        if ($inserted) {
            return self::with(['role'])->where($pkString, $uuidString)->first();
        }

        return false;
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
