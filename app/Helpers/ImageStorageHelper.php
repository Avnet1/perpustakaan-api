<?php

namespace App\Helpers;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterImageStorage;

class ImageStorageHelper
{


    public static function storeImage(array $payload, string $typeName = 'icon'): LaravelResponseInterface
    {
        $primaryKey = MasterImageStorage::getPrimaryKeyName();
        $result = MasterImageStorage::store($payload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.uploadImage', ["attribute" => "file {$typeName}"]), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.default.uploadImage', ["attribute" => "file {$typeName}"]), (object) [
            "{$primaryKey}" => $result["{$primaryKey}"],
            "{$typeName}" => getFileUrl($result->image_path),
        ]);
    }

    public static function getImage(string $id, string $typeName = 'icon'): LaravelResponseInterface
    {
        $primaryKey = MasterImageStorage::getPrimaryKeyName();
        $result = MasterImageStorage::whereNull('deleted_at')->where("{$primaryKey}", $id)->first();
        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ["attribute" => "Image ID ({$id})"]), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.default.findImage', ["attribute" => "file {$typeName}"]), $result);
    }
}
