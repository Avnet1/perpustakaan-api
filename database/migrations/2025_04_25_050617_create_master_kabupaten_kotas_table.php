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
        Schema::create('master_kabupaten_kota', function (Blueprint $table) {
            $table->uuid('kabupaten_kota_id')->primary();
            $table->uuid('provinsi_id')->nullable()->default(null);
            $table->string('nama_kabupaten_kota')->nullable()->default(null);
            $table->string('status_administrasi')->nullable()->default('kota');
            $table->string('kode_kabupaten_kota')->nullable()->default(null);
            $table->string('kode_dikti')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();


            $table->foreign('provinsi_id')
                ->references('provinsi_id')
                ->on('master_provinsi')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kabupaten_kota');
    }
};
