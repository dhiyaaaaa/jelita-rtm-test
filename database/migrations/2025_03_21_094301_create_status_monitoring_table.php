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
        Schema::create('status_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('monitoring_id')->constrained('monitoring', 'id')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'id')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_monitoring');
    }
};
