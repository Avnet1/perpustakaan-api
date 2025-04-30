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
        Schema::create('master_identitas', function (Blueprint $table) {
            $table->uuid('identitas_id')->primary();
            $table->string('nama_perusahaan')->nullable()->default(null);
            $table->string('kota')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('telepon')->nullable()->default(null);
            $table->text('website')->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->longText('footer')->nullable()->default(null);
            $table->longText('deskripsi')->nullable()->default(null);
            $table->longText('privacy_policy')->nullable()->default(null);
            $table->text('photo')->nullable()->default(null);
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
        Schema::dropIfExists('master_identitas');
    }
};
