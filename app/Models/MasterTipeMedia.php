<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTipeMedia extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_media';

    protected $primaryKey = 'tipe_media_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'kode',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
