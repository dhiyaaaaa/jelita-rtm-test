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
        Schema::create('instrumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peraturan_id')->nullable()->constrained('peraturan', 'id')->onDelete('cascade');
            $table->foreignId('standar_id')->constrained('standar', 'id')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori', 'id')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('level', 'id')->onDelete('cascade');
            $table->foreignId('jenis_pertanyaan_id')->constrained('jenis_pertanyaan', 'id')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->text('pernyataan');
            $table->text('indikator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen');
    }
};
