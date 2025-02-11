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
        Schema::create('rtm_jadwal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jadwal_audit_id')->constrained('jadwal_audit', 'id')->onDelete('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade');
            $table->string('agenda');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tempat');
            $table->string('pimpinan');
            $table->integer('peserta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_jadwal');
    }
};
