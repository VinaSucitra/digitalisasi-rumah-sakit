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

            // Nama obat/tindakan (harus unik)
            $table->string('name')->unique();

            // Deskripsi / catatan obat atau tindakan
            $table->text('description')->nullable();

            // Harga (dipakai untuk transaksi/tagihan)
            $table->decimal('price', 10, 2);

            // Jenis item: Obat atau Tindakan
            $table->enum('type', ['Obat', 'Tindakan'])->default('Obat');

            // Tipe obat: keras / biasa (untuk Tindakan boleh null)
            $table->enum('drug_type', ['biasa', 'keras'])->nullable();

            // Stok obat (untuk Tindakan bisa 0 saja)
            $table->unsignedInteger('stock')->default(0);

            // Path gambar obat (opsional)
            $table->string('image')->nullable();

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
