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
        Schema::create('master_anggota', function (Blueprint $table) {
            $table->uuid('anggota_id')->primary();
            $table->uuid('user_id')->nullable()->default(null);
            $table->string('kode_anggota', 255)->nullable()->default(null);
            $table->string('nama', 255)->nullable()->default(null);
            $table->date('tanggal_lahir')->nullable()->default(null);
            $table->date('tanggal_bergabung')->nullable()->default(null);
            $table->date('tanggal_berlaku')->nullable()->default(null);
            $table->text('nama_institusi')->nullable()->default(null);
            $table->uuid('fakultas_id')->nullable()->default(null);
            $table->uuid('prodi_id')->nullable()->default(null);
            $table->uuid('tipe_anggota_id')->nullable()->default(null);
            $table->string('gender', 255)->nullable()->default(null);
            $table->text('alamat')->nullable()->default(null);
            $table->string('kode_pos', 255)->nullable()->default(null);
            $table->text('alamat_surat')->nullable()->default(null);
            $table->string('no_telepon', 100)->nullable()->default(null);
            $table->string('no_fax', 100)->nullable()->default(null);
            $table->string('no_identitas', 100)->nullable()->default(null);
            $table->text('catatan')->nullable()->default(null);
            $table->boolean('tunda_keanggotaan')->nullable()->default(false);
            $table->longText('photo')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('tipe_anggota_id')
                ->references('tipe_anggota_id')
                ->on('master_tipe_keanggotaan')
                ->onDelete('set null');

            $table->foreign('prodi_id')
                ->references('prodi_id')
                ->on('master_program_studi')
                ->onDelete('set null');

                $table->foreign('fakultas_id')
                ->references('fakultas_id')
                ->on('master_fakultas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_anggotas');
    }
};
