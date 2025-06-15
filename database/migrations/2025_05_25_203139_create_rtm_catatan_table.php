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
        Schema::create('rtm_catatan', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rtm_jadwal_id')->constrained('rtm_jadwal', 'id')->onDelete('cascade');
            $table->foreignUuId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->text('bagian');
            $table->text('judul');
            $table->text('isi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtm_catatan');
    }
};
