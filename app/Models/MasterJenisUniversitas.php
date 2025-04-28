<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MasterJenisUniversitas extends Model
{

    use SoftDeletes;

    protected $table = 'master_jenis_universitas';
    protected $primaryKey = 'jenis_universitas_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'jenis_universitas_id',
        'nama_jenis',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listUniversitas()
    {
        return $this->hasMany(User::class, 'jenis_universitas_id', 'jenis_universitas_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->jenis_universitas_id)) {
                $model->jenis_universitas_id = (string) Str::uuid();
            }
        });
    }
}
