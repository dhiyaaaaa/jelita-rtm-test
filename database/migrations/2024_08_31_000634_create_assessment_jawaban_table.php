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
        Schema::create('assessment_jawaban', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jadwal_audit_id')->constrained('jadwal_audit', 'id')->onDelete('cascade');
            $table->foreignUuid('prodi_id')->nullable()->constrained('prodi', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('assessment_form_id')->constrained('assessment_form', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_penilai_id')->constrained('auditor', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_dinilai_id')->constrained('auditor', 'id')->onDelete('cascade');
            $table->string('jawaban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_jawaban');
    }
};
