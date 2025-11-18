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
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->onDelete('cascade'); 

            $table->foreignId('poli_id')
                  ->nullable() 
                  ->constrained('polis')
                  ->onDelete('set null');
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_details');
    }
};
