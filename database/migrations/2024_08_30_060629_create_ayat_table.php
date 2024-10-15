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
        Schema::create('ayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peraturan_id')->constrained('peraturan', 'id')->onDelete('cascade');
            $table->foreignId('pasal_id')->constrained('pasal', 'id')->onDelete('cascade');
            $table->integer('ayat');
            $table->text('isi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayat');
    }
};
