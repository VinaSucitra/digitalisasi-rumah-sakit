@extends('layouts.doctor')

@section('page_title', 'Rekam Medis')

@section('content')

<div class="space-y-8">

    {{-- HERO HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-500 to-emerald-500 rounded-2xl px-6 py-5 shadow-md text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-teal-100 mb-1">Rekam Medis & Konsultasi</p>
            <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-2">
                <i class="fas fa-notes-medical text-white/90"></i>
                Manajemen Rekam Medis
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Pantau antrean konsultasi hari ini dan riwayat rekam medis pasien Anda dalam satu tampilan.
            </p>
        </div>

        {{-- STAT KECIL --}}
        <div class="flex flex-wrap gap-3 text-xs">
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 flex flex-col justify-center min-w-[130px]">
                <span class="text-[10px] uppercase tracking-widest text-teal-100">Tanggal Hari Ini</span>
                <span class="font-semibold text-sm">
                    {{ \Carbon\Carbon::parse($today)->translatedFormat('d M Y') }}
                </span>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 flex flex-col justify-center min-w-[130px]">
                <span class="text-[10px] uppercase tracking-widest text-teal-100">Antrean Disetujui</span>
                <span class="font-semibold text-sm">
                    {{ $appointments->count() }} Janji Temu
                </span>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2"
             role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2"
             role="alert">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- KONTEN UTAMA: GRID 2 KOLOM --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- 1. ANTRIAN KONSULTASI HARI INI --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-teal-50 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-teal-900 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-teal-100 text-teal-700">
                            <i class="fas fa-user-clock text-xs"></i>
                        </span>
                        Antrean Konsultasi Hari Ini
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Janji temu berstatus <span class="font-semibold text-emerald-600">Approved</span> pada
                        {{ \Carbon\Carbon::parse($today)->translatedFormat('d M Y') }}.
                    </p>
                </div>

                <div class="hidden md:flex items-center gap-2 text-[11px] text-gray-500">
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-teal-50 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Siap dikonsultasikan
                    </span>
                </div>
            </div>

            @if($appointments->isEmpty())
                <div
                    class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-dashed border-teal-200 text-teal-700 px-4 py-4 rounded-xl text-sm text-center flex flex-col items-center gap-2">
                    <i class="fas fa-check-double text-2xl text-emerald-400"></i>
                    <p>Tidak ada antrean konsultasi yang disetujui hari ini.</p>
                    <p class="text-xs text-teal-500">
                        Saat janji temu pasien berstatus <span class="font-semibold">Approved</span>, mereka akan
                        muncul di sini secara otomatis.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full text-xs">
                        <thead class="bg-teal-50/70">
                        <tr class="text-[11px] uppercase tracking-wide text-gray-600">
                            <th class="py-3 px-4 text-left font-semibold">Pasien</th>
                            <th class="py-3 px-4 text-left font-semibold">Jam</th>
                            <th class="py-3 px-4 text-left font-semibold">Keluhan Singkat</th>
                            <th class="py-3 px-4 text-center font-semibold">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($appointments as $a)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Pasien --}}
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-semibold">
                                            {{ strtoupper(substr($a->patient->user->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800">
                                                {{ $a->patient->user->name ?? '-' }}
                                            </span>
                                            <span class="text-[11px] text-gray-500">
                                                ID Pasien: #{{ $a->patient_id }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Jam --}}
                                <td class="py-3 px-4 text-teal-700 font-medium">
                                    @if($a->schedule)
                                        {{ \Carbon\Carbon::parse($a->schedule->start_time)->format('H:i') }} WIB
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- Keluhan --}}
                                <td class="py-3 px-4 text-gray-600">
                                    {{ \Illuminate\Support\Str::limit($a->complaint, 70) }}
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold bg-teal-600 text-white hover:bg-teal-700 shadow-sm transition">
                                        <i class="fas fa-file-medical mr-1"></i>
                                        Buat Rekam Medis
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- 2. RIWAYAT REKAM MEDIS TERBARU --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <span
                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-600">
                        <i class="fas fa-history text-xs"></i>
                    </span>
                    Riwayat Rekam Medis
                </h3>
                <a href="#"
                   class="text-[11px] font-medium text-teal-600 hover:text-teal-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($records->isEmpty())
                <div class="border border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-500 text-center">
                    Belum ada Rekam Medis yang Anda buat.
                </div>
            @else
                <ul class="space-y-3 max-h-[360px] overflow-y-auto pr-1">
                    @foreach($records as $rec)
                        <li
                            class="p-3 border border-gray-100 rounded-xl hover:border-teal-100 hover:bg-teal-50/40 transition flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div
                                    class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-semibold">
                                    {{ strtoupper(substr($rec->patient->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-gray-900 truncate">
                                        {{ $rec->patient->user->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        {{ \Carbon\Carbon::parse($rec->visit_date)->translatedFormat('d M Y') }}
                                        &middot;
                                        {{ \Illuminate\Support\Str::limit($rec->diagnosis, 40) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('doctor.medical_records.show', $rec->id) }}"
                               class="flex-shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-full bg-teal-600 text-white text-xs hover:bg-teal-700 shadow-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection
