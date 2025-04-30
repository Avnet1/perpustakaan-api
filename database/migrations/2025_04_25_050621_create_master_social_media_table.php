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
        Schema::create('master_social_media', function (Blueprint $table) {
            $table->uuid('social_media_id')->primary();
            $table->uuid('identitas_id')->nullable()->default(null);
            $table->string('nama_sosmed')->nullable()->default(null);
            $table->text('link_sosmed')->nullable()->default(null);
            $table->longText('logo')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('identitas_id')
                ->references('identitas_id')
                ->on('master_identitas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_social_media');
    }
};
