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
        Schema::create('rtl_approve', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rtl_id')->constrained('rtl', 'id')->onDelete('cascade');
            $table->foreignUuId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->boolean('approve')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtl_approve');
    }
};
