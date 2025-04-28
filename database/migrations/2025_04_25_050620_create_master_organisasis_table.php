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
        Schema::create('master_organisasi', function (Blueprint $table) {
            $table->uuid('organisasi_id')->primary();
            $table->uuid('universitas_id')->nullable()->default(null);
            $table->uuid('provinsi_id')->nullable()->default(null);
            $table->uuid('kabupaten_kota_id')->nullable()->default(null);
            $table->uuid('kecamatan_id')->nullable()->default(null);
            $table->uuid('kelurahan_id')->nullable()->default(null);
            $table->string('postal_code')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->text('logo')->nullable()->default(null);
            $table->text('domain_admin_url')->nullable()->default(null);
            $table->text('domain_website_url')->nullable()->default(null);
            $table->string('license_code')->default(null);
            $table->date('end_active_at')->nullable()->default(null);
            $table->string('db_user')->default('admin');
            $table->string('db_pass')->default(null);
            $table->string('db_name')->default('db_master');
            $table->timestamps();

            $table->foreign('universitas_id')
                ->references('universitas_id')
                ->on('master_universitas')
                ->onDelete('set null');

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

            $table->foreign('kelurahan_id')
                ->references('kelurahan_id')
                ->on('master_kelurahan')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_organisasi');
    }
};
