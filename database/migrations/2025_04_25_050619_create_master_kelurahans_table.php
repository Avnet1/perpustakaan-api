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
        Schema::create('master_kelurahan', function (Blueprint $table) {
            $table->uuid('kelurahan_id')->primary();
            $table->uuid('provinsi_id')->nullable()->default(null);
            $table->uuid('kabupaten_kota_id')->nullable()->default(null);
            $table->uuid('kecamatan_id')->nullable()->default(null);
            $table->string('nama_kelurahan')->nullable()->default(null);
            $table->string('kode_kelurahan')->nullable()->default(null);
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


            $table->foreign('kabupaten_kota_id')
                ->references('kabupaten_kota_id')
                ->on('master_kabupaten_kota')
                ->onDelete('set null');

            $table->foreign('kecamatan_id')
                ->references('kecamatan_id')
                ->on('master_kecamatan')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kelurahan');
    }
};
