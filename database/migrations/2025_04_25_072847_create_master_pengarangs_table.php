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
        Schema::create('master_pengarang', function (Blueprint $table) {
            $table->uuid('pengarang_id')->primary();
            $table->string('nama', 255)->nullable()->default(null);
            $table->bigInteger('tahun_lahir')->nullable()->default(null);
            $table->string('daftar_terkendali', 255)->nullable()->default(null);
            $table->uuid('jenis_pengarang_id')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('jenis_pengarang_id')
                ->references('jenis_pengarang_id')
                ->on('master_jenis_pengarang')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pengarang');
    }
};
