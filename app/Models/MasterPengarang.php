<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPengarang extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'master_pengarang';

    protected $primaryKey = 'pengarang_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'tahun_lahir',
        'daftar_terkendali',
        'jenis_pengarang',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function jenisPengarang()
    {
        return $this->belongsTo(MasterJenisPengarang::class, 'jenis_pengarang_id', 'jenis_pengarang_id');
    }
}
