<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTempat extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_tempat';

    protected $primaryKey = 'tempat_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
