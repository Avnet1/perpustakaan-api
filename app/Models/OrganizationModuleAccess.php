<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrganizationModuleAccess extends Model
{

    use SoftDeletes;

    protected $table = 'organization_module_access';
    protected $primaryKey = 'modul_access_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'organisasi_id',
        'modul_id',
        'start_service',
        'end_service',
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
            'created_at' => now(),
            'updated_at' => null,
        ], $payload);

        // Perform the insert operation
        $inserted = self::insert($data);
        if ($inserted) {
            return self::where($pkString, $uuidString)->first();
        }

        return false;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->modul_access_id)) {
                $model->modul_access_id = (string) Str::uuid();
            }
        });
    }
}
