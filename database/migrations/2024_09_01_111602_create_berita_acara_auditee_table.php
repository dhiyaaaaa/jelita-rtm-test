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
        Schema::create('berita_acara_auditee', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('berita_acara_id')->constrained('berita_acara', 'id')->onDelete('cascade');
            $table->foreignUuid('auditee_id')->constrained('auditee', 'id')->onDelete('cascade');
            $table->boolean('approve')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_auditee');
    }
};
