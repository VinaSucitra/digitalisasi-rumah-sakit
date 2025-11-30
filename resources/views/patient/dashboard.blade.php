@extends('layouts.patient')

@section('title', 'Dashboard Pasien')
@section('page_title', 'Dashboard Pasien')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="space-y-6 md:space-y-8">

        {{-- HERO / HEADER --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 text-teal-50 shadow-xl">
            <div class="absolute inset-y-0 right-0 opacity-20">
                <div class="w-64 h-64 bg-white rounded-full blur-3xl translate-x-10 translate-y-6"></div>
            </div>

            <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-100">
                        Portal Pasien
                    </p>
                    <h2 class="text-2xl md:text-3xl font-bold leading-tight">
                        Halo, {{ auth()->user()->name ?? 'Pasien' }} 👋
                    </h2>
                    <p class="text-sm md:text-[13px] text-teal-100/90 max-w-xl">
                        Pantau janji temu, riwayat pemeriksaan, dan status resep Anda dalam satu dashboard.
                    </p>

                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-900/40 border border-teal-200/30 text-[11px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        Akun pasien aktif • Terakhir login:
                        <span class="font-semibold">
                            {{ now()->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 text-sm">
                    <a href="{{ route('patient.appointments.create') }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white text-teal-800 font-semibold shadow-md hover:shadow-lg hover:bg-teal-50 transition">
                        <i class="fas fa-calendar-plus mr-2 text-teal-600"></i>
                        Buat Janji Temu
                    </a>
                    <a href="{{ route('patient.doctor_schedules.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-teal-800/40 text-teal-50 border border-teal-200/40 hover:bg-teal-900/50 transition">
                        <i class="fas fa-user-md mr-2 text-emerald-200"></i>
                        Lihat Jadwal Dokter
                    </a>
                </div>
            </div>
        </div>

        {{-- KARTU RINGKASAN ATAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Janji Temu Menunggu --}}
            <div class="bg-white border border-teal-50 rounded-2xl p-4 md:p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center">
                        <i class="fas fa-clock text-teal-700"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-semibold text-teal-600 uppercase tracking-wide">
                            Janji Temu Menunggu
                        </p>
                        <p class="text-3xl font-bold text-teal-800 mt-1">
                            {{ $pendingAppointments ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Menunggu verifikasi Admin / Dokter.
                        </p>
                    </div>
                </div>
                <a href="{{ route('patient.appointments.index') }}"
                   class="mt-4 inline-flex items-center text-xs font-semibold text-teal-700 hover:text-teal-900">
                    Lihat daftar janji
                    <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>

            {{-- Total Riwayat Pemeriksaan --}}
            <div class="bg-white border border-blue-50 rounded-2xl p-4 md:p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-medical text-blue-700"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-semibold text-blue-600 uppercase tracking-wide">
                            Total Riwayat Periksa
                        </p>
                        <p class="text-3xl font-bold text-blue-800 mt-1">
                            {{ $totalRecords ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Jumlah rekam medis yang tercatat atas nama Anda.
                        </p>
                    </div>
                </div>
                <a href="{{ route('patient.medical_records.index') }}"
                   class="mt-4 inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">
                    Lihat semua riwayat
                    <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>

            {{-- Resep Siap Diambil --}}
            <div class="bg-white border border-emerald-50 rounded-2xl p-4 md:p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-pills text-emerald-700"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wide">
                            Resep Siap Diambil
                        </p>
                        <p class="text-3xl font-bold text-emerald-800 mt-1">
                            {{ $readyPrescriptions->count() ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Resep dengan status <span class="font-semibold">READY</span> di bagian farmasi.
                        </p>
                    </div>
                </div>
                <a href="{{ route('patient.medical_records.index') }}"
                   class="mt-4 inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                    Detail resep
                    <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>

        </div>

        {{-- STATUS JANJI & NOTIFIKASI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">

            {{-- Status Janji Temu Terakhir --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-teal-500"></span>
                        Status Janji Temu Terakhir
                    </h3>
                    <a href="{{ route('patient.appointments.index') }}"
                       class="text-xs text-teal-600 hover:text-teal-800 font-semibold">
                        Lihat semua →
                    </a>
                </div>

                @if($latestAppointment)
                    @php
                        $date = Carbon::parse($latestAppointment->booking_date)->translatedFormat('d M Y');
                        $start = optional($latestAppointment->schedule)->start_time
                            ? Carbon::parse($latestAppointment->schedule->start_time)->format('H:i')
                            : null;
                        $end = optional($latestAppointment->schedule)->end_time
                            ? Carbon::parse($latestAppointment->schedule->end_time)->format('H:i')
                            : null;

                        $status = strtolower($latestAppointment->status ?? 'pending');
                        $badge = match($status) {
                            'approved' => 'bg-emerald-100 text-emerald-800',
                            'rejected' => 'bg-red-100 text-red-700',
                            'done'     => 'bg-blue-100 text-blue-800',
                            default    => 'bg-amber-100 text-amber-800',
                        };
                    @endphp

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-9 h-9 rounded-full bg-teal-50 flex items-center justify-center border border-teal-100">
                                <i class="fas fa-calendar-day text-teal-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $date }}
                                    @if($start && $end)
                                        <span class="text-xs text-gray-500">• {{ $start }} - {{ $end }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ optional($latestAppointment->doctor->user)->name ?? 'Dokter' }}
                                    @if(optional($latestAppointment->doctor->poli)->name)
                                        • {{ $latestAppointment->doctor->poli->name }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold {{ $badge }}">
                            <i class="fas fa-circle-info mr-1.5 text-[10px]"></i>
                            Status: {{ strtoupper($latestAppointment->status ?? 'pending') }}
                        </span>

                        @if($latestAppointment->complaint)
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                <span class="font-semibold text-gray-700">Keluhan:</span>
                                {{ \Illuminate\Support\Str::limit($latestAppointment->complaint, 90) }}
                            </p>
                        @endif
                    </div>
                @else
                    <div
                        class="border border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-500 bg-gray-50/60">
                        Belum ada janji temu yang disetujui. Silakan buat janji temu baru melalui menu
                        <span class="font-semibold text-teal-700">“Buat Janji Temu”</span>.
                    </div>
                @endif
            </div>

            {{-- Janji Temu Mendatang --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-emerald-500"></span>
                        Janji Temu Mendatang
                    </h3>
                    <a href="{{ route('patient.appointments.index') }}"
                       class="text-xs text-teal-600 hover:text-teal-800 font-semibold">
                        Lihat semua →
                    </a>
                </div>

                @if($approvedAppointments->isEmpty())
                    <div
                        class="border border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-500 bg-gray-50/60">
                        Belum ada janji temu yang akan datang.
                    </div>
                @else
                    <ul class="space-y-3 text-sm max-h-64 overflow-y-auto pr-1">
                        @foreach($approvedAppointments->take(5) as $appointment)
                            @php
                                $date = Carbon::parse($appointment->booking_date)->translatedFormat('d M Y');
                                $start = optional($appointment->schedule)->start_time
                                    ? Carbon::parse($appointment->schedule->start_time)->format('H:i')
                                    : null;
                                $end = optional($appointment->schedule)->end_time
                                    ? Carbon::parse($appointment->schedule->end_time)->format('H:i')
                                    : null;
                            @endphp
                            <li
                                class="flex items-start gap-3 rounded-xl border border-gray-100 px-3 py-2.5 bg-gray-50/50">
                                <div class="mt-1">
                                    <span class="w-2 h-2 inline-block rounded-full bg-emerald-500"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ $date }}
                                        @if($start && $end)
                                            <span class="text-xs text-gray-500">• {{ $start }} - {{ $end }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ optional($appointment->doctor->user)->name ?? 'Dokter' }}
                                        @if(optional($appointment->doctor->poli)->name)
                                            • {{ $appointment->doctor->poli->name }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>

        {{-- RIWAYAT & RESEP --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">

            {{-- Riwayat Pemeriksaan Terakhir --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-sky-500"></span>
                        Riwayat Pemeriksaan Terakhir
                    </h3>
                    <a href="{{ route('patient.medical_records.index') }}"
                       class="text-xs text-teal-600 hover:text-teal-800 font-semibold">
                        Lihat semua →
                    </a>
                </div>

                @if($latestRecords->isEmpty())
                    <div
                        class="border border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-500 bg-gray-50/60">
                        Belum ada riwayat pemeriksaan yang tercatat.
                    </div>
                @else
                    <ul class="space-y-3 text-sm">
                        @foreach($latestRecords as $record)
                            @php
                                $date = $record->visit_date
                                    ? Carbon::parse($record->visit_date)->translatedFormat('d M Y')
                                    : '-';
                                $hasPrescription = $record->prescriptions->count() > 0;
                            @endphp
                            <li
                                class="flex items-start justify-between gap-3 border border-gray-100 rounded-xl px-3 py-2.5 bg-gray-50/40">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $date }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ optional($record->doctor->user)->name ?? 'Dokter' }}
                                        @if(optional($record->doctor->poli)->name)
                                            • {{ $record->doctor->poli->name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right space-y-1">
                                    <p class="text-[11px] {{ $hasPrescription ? 'text-emerald-600' : 'text-gray-400' }}">
                                        <i class="fas fa-prescription-bottle-alt mr-1"></i>
                                        {{ $hasPrescription ? 'Ada resep' : 'Tanpa resep' }}
                                    </p>
                                    <a href="{{ route('patient.medical_records.show', $record->id) }}"
                                       class="text-[11px] text-teal-600 hover:text-teal-800 font-semibold inline-flex items-center">
                                        Lihat detail
                                        <i class="fas fa-arrow-right ml-1 text-[9px]"></i>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Resep Siap Diambil (detail) --}}
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-emerald-500"></span>
                        Resep Siap Diambil
                    </h3>
                </div>

                @if($readyPrescriptions->isEmpty())
                    <div
                        class="border border-dashed border-gray-200 rounded-xl p-4 text-sm text-gray-500 bg-gray-50/60">
                        Belum ada resep dengan status <span class="font-semibold">READY</span>.
                    </div>
                @else
                    <ul class="space-y-3 text-sm max-h-64 overflow-y-auto pr-1">
                        @foreach($readyPrescriptions as $record)
                            @php
                                $date = $record->visit_date
                                    ? Carbon::parse($record->visit_date)->translatedFormat('d M Y')
                                    : '-';
                            @endphp
                            <li
                                class="border border-gray-100 rounded-xl px-3 py-2.5 bg-emerald-50/40 flex flex-col gap-1.5">
                                <p class="font-semibold text-gray-800">
                                    {{ $date }} • {{ optional($record->doctor->user)->name ?? 'Dokter' }}
                                </p>
                                <ul class="mt-0.5 text-xs text-gray-700 list-disc list-inside">
                                    @foreach($record->prescriptions as $prescription)
                                        @foreach($prescription->items as $item)
                                            <li>
                                                {{ optional($item->medicine)->name ?? 'Obat' }}
                                                — {{ $item->quantity }} {{ $item->unit ?? 'pcs' }}
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
@endsection
