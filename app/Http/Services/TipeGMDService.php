<?php

namespace App\Http\Services;

use App\Http\Contracts\LaravelResponseContract;
use App\Http\Interfaces\LaravelResponseInterface;
use App\Http\Repositories\TipeGMDRepository;
use Illuminate\Http\Request;

class TipeGMDService
{
    protected $repository;

    public function __construct(TipeGMDRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(mixed $payload):LaravelResponseInterface {
        $result = $this->repository->insertData($payload);

        if(!$result) {
            return new LaravelResponseContract(false, 400, __('validation.error.tipeGMD.store'), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.success.tipeGMD.store') ,[
            "id" =>  $result->gmd_id
        ]);
    }


    public function update(string $id, mixed $payload):LaravelResponseInterface {
        $result = $this->repository->updateData($id, $payload);

        if(!$result) {
            return new LaravelResponseContract(false, 400, __('validation.error.tipeGMD.update'), $result);
        }

        return new LaravelResponseContract(true, 200, __('validation.success.tipeGMD.update') ,[
            "id" =>  $result->gmd_id
        ]);
    }
}
