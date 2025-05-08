<?php

namespace App\Http\Modules\Superadmin\Dashboard;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use Exception;

class DashboardService
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }


    public function fetchTabInfo(): LaravelResponseInterface
    {
        try {
            $response = $this->repository->fetchTabInfo();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.dashboard.fetchDashboardInfo'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }

    public function fetchListOrganization(): LaravelResponseInterface
    {
        try {
            $response = $this->repository->fetchListOrganization();
            return new LaravelResponseContract(true, 200, __('validation.custom.success.dashboard.fetchListOrganization'), $response);
        } catch (Exception $e) {
            return sendErrorResponse($e);
        }
    }
}
