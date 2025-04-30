<?php

namespace App\Http\Modules\Superadmin\SocialMedia;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\MasterSocialMedia;
use Exception;
use Illuminate\Support\Facades\DB;

class SocialMediaService
{
    public static $primaryKey = 'social_media_id';
    protected $repository;

    public function __construct(SocialMediaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetch($where): LaravelResponseInterface
    {
        try {
            $results = MasterSocialMedia::whereNull('deleted_at')->where($where)->get();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.fetch'), $results);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function findById(string $id): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Social media']), $row);
            }

            return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.find'), $row);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function store(mixed $payload): LaravelResponseInterface
    {
        DB::beginTransaction();
        try {
            $row = $this->repository->findByCondition([
                'identitas_id' => $payload->identitas_id,
                'nama_sosmed' => $payload->nama_sosmed,
            ]);

            if ($row) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.default.exists', ['attribute' => "Nama sosmed ({$payload->nama_sosmed})"]), $row);
            }

            $result = $this->repository->insert((array) $payload);

            if (!$result) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 400, __('validation.custom.error.socialMedia.create'), $result);
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.create'), (object) [
                self::$primaryKey => $result[self::$primaryKey],
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->logo);
            return sendErrorResponse($e);
        }
    }

    public function update(string $id, mixed $payload): LaravelResponseInterface
    {
        $storageOldPath = null;
        $hasPhoto = true;
        DB::beginTransaction();
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                DB::rollBack();
                deleteFileInStorage($payload->logo);
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Social media']), $row);
            }

            if ($row->logo != null) {
                $storageOldPath = $row->logo;
            }

            if ($payload->logo == null) {
                $hasPhoto = false;
                unset($payload->logo);
            }

            $row->update((array) $payload);

            if ($hasPhoto == true) {
                deleteFileInStorage($storageOldPath);
            }

            DB::commit();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.update'), (object) [
                self::$primaryKey => $id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            deleteFileInStorage($payload->logo);
            return sendErrorResponse($e);
        }
    }

    public function delete(string $id, mixed $payload): LaravelResponseInterface
    {
        try {
            $row = $this->repository->findById($id);

            if (!$row) {
                return new LaravelResponseContract(false, 404, __('validation.custom.error.default.notFound', ['attribute' => 'Social media']), $row);
            }

            $this->repository->delete($id, (array) $payload);

            return new LaravelResponseContract(true, 200, __('validation.custom.success.socialMedia.delete'), (object) [
                self::$primaryKey => $id,
            ]);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
