<?php

namespace App\Http\Modules\Superadmin\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = User::getPrimaryKeyName();
        $this->tableName = User::getTableName();
    }

    public function insert(mixed $payload)
    {
        return User::store($payload);
    }

    public function update(string $id, mixed $payload)
    {
        $result =  DB::table("{$this->tableName}")
            ->whereNull('deleted_at')
            ->where("{$this->primaryKey}", $id)
            ->update($payload);

        if (!$result) {
            return $result;
        }

        return User::with(['role'])->where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {
        return User::with(['role'])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }


    public function checkExisted(string $id, array $where)
    {
        return User::with(['role'])->whereNull('deleted_at')
            ->where($where)
            ->where("{$this->primaryKey}", '!=', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = User::with(['role'])->whereNull('deleted_at');

        foreach ($condition as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } elseif (is_null($value)) {
                $query->whereNull($key);
            } elseif (strpos($value, '%') !== false) {
                $query->where($key, 'ilike', $value);
            } else {
                $query->where($key, '=', $value);
            }
        }

        return $query->first();
    }

    public function delete(string $id, array $payload)
    {
        DB::table("{$this->tableName}")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }
}
