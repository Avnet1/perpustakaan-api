<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MasterLabel extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_label';

    protected $primaryKey = 'label_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
