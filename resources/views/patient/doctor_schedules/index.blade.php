@extends('layouts.patient')

@section('title', 'Jadwal Dokter')
@section('page_title', 'Jadwal Dokter')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-teal-500
                rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-emerald-100 mb-1">
                Informasi Jadwal Dokter
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-user-md text-white/90"></i>
                Jadwal Dokter
            </h1>
            <p class="text-sm text-emerald-100 mt-1">
                Lihat jadwal praktik dokter sebelum membuat janji temu.
            </p>
        </div>

        <a href="{{ route('patient.appointments.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                  text-sm font-semibold shadow-md hover:bg-emerald-50 transition">
            <i class="fas fa-calendar-plus mr-2 text-xs"></i>
            Buat Janji Temu
        </a>
    </div>

    {{-- KONTEN --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 md:p-8 max-w-5xl">

        @if($doctorDetails->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-10 text-center gap-3">
                <div
                    class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-user-md-slash text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-gray-800">
                    Belum ada jadwal dokter yang tersedia
                </h2>
                <p class="text-sm text-gray-500 max-w-md">
                    Silakan cek kembali beberapa saat lagi atau hubungi bagian pendaftaran rumah sakit
                    untuk informasi terbaru mengenai jadwal praktik dokter.
                </p>
            </div>
        @else
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Daftar Jadwal Praktik Dokter
                </h2>
                <span class="text-[11px] text-gray-500">
                    Total Dokter: {{ $doctorDetails->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3 text-left w-1/4">Dokter</th>
                            <th class="px-4 py-3 text-left w-1/4">Poli</th>
                            <th class="px-4 py-3 text-left w-2/4">Jadwal Praktik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($doctorDetails as $doctor)
                            @php
                                // Urutkan jadwal berdasarkan hari
                                $sortedSchedules = $doctor->schedules->sortBy(function($schedule) {
                                    $daysOrder = [
                                        'senin' => 1, 'selasa' => 2, 'rabu' => 3,
                                        'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7
                                    ];
                                    return $daysOrder[strtolower($schedule->day_of_week)] ?? 99;
                                });
                            @endphp

                            <tr class="hover:bg-gray-50">
                                {{-- DOKTER --}}
                                <td class="px-4 py-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="hidden sm:flex items-center justify-center w-9 h-9 rounded-full bg-emerald-50 text-emerald-600">
                                            <i class="fas fa-user-md text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $doctor->user->name ?? 'Dokter Dihapus' }}
                                            </p>
                                            @if($doctor->sip)
                                                <p class="text-[11px] text-gray-500">
                                                    SIP: {{ $doctor->sip }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- POLI --}}
                                <td class="px-4 py-4 align-top">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold
                                               bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="fas fa-hospital-user mr-1"></i>
                                        {{ $doctor->poli->name ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- JADWAL PRAKTIK --}}
                                <td class="px-4 py-4 align-top">
                                    @if ($sortedSchedules->count())
                                        <ul class="space-y-1.5 text-xs text-gray-800">
                                            @foreach($sortedSchedules as $schedule)
                                                @php
                                                    $day  = ucfirst($schedule->day_of_week);
                                                    $start = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                                                    $end   = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
                                                @endphp
                                                <li class="flex items-center justify-between gap-3">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full
                                                               bg-gray-100 text-gray-700 font-semibold text-[11px]">
                                                        {{ $day }}
                                                    </span>
                                                    <span class="font-medium text-gray-800">
                                                        {{ $start }} - {{ $end }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-xs text-gray-500 italic">
                                            Tidak ada jadwal aktif.
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-[11px] text-gray-500">
                * Jadwal dapat berubah sewaktu-waktu. Untuk kepastian, harap konfirmasi ke bagian pendaftaran
                ketika akan berkunjung ke rumah sakit.
            </p>
        @endif
    </div>
</div>
@endsection
