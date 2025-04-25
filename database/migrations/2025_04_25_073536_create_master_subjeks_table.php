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
        Schema::create('master_subjek', function (Blueprint $table) {
            $table->uuid('subjek_id')->primary();
            $table->string('nama', 255)->nullable()->default(null);
            $table->string('kode', 255)->nullable()->default(null);
            $table->uuid('tipe_subjek_id')->nullable()->default(null);
            $table->string('daftar_terkendali', 255)->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('tipe_subjek_id')
            ->references('tipe_subjek_id')
            ->on('master_tipe_subjek')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_subjek');
    }
};
