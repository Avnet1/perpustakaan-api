<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_menu';

    protected $primaryKey = 'menu_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Enables timestamps for the model
    public $timestamps = true;

    protected $fillable = [
        'menu_id',
        'modul_id',
        'nama_menu',
        'slug',
        'icon',
        'urutan',
        'parent_id',
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
            if (empty($model->menu_id)) {
                $model->menu_id = (string) Str::uuid();
            }
        });
    }


    public function module()
    {
        return $this->belongsTo(MasterModule::class, 'modul_id', 'modul_id');
    }

    public function parent()
    {
        return $this->belongsTo(MasterMenu::class, 'parent_id', 'menu_id');
    }

    // Relationship: child menus
    public function childrens()
    {
        $url = asset('storage');
        return $this->hasMany(MasterMenu::class, 'parent_id', 'menu_id')->with([
            'childrens' => function ($q) use ($url) {
                $q->with(['childrens'])->selectRaw("*, (case when icon is null then null else CONCAT('$url/', icon) end) as icon")->orderBy('urutan', 'asc');
            }
        ]);
    }
}
