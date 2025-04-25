<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRujukanSilang extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_rujukan_silang';

    protected $primaryKey = 'rujukan_silang_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'penjelasan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
