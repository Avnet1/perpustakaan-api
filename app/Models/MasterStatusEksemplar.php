<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterStatusEksemplar extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_status_eksemplar';

    protected $primaryKey = 'eksemplar_id';

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
