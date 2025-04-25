<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MasterBahasaDokumen extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_bahasa';

    protected $primaryKey = 'bahasa_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listKalaTerbit()
    {
        return $this->hasMany(MasterKalaTerbit::class, 'bahasa_id', 'bahasa_id');
    }
}
