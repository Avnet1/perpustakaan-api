<?php

namespace App\Http\Repositories;

use App\Models\MasterGmd;

class TipeGMDRepository
{
    public function insertData(mixed $payload) {
       return MasterGmd::create($payload);
    }

    public function updateData(string $id, mixed $payload) {
        $row = MasterGmd::find($id);
        if(!$row) {
            return $row;
        }
        return $row->update($payload);
     }

}

