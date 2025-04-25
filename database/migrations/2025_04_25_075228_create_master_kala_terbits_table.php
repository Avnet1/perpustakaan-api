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
        Schema::create('master_kala_terbit', function (Blueprint $table) {
            $table->uuid('kala_terbit_id')->primary();
            $table->string('nama', 255)->nullable()->default(null);
            $table->uuid('bahasa_id')->nullable()->default(null);
            $table->string('selang_waktu')->nullable()->default(null);
            $table->enum('satuan_waktu', ['day', 'week', 'month', 'year'])->nullable();
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('bahasa_id')
                ->references('bahasa_id')
                ->on('master_bahasa')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kala_terbit');
    }
};
