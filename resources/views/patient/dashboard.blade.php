@extends('layouts.patient')

@section('title', 'Dashboard Pasien')
@section('page_title', 'Dashboard Pasien')

@section('content')
    <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 space-y-6">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Dashboard Pasien</h2>
        <p class="text-gray-600 mb-6">Ringkasan riwayat dan janji temu Anda.</p>
            
        {{-- KARTU RINGKASAN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            
            {{-- Janji Temu Menunggu --}}
            <div class="bg-teal-50 border-l-4 border-teal-500 rounded-lg p-4 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                    JANJI TEMU MENUNGGU
                </p>
                <p class="text-3xl font-bold text-teal-700 mt-1">
                    {{ $pendingAppointments ?? 0 }}
                </p>
                <p class="text-xs text-gray-500">Menunggu verifikasi Admin/Dokter.</p>
            </div>

            {{-- Total Riwayat Pemeriksaan --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                    TOTAL RIWAYAT PERIKSA
                </p>
                <p class="text-3xl font-bold text-blue-700 mt-1">
                    {{ $totalRecords ?? 0 }}
                </p>
                <p class="text-xs text-gray-500">Jumlah Rekam Medis yang Anda miliki.</p>
            </div>
            
            {{-- Akses Cepat --}}
            <a href="{{ route('patient.doctor_schedules.index') }}"
               class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm transition hover:shadow-md">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                    AKSES CEPAT
                </p>
                <p class="text-lg font-bold text-green-700 mt-1">
                    Lihat Jadwal Dokter
                </p>
                <p class="text-xs text-gray-500">Periksa jadwal dokter sebelum reservasi.</p>
            </a>
        </div>

        {{-- JADWAL & RIWAYAT TERAKHIR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
            
            {{-- Janji Temu Terakhir --}}
            <div class="bg-white shadow-sm rounded-lg p-5 border">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-lg font-semibold text-gray-700">
                        Janji Temu Terakhir (Approved)
                    </h4>
                    <a href="{{ route('patient.appointments.index') }}"
                       class="text-sm text-teal-600 hover:text-teal-800">
                        Lihat Semua →
                    </a>
                </div>
                <p class="text-gray-500">
                    Tidak ada janji temu yang disetujui saat ini.
                </p>
            </div>

            {{-- Riwayat Pemeriksaan Terakhir --}}
            <div class="bg-white shadow-sm rounded-lg p-5 border">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-lg font-semibold text-gray-700">
                        Riwayat Pemeriksaan Terakhir
                    </h4>
                    <a href="{{ route('patient.medical_records.index') }}"
                       class="text-sm text-teal-600 hover:text-teal-800">
                        Lihat Semua →
                    </a>
                </div>
                <p class="text-gray-500">
                    Belum ada riwayat pemeriksaan (Rekam Medis) yang tercatat.
                </p>
            </div>
        </div>
    </div>
@endsection
