<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTipeSubjek extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_tipe_subjek';

    protected $primaryKey = 'tipe_subjek_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listSubjek()
    {
        return $this->hasMany(MasterSubjek::class, 'tipe_subjek_id', 'tipe_subjek_id');
    }
}
