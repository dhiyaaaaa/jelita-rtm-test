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
        Schema::table('monitoring_form_status', function (Blueprint $table) {
            $table->foreignId('kriteria_id')
                ->constrained('kriteria', 'id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_form_status', function (Blueprint $table) {
            $table->dropForeign(['kriteria_id']);
            $table->dropColumn('kriteria_id');
        });
    }
};
