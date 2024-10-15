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
        Schema::create('auditee_auditor', function (Blueprint $table) {
            $table->foreignUuid('jadwal_audit_id')->constrained('jadwal_audit', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('auditee_id')->constrained('auditee', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('auditor_id')->constrained('auditor', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('prodi_id')->nullable()->constrained('prodi', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditee_auditor');
    }
};
