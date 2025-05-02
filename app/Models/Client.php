<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Client extends Model
{

    use SoftDeletes;

    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'user_client_id',
        'client_code',
        'client_name',
        'client_job',
        'client_phone',
        'client_address',
        'client_photo',
        'client_email',
        'organisasi_id',
        'created_by',
        'updated_by',
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

    public function user_client()
    {
        return $this->belongsTo(UserClient::class, 'user_client_id', 'user_client_id');
    }

    public function organisasi()
    {
        return $this->belongsTo(MasterOrganisasi::class, 'organisasi_id', 'organisasi_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->client_id)) {
                $model->client_id = (string) Str::uuid();
            }
        });
    }
}
