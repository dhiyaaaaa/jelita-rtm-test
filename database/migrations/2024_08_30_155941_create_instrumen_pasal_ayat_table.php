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
        Schema::create('instrumen_pasal_ayat', function (Blueprint $table) {
            $table->foreignId('instrumen_id')->constrained('instrumen', 'id')->onDelete('cascade');
            $table->foreignId('pasal_id')->nullable()->constrained('pasal', 'id')->onDelete('cascade');
            $table->foreignId('ayat_id')->nullable()->constrained('ayat', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrumen_pasal_ayat');
    }
};
