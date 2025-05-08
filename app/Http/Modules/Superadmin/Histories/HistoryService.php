<?php

namespace App\Http\Modules\Superadmin\Histories;

use App\Helpers\ImageStorageHelper;
use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Models\RiwayatLangganan;
use App\Services\RabbitMQPublisherService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class HistoryService
{
    protected $repository;

    public function __construct(HistoryRepository $repository)
    {
        $this->repository = $repository;
    }


    public function fetchRiwayatLangganan(mixed $filters): LaravelResponseInterface
    {
        try {

            $result = $this->repository->getRiwayanLangganan($filters);
            $response = setPagination($result->rows, (int) $result->total_rows ?? 0, (int) $filters->paging->page, (int) $filters->paging->limit);
            return new LaravelResponseContract(true, 200, __('validation.custom.success.histories.fetchHistoryClient'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
