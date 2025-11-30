<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke users (akun login dokter)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Relasi ke poli
            $table->foreignId('poli_id')
                ->constrained('polis')
                ->onDelete('restrict');

            $table->string('sip')->nullable(); 
            $table->text('bio')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_details');
    }
};
