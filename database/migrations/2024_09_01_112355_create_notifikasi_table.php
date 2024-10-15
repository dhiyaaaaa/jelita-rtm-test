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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('jadwal_audit_id')->constrained('jadwal_audit', 'id')->onDelete('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade');
            $table->foreignUuid('prodi_id')->nullable()->constrained('prodi', 'id')->onDelete('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->nullable()->constrained('form', 'id')->onDelete('cascade');
            $table->foreignUuid('auditor_id')->nullable()->constrained('auditor', 'id')->onDelete('cascade');
            $table->foreignUuid('auditee_id')->nullable()->constrained('auditee', 'id')->onDelete('cascade');
            $table->string('pesan');
            $table->enum('status', ['terkirim', 'diterima', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
