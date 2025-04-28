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
        Schema::create('master_universitas', function (Blueprint $table) {
            $table->uuid('universitas_id')->primary();
            $table->uuid('jenis_universitas_id')->nullable()->default(null);
            $table->string('nama_universitas')->nullable()->default(null);
            $table->string('singkatan', 150)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('jenis_universitas_id')
                ->references('jenis_universitas_id')
                ->on('master_jenis_universitas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_universitas');
    }
};
