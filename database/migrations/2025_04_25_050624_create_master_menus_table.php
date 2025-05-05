<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_menu', function (Blueprint $table) {
            $table->uuid('menu_id')->primary();
            $table->uuid('modul_id')->nullable()->default(null);
            $table->string('nama_menu')->default(null);
            $table->text('slug')->nullable()->default(null);
            $table->longText('icon')->nullable()->default(null);
            $table->bigInteger('urutan')->nullable()->default(null);
            $table->uuid('parent_id')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('modul_id')
                ->references('modul_id')
                ->on('master_modul')
                ->onDelete('set null');
        });

        // Tambahkan foreign key parent_id setelah tabel dibuat
        Schema::table('master_menu', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('menu_id')
                ->on('master_menu')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('master_menu', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('master_menu');
    }
};
