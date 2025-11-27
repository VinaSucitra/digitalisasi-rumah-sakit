<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * Tabel 'appointments' ini mengikuti spesifikasi tugas:
     * - Relasi ke patients (pasien)
     * - Relasi ke doctor_details (dokter)
     * - Relasi ke schedules (jadwal praktik)
     * - Field: booking_date, complaint, status, queue_number, rejected_reason
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Pasien (profil pasien, relasi ke tabel patients)
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');

            // Dokter (sesuaikan dengan tabel dokter milikmu: doctor_details)
            $table->foreignId('doctor_id')
                ->constrained('doctor_details')
                ->onDelete('cascade');

            // Jadwal praktik yang dipilih pasien
            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->onDelete('cascade');

            // Tanggal janji temu (hari konsultasi)
            $table->date('booking_date');

            // Keluhan singkat pasien
            $table->text('complaint');

            // Status janji temu sesuai tugas:
            // Pending -> Approved / Rejected -> Selesai (Done)
            $table->enum('status', ['pending', 'approved', 'rejected', 'done'])
                ->default('pending');

            // Alasan penolakan (jika status = rejected)
            $table->text('rejected_reason')->nullable();

            // Nomor antrian (jika ingin menampilkan antrian)
            $table->string('queue_number')->nullable();

            $table->timestamps();

            // Cegah double-booking:
            // Pasien yang sama tidak bisa booking di jadwal & tanggal yang sama
            $table->unique(['schedule_id', 'booking_date', 'patient_id'], 'uniq_schedule_booking_patient');
        });
    }

    /**
     * Undo migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
