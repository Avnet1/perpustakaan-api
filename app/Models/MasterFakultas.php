<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterFakultas extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_fakultas';

    protected $primaryKey = 'fakultas_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama_fakultas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listPengarang()
    {
        return $this->hasMany(MasterPengarang::class, 'jenis_pengarang_id', 'jenis_pengarang_id');
    }
}
