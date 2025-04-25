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
        Schema::create('master_tipe_keanggotaan', function (Blueprint $table) {
            $table->uuid('tipe_anggota_id')->primary();
            $table->string('nama', 255)->nullable()->default(null);
            $table->bigInteger('total_pinjaman')->nullable()->default(null);
            $table->bigInteger('lama_pinjaman')->nullable()->default(null);
            $table->string('status_reservasi')->nullable()->default(null);
            $table->bigInteger('total_reservasi')->nullable()->default(null);
            $table->bigInteger('masa_keanggotaan')->nullable()->default(null);
            $table->bigInteger('kali_perpanjangan')->nullable()->default(null);
            $table->decimal('denda', 20, 2)->nullable()->default(null);
            $table->bigInteger('total_toleransi')->nullable()->default(null);
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
        Schema::dropIfExists('master_tipe_keanggotaan');
    }
};
