<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_langganan', function (Blueprint $table) {
            $table->uuid('riwayat_id')->primary();
            $table->uuid('modul_access_id')->nullable()->default(null);
            $table->date('start_service')->nullable()->default(null);
            $table->date('end_service')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();


            $table->foreign('modul_access_id')
                ->references('modul_access_id')
                ->on('organization_module_access')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_langganan');
    }
};
