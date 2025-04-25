<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKalaTerbit extends Model
{


    use HasFactory, SoftDeletes;

    protected $table = 'master_kala_terbit';

    protected $primaryKey = 'kala_terbit_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'selang_waktu',
        'satuan_waktu',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function bahasa()
    {
        return $this->belongsTo(MasterBahasaDokumen::class, 'bahasa_id', 'bahasa_id');
    }
}
