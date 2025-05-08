<?php

namespace App\Http\Modules\Superadmin\Histories;

use App\Models\RiwayatLangganan;

class HistoryRepository
{

    public function getRiwayanLangganan(mixed $filters): object
    {
        $sqlQuery = RiwayatLangganan::whereNull('deleted_at');

        if (isset($filters->query['modul_access_id'])) {
            $sqlQuery->where('modul_access_id', $filters->query['modul_access_id']);
        }

        if ($filters?->paging?->search) {
            $search = $filters->paging->search;
            $sqlQuery->where(function ($builder) use ($search) {
                $builder
                    ->where("start_service", '=', $search)
                    ->orWhere("end_service", '=', $search);
            });
        }

        foreach ($filters->sorting as $column => $order) {
            $sqlQuery->orderBy($column, $order);
        }

        $sqlQueryCount = $sqlQuery;
        $sqlQueryRows = $sqlQuery;

        $totalRows = $sqlQueryCount->count();
        $rows =  $sqlQueryRows
            ->skip($filters->paging->skip)
            ->take($filters->paging->limit)
            ->get();

        return (object) [
            "total_rows" => $totalRows,
            "rows" => $rows
        ];
    }
}
