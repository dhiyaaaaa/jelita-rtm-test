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
        Schema::create('rtm_rtl_form', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rtm_rtl_id')->constrained('rtm_rtl', 'id')->onDelete('cascade');
            $table->foreignUuid('form_id')->constrained('form', 'id')->onDelete('cascade');
            $table->foreignUuId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->text('rekomendasi');
            $table->text('koreksi');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_rtl_form');
    }
};
