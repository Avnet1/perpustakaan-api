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
            $table->string('db_user')->nullable()->default(null);
            $table->string('db_pass')->nullable()->default(null);
            $table->string('db_name')->nullable()->default(null);
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
        Schema::dropIfExists('master_organisasi');
    }
};
