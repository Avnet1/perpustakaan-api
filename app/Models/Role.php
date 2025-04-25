<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $primaryKey = 'role_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'role_name',
        'role_slug',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function listUser()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
