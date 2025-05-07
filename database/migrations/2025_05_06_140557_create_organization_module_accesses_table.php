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
        Schema::create('organization_module_access', function (Blueprint $table) {
            $table->uuid('modul_access_id')->primary();
            $table->uuid('organisasi_id')->nullable()->default(null);
            $table->uuid('modul_id')->nullable()->default(null);
            $table->date('start_service')->nullable()->default(null);
            $table->date('end_service')->nullable()->default(null);
            $table->string('access_code')->nullable()->default(null);
            $table->boolean('is_active')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_module_access');
    }
};
