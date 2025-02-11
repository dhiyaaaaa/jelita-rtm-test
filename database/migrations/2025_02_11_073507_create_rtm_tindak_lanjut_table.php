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
        Schema::create('rtm_tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rtm_rtl_id')->constrained('rtm_rtl', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignUuid('prodi_id')->nullable()->constrained('prodi', 'id')->onDelete('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'id')->onDelete('cascade'); // Relasi ke tabel kriteria
            $table->text('tindakan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_tindak_lanjut');
    }
};
