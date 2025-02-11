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
        Schema::create('rtm_lampiran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuId('rtm_jadwal_id')->constrained('rtm_jadwal', 'id')->onDelete('cascade');
            $table->text('presensi');
            $table->text('undangan');
            $table->text('dokumentasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_lampiran');
    }
};
