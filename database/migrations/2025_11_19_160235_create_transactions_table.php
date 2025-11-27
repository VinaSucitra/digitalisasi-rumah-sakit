<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Janji Temu (Appointment)
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            
            // Detail Transaksi
            $table->decimal('total_amount', 10, 2); // Jumlah total yang harus dibayar
            $table->decimal('paid_amount', 10, 2); // Jumlah yang dibayar (bisa berbeda jika ada DP)
            $table->enum('payment_method', ['cash', 'transfer', 'card'])->nullable();
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->string('reference_number')->nullable()->unique();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};