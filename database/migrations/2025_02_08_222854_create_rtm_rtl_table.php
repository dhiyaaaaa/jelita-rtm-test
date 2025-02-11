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
        Schema::create('rtm_rtl', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jadwal_audit_id')->constrained('jadwal_audit', 'id')->onDelete('cascade');
            $table->foreignUuid('rtm_jadwal_id')->constrained('rtm_jadwal', 'id')->onDelete('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade');
            $table->date('tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_rtl');
    }
};
