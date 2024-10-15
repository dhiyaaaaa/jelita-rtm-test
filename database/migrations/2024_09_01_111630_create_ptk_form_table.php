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
        Schema::create('ptk_form', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('ptk_id')->constrained('ptk', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_id')->nullable()->constrained('auditor', 'id')->onDelete('cascade');
            $table->foreignUuid('auditee_id')->nullable()->constrained('auditee', 'id')->onDelete('cascade');
            $table->text('analisis')->nullable();
            $table->text('akibat')->nullable();
            $table->text('target')->nullable();
            $table->enum('kategori_temuan', ['observasi', 'minor', 'mayor'])->nullable();
            $table->text('pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptk_form');
    }
};
