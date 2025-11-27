<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();

            // Relasi ke rekam medis
            $table->foreignId('medical_record_id')
                ->constrained('medical_records')
                ->onDelete('cascade');

            // Status resep (opsional lanjut untuk farmasi)
            $table->enum('status', ['pending', 'ready', 'taken'])
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
