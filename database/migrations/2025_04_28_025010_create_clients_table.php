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
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('client_id')->primary();
            $table->uuid('user_id')->nullable()->default(null);
            $table->string('client_code')->nullable()->default(null);
            $table->string('client_name')->nullable()->default(null);
            $table->string('client_job')->nullable()->default(null);
            $table->string('client_phone', 100)->nullable()->default(null);
            $table->string('client_email')->nullable()->default(null);
            $table->text('client_address')->nullable()->default(null);
            $table->text('client_photo')->nullable()->default(null);
            $table->uuid('organisasi_id')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes(); // deleted_at
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('organisasi_id')
                ->references('organisasi_id')
                ->on('master_organisasi')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
