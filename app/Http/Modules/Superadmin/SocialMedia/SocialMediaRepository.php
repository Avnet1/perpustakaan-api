<?php

namespace App\Http\Modules\Superadmin\SocialMedia;

use App\Models\MasterSocialMedia;
use Illuminate\Support\Facades\DB;

class SocialMediaRepository
{
    public static $primaryKey = 'social_media_id';
    public static $tableName = 'master_social_media';

    public function insert(mixed $payload)
    {
        return MasterSocialMedia::create($payload);
    }

    public function findById(string $id)
    {
        return MasterSocialMedia::whereNull('deleted_at')->where(self::$primaryKey, $id)->first();
    }


    public function checkExisted(string $id, array $where)
    {
        return MasterSocialMedia::whereNull('deleted_at')
            ->where($where)
            ->where(self::$primaryKey, '!=', $id)->first();
    }

    public function findByCondition(mixed $condition)
    {
        $query = MasterSocialMedia::whereNull('deleted_at');

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
        DB::table(self::$tableName)
            ->where(self::$primaryKey, $id)
            ->update($payload);
    }
}
