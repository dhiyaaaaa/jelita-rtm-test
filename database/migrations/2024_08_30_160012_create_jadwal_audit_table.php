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
        Schema::create('jadwal_audit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jadwal');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->boolean('import')->default(0);
            $table->boolean('fitur_auditor')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_audit');
    }
};
