<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            // Janji temu yang terkait (harus sudah ada di tabel appointments)
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade');

            // Pasien (ambil dari tabel patients, bukan langsung users lagi)
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');

            // Dokter (ambil dari tabel doctor_details)
            $table->foreignId('doctor_id')
                ->constrained('doctor_details')
                ->onDelete('cascade');

            // Isi rekam medis
            $table->text('diagnosis');
            $table->text('treatment')->nullable(); // tindakan medis
            $table->text('notes')->nullable();     // catatan tambahan

            // Tanggal kunjungan
            $table->date('visit_date');

            $table->timestamps();

            // Satu appointment hanya boleh punya satu rekam medis
            $table->unique('appointment_id', 'uniq_medical_records_appointment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
