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

    // Enables timestamps for the model
    public $timestamps = true;


    protected $fillable = [
        'kecamatan_id',
        'provinsi_id',
        'kabupaten_kota_id',
        'nama_kecamatan',
        'kode_kecamatan',
        'kode_dikti',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
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
            "created_at" => now(),
            "updated_at" => null,
        ], $payload);

        // Perform the insert operation
        $inserted = self::insert($data);
        if ($inserted) {
            return self::where($pkString, $uuidString)->first();
        }

        return false;
    }

    public function kabupatenKota()
    {
        return $this->belongsTo(MasterKabupatenKota::class, 'kabupaten_kota_id', 'kabupaten_kota_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(MasterProvinsi::class, 'provinsi_id', 'provinsi_id');
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
