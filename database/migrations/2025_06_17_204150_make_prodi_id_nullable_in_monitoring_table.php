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
        Schema::table('monitoring', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            
            $table->uuid('prodi_id')->nullable()->change();
            
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->uuid('prodi_id')->nullable(false)->change();
            $table->foreign('prodi_id')->references('id')->on('prodi')->onDelete('cascade');
        });
    }
};
