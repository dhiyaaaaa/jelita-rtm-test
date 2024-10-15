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
        Schema::create('jabatan_user', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatan', 'id')->onDelete('cascade');
            $table->foreignUuid('fakultas_id')->nullable()->constrained('fakultas', 'id')->onDelete('cascade');
            $table->foreignUuid('prodi_id')->nullable()->constrained('prodi', 'id')->onDelete('cascade');
            $table->foreignUuid('unit_id')->nullable()->constrained('unit', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_user');
    }
};
