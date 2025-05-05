<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterImageStorage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_image_storages';

    protected $primaryKey = 'image_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    protected $fillable = [
        'image_id',
        'image_path',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $hidden = [
        'deleted_at',
        'deleted_by'
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->image_id)) {
                $model->image_id = (string) Str::uuid();
            }
        });
    }
}
