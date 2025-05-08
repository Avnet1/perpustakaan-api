<?php

namespace App\Http\Modules\Superadmin\Dashboard;

use App\Models\MasterOrganisasi;
use App\Models\RiwayatLangganan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{

    public function fetchTabInfo()
    {
        DB::statement("REFRESH MATERIALIZED VIEW mv_dashboard_organisasi_statistik");
        return DB::select("SELECT total_modul, total_organisasi, total_organisasi_aktif, cast(persentasi_organisasi as int) as persentasi_organisasi FROM mv_dashboard_organisasi_statistik");
    }

    public function fetchListOrganization()
    {
        $url = asset('storage');
        $result = MasterOrganisasi::whereNull('deleted_at')
            ->selectRaw("organisasi_id, kode_member, nama_organisasi, created_at, updated_at, (case when logo is null then null else CONCAT('$url/', logo) end) as logo")
            ->whereDate('created_at', Carbon::today())
            ->take(10)->get();
        return $result;
    }
}
