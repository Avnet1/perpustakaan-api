<?php

namespace App\Http\Modules\Superadmin\Histories;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;

class HistoryController extends Controller
{

    protected $service;

    public function __construct(HistoryService $service)
    {
        $this->service = $service;
    }

    /** Fetch Client (Pagination) */
    public function fetchRiwayatLangganan(Request $request): JsonResponse
    {
        $filters = (object) [
            "paging" => defineRequestPaginateArgs($request),
            "sorting" => defineRequestOrder(
                $request,
                [
                    'created_at',
                    'DESC'
                ],
                [
                    'start_service' => 'start_service',
                    'end_service' => 'end_service'
                ]
            ),
            'query' => $request->all()
        ];

        $result = $this->service->fetchRiwayatLangganan($filters);
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
