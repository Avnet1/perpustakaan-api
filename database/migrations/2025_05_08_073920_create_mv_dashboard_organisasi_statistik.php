<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE MATERIALIZED VIEW mv_dashboard_organisasi_statistik AS
            SELECT
                (SELECT COUNT(mm.modul_id) FROM master_modul mm WHERE mm.deleted_at IS NULL) AS total_modul,
                COUNT(mo.organisasi_id) as total_organisasi,
                (
                    CASE
                        WHEN NULLIF(SUM(CASE WHEN status IS TRUE THEN 1 ELSE 0 END), 0) IS NULL
                        THEN 0
                        ELSE NULLIF(SUM(CASE WHEN status IS TRUE THEN 1 ELSE 0 END), 0)
                    END
                ) as total_organisasi_aktif,
                (
                    CASE
                        WHEN COUNT(mo.organisasi_id) * 100.0 / NULLIF(SUM(CASE WHEN status IS TRUE THEN 1 ELSE 0 END), 0) IS NULL
                        THEN 0
                        ELSE COUNT(mo.organisasi_id) * 100.0 / NULLIF(SUM(CASE WHEN status IS TRUE THEN 1 ELSE 0 END), 0)
                    END
                ) AS persentasi_organisasi
            FROM master_organisasi mo
            WHERE deleted_at IS NULL;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_dashboard_organisasi_statistik");
    }
};
