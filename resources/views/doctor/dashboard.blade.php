@extends('layouts.doctor')

@section('page_title', 'Dashboard Dokter')

@section('content')
<div class="space-y-8">

    {{-- HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-teal-600 to-teal-500 rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                Selamat datang, Dr. {{ $user->name ?? 'Dokter' }}
            </h1>
            <p class="text-teal-100 text-sm mt-1">
                Ringkasan aktivitas praktik Anda untuk tanggal {{ now()->format('d M Y') }}.
            </p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs text-teal-50">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem berjalan normal
            </span>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- 1. Pending Appointments (Menggunakan warna Merah/Rose untuk Penekanan) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-rose-700 uppercase tracking-wide">
                    Janji Temu Pending
                </p>
                <p class="mt-2 text-3xl font-extrabold text-rose-900">
                    {{ $pendingCount ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Janji temu yang menunggu persetujuan Anda.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 flex items-center justify-center">
                    <i class="fas fa-calendar-clock text-rose-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 2. Approved Today (Menggunakan warna Teal) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-teal-700 uppercase tracking-wide">
                    Janji Temu Hari Ini
                </p>
                <p class="mt-2 text-3xl font-extrabold text-teal-900">
                    {{ $todayApproved->count() ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Pasien yang dijadwalkan hari ini.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-teal-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 3. Queue Number (Menggunakan warna Sky) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-sky-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-sky-700 uppercase tracking-wide">
                    Antrian Hari Ini
                </p>
                <p class="mt-2 text-3xl font-extrabold text-sky-900">
                    {{ $todayQueue->count() ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Pasien yang sedang menunggu di antrean.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-sky-100 flex items-center justify-center">
                    <i class="fas fa-users text-sky-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 4. Medical Records (Menggunakan warna Emerald) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                    Rekam Medis Terbaru
                </p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-900">
                    {{ $latestPatients->count() ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Total rekam medis yang baru-baru ini dibuat.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-notes-medical text-emerald-700 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- APPOINTMENTS + RECENT RECORDS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Approved Appointments (lg:col-span-2) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Janji Temu Approved Hari Ini
                </h2>
                <a href="{{ route('doctor.appointments.index') }}"
                   class="text-xs font-semibold text-teal-600 hover:text-teal-800">
                    Lihat semua &rarr;
                </a>
            </div>

            @if($todayApproved->isEmpty())
                <p class="text-xs text-gray-500">Belum ada janji temu approved untuk hari ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="border-b text-[11px] uppercase tracking-wide text-gray-500">
                                <th class="py-2 text-left">No. Antrian</th>
                                <th class="py-2 text-left">Pasien</th>
                                <th class="py-2 text-left">Keluhan</th>
                                <th class="py-2 text-left">Jam Mulai</th>
                                <th class="py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($todayApproved as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 font-semibold">
                                        {{ $a->queue_number ?? '-' }}
                                    </td>
                                    <td class="py-2">
                                        {{ $a->patient->user->name ?? '-' }}
                                    </td>
                                    <td class="py-2 text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($a->complaint, 30) }}
                                    </td>
                                    <td class="py-2 text-teal-600 font-medium">
                                        {{ date('H:i', strtotime($a->schedule->start_time)) }}
                                    </td>
                                    <td class="py-2 text-center">
                                        <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                                           class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
                                            <i class="fas fa-plus mr-1"></i> Mulai RM
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Queue List Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2 mb-3">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                Antrean Pasien Saat Ini
            </h2>

            @if($todayQueue->isEmpty())
                <p class="text-xs text-gray-500">
                    Tidak ada pasien yang berada di antrean.
                </p>
            @else
                <ul class="space-y-2 text-xs">
                    @foreach($todayQueue as $queue)
                        <li class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold text-sm">
                                    {{ $queue->queue_number }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $queue->patient->user->name ?? '-' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($queue->complaint, 20) }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-[11px] text-sky-600 font-semibold">
                                Waiting
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Info ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs text-gray-600">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h3 class="font-semibold text-gray-800 mb-1">Manajemen Jadwal</h3>
            <p>Kelola janji temu dan jadwal praktik Anda melalui menu di sidebar kiri.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h3 class="font-semibold text-gray-800 mb-1">Alur Pelayanan</h3>
            <p>Pasien diantrekan, Anda periksa, lalu buat Rekam Medis (RM) dan Resep.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h3 class="font-semibold text-gray-800 mb-1">Rekam Medis (RM)</h3>
            <p>Pastikan setiap pemeriksaan pasien selesai dengan pembuatan Rekam Medis untuk riwayat data yang akurat.</p>
        </div>
    </div>

</div>
@endsection