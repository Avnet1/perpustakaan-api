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
        Schema::create('master_agen', function (Blueprint $table) {
            $table->uuid('agen_id')->primary();
            $table->string('nama', 255)->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->string('kontak', 150)->nullable()->default(null);
            $table->string('no_telepon', 150)->nullable()->default(null);
            $table->string('no_fax', 150)->nullable()->default(null);
            $table->string('no_akun', 150)->nullable()->default(null);
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
        Schema::dropIfExists('master_agen');
    }
};
