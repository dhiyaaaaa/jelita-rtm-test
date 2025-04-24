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
        Schema::create('rtl_form', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rtl_id')->constrained('rtl', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'id')->onDelete('cascade');
            $table->foreignUuid('auditee_id')->constrained('auditee', 'id')->onDelete('cascade');
            $table->text('tindakan');
            $table->text('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtl_form');
    }
};
