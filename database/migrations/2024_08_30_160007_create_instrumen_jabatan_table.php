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
        Schema::create('instrumen_jabatan', function (Blueprint $table) {
            $table->foreignId('instrumen_id')->constrained('instrumen', 'id')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatan', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen_jabatan');
    }
};
