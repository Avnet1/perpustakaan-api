<?php

namespace App\Http\Modules\Superadmin\Menu;

use App\Models\MasterImageStorage;
use App\Models\MasterMenu;
use Illuminate\Support\Facades\DB;

class MenuRepository
{
    private $primaryKey;
    private $tableName;

    public function __construct()
    {
        $this->primaryKey = MasterMenu::getPrimaryKeyName();
        $this->tableName = MasterMenu::getTableName();
    }

    public function insert(mixed $payload)
    {
        return MasterMenu::store($payload);
    }

    public function update(string $id, mixed $payload)
    {
        $result = DB::table("{$this->tableName}")
            ->whereNull("deleted_at")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);

        if (!$result) {
            return $result;
        }

        return MasterMenu::where("{$this->primaryKey}", $id)->first();
    }

    public function findById(string $id)
    {
        return MasterMenu::with([
            'module',
            'childrens' => function ($q) {
                $q->with([
                    'childrens'
                ]);
            },
            'parent' => function ($q) {
                $q->whereNull('deleted_at');
            }
        ])->whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
    }


    public function checkExisted(string $id, array $condition)
    {
        $query = MasterMenu::whereNull('deleted_at');

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

        return $query->where("{$this->primaryKey}", '!=', $id)->first();
    }


    public function findByCondition(mixed $condition, bool $relation = false)
    {
        $query = MasterMenu::whereNull('deleted_at');

        if ($relation == true) {
            $query = MasterMenu::with([
                'module',
                'childrens' => function ($q) {
                    $q->with([
                        'childrens'
                    ]);
                },
                'parent' => function ($q) {
                    $q->whereNull('deleted_at');
                }
            ])->whereNull('deleted_at');
        }

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
            ->whereNull("deleted_at")
            ->where("{$this->primaryKey}", $id)
            ->update($payload);
    }
}
