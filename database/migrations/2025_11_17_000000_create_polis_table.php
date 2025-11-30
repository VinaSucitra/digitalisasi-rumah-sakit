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
        Schema::create('polis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();          // Nama Poli (Umum, Gigi, Anak, dll)
            $table->string('description')->nullable(); // Deskripsi singkat
            $table->string('icon')->nullable();        // Nama ikon (misal: stethoscope, tooth, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polis');
    }
};
