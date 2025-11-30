@extends('layouts.doctor')

@section('page_title', 'Dashboard Dokter')

@section('content')
<div class="space-y-8">

    {{-- HERO DASHBOARD --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-500 to-emerald-500 rounded-2xl shadow-md px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Doctor Panel
            </p>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-stethoscope text-white/90"></i>
                Selamat datang, Dr. {{ $user->name ?? 'Dokter' }}
            </h1>
            <p class="text-teal-100 text-sm mt-1">
                Ringkasan aktivitas praktik Anda untuk tanggal
                <span class="font-semibold">{{ now()->format('d M Y') }}</span>.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-3 text-xs">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/30 text-teal-50">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem berjalan normal
            </div>

            <div class="bg-white/10 rounded-xl px-4 py-2 text-[11px]">
                <p class="uppercase tracking-widest text-teal-100 mb-1">Aktivitas Hari Ini</p>
                <div class="flex flex-wrap gap-3 text-teal-50">
                    <span>{{ $pendingCount ?? 0 }} pending</span>
                    <span>• {{ $todayApproved->count() ?? 0 }} approved</span>
                    <span>• {{ $todayQueue->count() ?? 0 }} antrean</span>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- 1. Pending Appointments --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-rose-100 p-4 flex items-center hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex-1">
                <p class="text-[11px] font-semibold text-rose-700 uppercase tracking-wide">
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
                <div class="w-11 h-11 rounded-2xl bg-rose-50 flex items-center justify-center">
                    <i class="fas fa-clock text-rose-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 2. Approved Today --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-teal-100 p-4 flex items-center hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex-1">
                <p class="text-[11px] font-semibold text-teal-700 uppercase tracking-wide">
                    Janji Temu Hari Ini
                </p>
                <p class="mt-2 text-3xl font-extrabold text-teal-900">
                    {{ $todayApproved->count() ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Pasien yang dijadwalkan datang hari ini.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-teal-50 flex items-center justify-center">
                    <i class="fas fa-user-check text-teal-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 3. Total Jadwal Praktik --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-4 flex items-center hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex-1">
                <p class="text-[11px] font-semibold text-indigo-700 uppercase tracking-wide">
                    Jadwal Praktik Aktif
                </p>
                <p class="mt-2 text-3xl font-extrabold text-indigo-900">
                    {{ $totalSchedules ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Slot jadwal praktik yang Anda miliki.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 flex items-center justify-center">
                    <i class="fas fa-calendar-days text-indigo-600 text-lg"></i>
                </div>
            </div>
        </div>

        {{-- 4. Medical Records --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-4 flex items-center hover:shadow-md hover:-translate-y-0.5 transition">
            <div class="flex-1">
                <p class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wide">
                    Rekam Medis Terbaru
                </p>
                <p class="mt-2 text-3xl font-extrabold text-emerald-900">
                    {{ $latestPatients->count() ?? 0 }}
                </p>
                <p class="mt-1 text-[11px] text-gray-500">
                    Rekam medis yang baru-baru ini Anda buat.
                </p>
            </div>
            <div class="ml-4">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <i class="fas fa-notes-medical text-emerald-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- APPOINTMENTS + QUEUE --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Approved Appointments Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
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
                <div class="rounded-xl bg-teal-50 border border-teal-100 px-4 py-3 text-xs text-teal-700 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Belum ada janji temu approved untuk hari ini.</span>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr class="border-b text-[11px] uppercase tracking-wide text-gray-500">
                                <th class="py-2 px-3 text-left">No. Antrian</th>
                                <th class="py-2 px-3 text-left">Pasien</th>
                                <th class="py-2 px-3 text-left">Keluhan</th>
                                <th class="py-2 px-3 text-left">Jam Mulai</th>
                                <th class="py-2 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($todayApproved as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-3 font-semibold text-gray-800">
                                        {{ $a->queue_number ?? '-' }}
                                    </td>
                                    <td class="py-2 px-3">
                                        {{ $a->patient->user->name ?? '-' }}
                                    </td>
                                    <td class="py-2 px-3 text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($a->complaint, 40) }}
                                    </td>
                                    <td class="py-2 px-3 text-teal-600 font-medium">
                                        {{ $a->schedule ? \Carbon\Carbon::parse($a->schedule->start_time)->format('H:i') : '-' }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                                           class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-semibold text-teal-900 flex items-center gap-2 mb-3">
                <span class="inline-block w-2 h-2 rounded-full bg-sky-500"></span>
                Antrean Pasien Saat Ini
            </h2>

            @if($todayQueue->isEmpty())
                <p class="text-xs text-gray-500">
                    Tidak ada pasien yang berada di antrean.
                </p>
            @else
                <ul class="space-y-2 text-xs max-h-72 overflow-y-auto pr-1">
                    @foreach($todayQueue as $queue)
                        <li class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ $queue->queue_number }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $queue->patient->user->name ?? '-' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($queue->complaint, 25) }}
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

    {{-- PASIEN TERBARU YANG DIPERIKSA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                Pasien Terbaru yang Diperiksa
            </h2>
        </div>

        @if($latestPatients->isEmpty())
            <p class="text-xs text-gray-500">
                Belum ada rekam medis yang Anda buat.
            </p>
        @else
            <ul class="space-y-2 text-sm">
                @foreach($latestPatients as $record)
                    @php
                        $date = $record->visit_date
                            ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y')
                            : '-';
                    @endphp
                    <li class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2 hover:bg-gray-50 transition">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $record->patient->user->name ?? 'Pasien' }}
                            </p>
                            <p class="text-[11px] text-gray-500">
                                Tgl pemeriksaan: {{ $date }}
                            </p>
                        </div>
                        <span class="text-[11px] text-emerald-600 font-semibold">
                            Rekam Medis
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- INFO RINGKAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs text-gray-600">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex gap-3">
            <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                <i class="fas fa-calendar-alt text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">Manajemen Jadwal</h3>
                <p>Kelola jadwal praktik dan janji temu Anda melalui menu di sidebar kiri.</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fas fa-procedures text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">Alur Pelayanan</h3>
                <p>Pasien diantrekan, Anda periksa, lalu buat Rekam Medis (RM) dan Resep.</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex gap-3">
            <div class="w-8 h-8 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600">
                <i class="fas fa-file-medical-alt text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">Rekam Medis (RM)</h3>
                <p>Pastikan setiap pemeriksaan pasien diakhiri dengan pembuatan RM yang lengkap.</p>
            </div>
        </div>
    </div>

</div>
@endsection
