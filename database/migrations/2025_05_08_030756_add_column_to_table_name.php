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
        Schema::table('master_organisasi', function (Blueprint $table) {
            $table->uuid('approved_by')->nullable();
            $table->bigInteger('order_number')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_organisasi', function (Blueprint $table) {
            $table->dropColumn('approved_by');
            $table->dropColumn('order_number');
        });
    }
};
