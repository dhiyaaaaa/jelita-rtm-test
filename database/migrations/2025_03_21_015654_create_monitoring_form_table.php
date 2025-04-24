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
        Schema::create('monitoring_form', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('monitoring_id')->constrained('monitoring', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_id')->nullable()->constrained('auditor', 'id')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_form');
    }
};
