<?php

namespace App\Http\Modules\Superadmin\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    /** Fetch Client (Pagination) */
    public function fetchTabInfo(Request $request): JsonResponse
    {
        $result = $this->service->fetchTabInfo();
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }

    public function fetchListOrganization(Request $request): JsonResponse
    {
        $result = $this->service->fetchListOrganization();
        return ResponseHelper::sendResponseJson($result->success, $result->code, $result->message, $result->data);
    }
}
