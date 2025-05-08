<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_modul';

    protected $primaryKey = 'modul_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    protected $fillable = [
        'modul_id',
        'nama_modul',
        'slug',
        'icon',
        'urutan',
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
            if (empty($model->modul_id)) {
                $model->modul_id = (string) Str::uuid();
            }
        });
    }


    public function scopeWithFormattedIcon($query, $url)
    {
        return $query->selectRaw("*, CASE WHEN icon IS NULL THEN NULL ELSE CONCAT('$url/', icon) END AS icon");
    }


    public function listMenu()
    {
        $url = asset('storage');

        return $this->hasMany(MasterMenu::class, 'modul_id', 'modul_id')
            ->selectRaw("*, CASE WHEN icon IS NULL THEN NULL ELSE CONCAT('$url/', icon) END AS icon");
    }


    public function moduleAccesses()
    {
        return $this->hasMany(OrganizationModuleAccess::class, 'modul_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(MasterOrganisasi::class, 'organization_module_access', 'modul_id', 'organisasi_id')
            ->withPivot(['start_service', 'end_service', 'access_code', 'is_active', 'access_code'])
            ->withTimestamps()
            ->using(OrganizationModuleAccess::class);
    }
}
