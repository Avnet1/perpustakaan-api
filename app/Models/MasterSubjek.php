<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSubjek extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_subjek';

    protected $primaryKey = 'subjek_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'kode',
        'tipe_subjek_id',
        'daftar_terkendali',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function tipeSubjek()
    {
        return $this->belongsTo(MasterTipeSubjek::class, 'tipe_subjek_id', 'tipe_subjek_id');
    }
}
