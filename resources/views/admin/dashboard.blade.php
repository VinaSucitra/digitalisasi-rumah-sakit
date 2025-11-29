@extends('admin.layouts.app')

@section('page_title', 'Dashboard Admin Rumah Sakit')

@section('content')
<div class="space-y-8">

    {{-- HEADER DASHBOARD --}}
    <div class="bg-gradient-to-r from-teal-600 to-teal-500 rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                Selamat datang, {{ auth()->user()->name ?? 'Admin' }}
            </h1>
            <p class="text-teal-100 text-sm mt-1">
                Ringkasan operasional rumah sakit untuk tanggal {{ now()->format('d M Y') }}.
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

        {{-- Pending Appointments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-teal-700 uppercase tracking-wide">
                    Janji Temu Pending
                </p>
                {{-- Variabel yang benar: $pendingAppointments --}}
                <p class="mt-2 text-3xl font-extrabold text-teal-900">
                    {{ $pendingAppointments ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Menunggu verifikasi Admin / Dokter.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-calendar-clock text-teal-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Total Doctors --}}
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                    Total Dokter
                </p>
                {{-- 🔥 PERBAIKAN: Menggunakan $totalDoctor --}}
                <p class="mt-2 text-3xl font-extrabold text-emerald-900">
                    {{ $totalDoctor ?? 0 }} 
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Dokter yang terdaftar di sistem.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-user-doctor text-emerald-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Total Patients --}}
        <div class="bg-white rounded-2xl shadow-sm border border-sky-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-sky-700 uppercase tracking-wide">
                    Total Pasien
                </p>
                {{-- 🔥 PERBAIKAN: Menggunakan $totalPatient --}}
                <p class="mt-2 text-3xl font-extrabold text-sky-900">
                    {{ $totalPatient ?? 0 }} 
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Akun pasien yang sudah terdaftar.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-sky-100 flex items-center justify-center">
                    <i class="fas fa-user-injured text-sky-700 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Total Medicines --}}
        <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-4 flex items-center">
            <div class="flex-1">
                <p class="text-xs font-semibold text-rose-700 uppercase tracking-wide">
                    Total Obat & Tindakan
                </p>
                {{-- Variabel yang benar: $totalMedicines --}}
                <p class="mt-2 text-3xl font-extrabold text-rose-900">
                    {{ $totalMedicines ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Item obat & tindakan medis yang tersedia.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 flex items-center justify-center">
                    <i class="fas fa-pills text-rose-700 text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- APPOINTMENTS + DOCTORS TODAY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Appointments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Janji Temu Terbaru
                </h2>
                <a href="{{ route('admin.appointments.index') }}"
                   class="text-xs font-semibold text-teal-600 hover:text-teal-800">
                    Lihat semua &rarr;
                </a>
            </div>

            @if($recentAppointments->isEmpty())
                <p class="text-xs text-gray-500">Belum ada janji temu yang tercatat.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="border-b text-[11px] uppercase tracking-wide text-gray-500">
                                <th class="py-2 text-left">Pasien</th>
                                <th class="py-2 text-left">Dokter</th>
                                <th class="py-2 text-left">Poli</th>
                                <th class="py-2 text-left">Tanggal</th>
                                <th class="py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($recentAppointments as $app)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2">
                                        {{ $app->patient->user->name ?? '-' }}
                                    </td>
                                    <td class="py-2">
                                        {{ $app->doctor->user->name ?? '-' }}
                                    </td>
                                    <td class="py-2">
                                        {{ $app->doctor->poli->name ?? '-' }} {{-- Menggunakan relasi langsung $app->doctor->poli->name --}}
                                    </td>
                                    <td class="py-2">
                                        {{ \Illuminate\Support\Carbon::parse($app->booking_date)->format('d M Y') }}
                                    </td>
                                    <td class="py-2">
                                        @php
                                            $status = $app->status;
                                            $color = match($status) {
                                                'approved','confirmed' => 'bg-emerald-100 text-emerald-700',
                                                'rejected','cancelled' => 'bg-rose-100 text-rose-700',
                                                'done','completed'     => 'bg-sky-100 text-sky-700',
                                                default                => 'bg-amber-100 text-amber-700', // pending
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-[11px] font-semibold {{ $color }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Doctors Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2 mb-3">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                Dokter yang Bertugas Hari Ini
            </h2>

            @if($doctorsToday->isEmpty()) {{-- Variabel sudah benar dari Controller --}}
                <p class="text-xs text-gray-500">
                    Tidak ada dokter yang terjadwal praktik hari ini.
                </p>
            @else
                <ul class="space-y-2 text-xs">
                    @foreach($doctorsToday as $schedule) {{-- Looping berdasarkan schedule --}}
                        <li class="flex items-start justify-between rounded-xl border border-gray-100 px-3 py-2 hover:bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $schedule->doctor->user->name ?? '-' }}
                                </p>
                                <p class="text-[11px] text-gray-500">
                                    Poli {{ $schedule->doctor->poli->name ?? '-' }}
                                </p>
                            </div>
                            <span class="text-[11px] text-emerald-600 font-semibold">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
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
            <h3 class="font-semibold text-gray-800 mb-1">Manajemen Data</h3>
            <p>Kelola data Poli, Dokter, Pasien, Obat & Jadwal melalui menu di sidebar kiri.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h3 class="font-semibold text-gray-800 mb-1">Alur Janji Temu</h3>
            <p>Pasien booking jadwal, Admin/Dokter melakukan verifikasi, lalu dokter mengisi rekam medis.</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <h3 class="font-semibold text-gray-800 mb-1">Rekam Medis & Resep</h3>
            <p>Dokter membuat rekam medis dan resep, pasien dapat melihat riwayat dan status resep.</p>
        </div>
    </div>

</div>
@endsection