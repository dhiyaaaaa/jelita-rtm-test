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
        Schema::create('laporan_form', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('laporan_id')->constrained('laporan', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_id')->nullable()->constrained('auditor', 'id')->onDelete('cascade');
            $table->text('kelebihan')->nullable();
            $table->text('ruang_peningkatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_form');
    }
};
