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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama obat/tindakan (harus unik)
            $table->text('description')->nullable(); // Deskripsi atau dosis
            $table->decimal('price', 10, 2); // Harga (untuk tagihan)
            $table->enum('type', ['Obat', 'Tindakan'])->default('Obat'); // Jenis: Obat atau Tindakan Medis

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};