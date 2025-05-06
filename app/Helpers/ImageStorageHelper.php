<?php

namespace App\Helpers;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterImageStorage;

class ImageStorageHelper
{

    private $primaryKey;

    public function __construct()
    {
        $this->primaryKey = MasterImageStorage::getPrimaryKeyName();
    }


    public static function storeImage(array $payload, string $typeName = 'icon'): LaravelResponseInterface
    {
        $result = MasterImageStorage::store($payload);

        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.uploadImage', ["attribute" => "file {$typeName}"]), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.default.uploadImage', ["attribute" => "file {$typeName}"]), (object) [
            "{$this->primaryKey}" => $result["{$this->primaryKey}"]
        ]);
    }

    public static function getImage(string $id, string $typeName = 'icon'): LaravelResponseInterface
    {
        $result = MasterImageStorage::whereNull('deleted_at')->where("{$this->primaryKey}", $id)->first();
        if (!$result) {
            return new LaravelResponseContract(false, 400, __('validation.custom.error.default.notFound', ["attribute" => "Image ID ({$id})"]), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.custom.success.default.findImage', ["attribute" => "file {$typeName}"]), $result);
    }
}
