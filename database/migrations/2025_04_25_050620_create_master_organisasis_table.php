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
            $table->string('kode_member')->nullable()->default(null);
            $table->string('nama_organisasi')->nullable()->default(null);
            $table->string('provinsi')->nullable()->default(null);
            $table->string('kabupaten_kota')->nullable()->default(null);
            $table->string('kecamatan')->nullable()->default(null);
            $table->string('kelurahan_desa')->nullable()->default(null);
            $table->string('kode_pos')->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->text('logo')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->boolean('is_approved')->nullable()->default(false);
            $table->timestamp('approved_at')->nullable()->default(null);
            $table->text('domain_admin_url')->nullable()->default(null);
            $table->text('domain_website_url')->nullable()->default(null);
            $table->boolean('status')->nullable()->default(false);
            $table->string('db_user')->default('admin');
            $table->string('db_pass')->default(null);
            $table->string('db_name')->default('db_master');
            $table->timestamps();
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
