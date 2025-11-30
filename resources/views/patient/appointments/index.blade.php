@extends('layouts.patient')

@section('title', 'Daftar Janji Temu')
@section('page_title', 'Daftar Janji Temu')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-teal-500
                rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-emerald-100 mb-1">
                Layanan Pasien
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-calendar-check text-white/90"></i>
                Daftar Janji Temu Saya
            </h1>
            <p class="text-sm text-emerald-100 mt-1">
                Lihat status semua janji temu yang pernah Anda ajukan.
            </p>
        </div>

        <a href="{{ route('patient.appointments.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                  text-sm font-semibold shadow-md hover:bg-emerald-50 transition">
            <i class="fas fa-plus-circle mr-2 text-xs"></i>
            Buat Janji Temu Baru
        </a>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6">
        @if($appointments->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-10 text-center gap-3">
                <div
                    class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-gray-800">
                    Belum ada janji temu yang dibuat
                </h2>
                <p class="text-sm text-gray-500 max-w-md">
                    Anda dapat membuat janji temu baru dengan memilih dokter dan poli sesuai kebutuhan.
                </p>
                <a href="{{ route('patient.appointments.create') }}"
                   class="mt-2 inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow">
                    <i class="fas fa-plus mr-2 text-xs"></i> Buat Janji Temu
                </a>
            </div>
        @else
            {{-- TABEL DATA --}}
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Riwayat Janji Temu
                </h2>
                <span class="text-[11px] text-gray-500">
                    Total: {{ $appointments->count() }} janji temu
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-2 text-left">Tanggal & Waktu</th>
                            <th class="px-4 py-2 text-left">Dokter</th>
                            <th class="px-4 py-2 text-left">Poli</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($appointments as $a)
                            @php
                                $status = strtolower($a->status ?? 'pending');
                                $badge = match($status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    'done'     => 'bg-sky-100 text-sky-700',
                                    default    => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                {{-- Tanggal & Waktu --}}
                                <td class="px-4 py-3 align-top whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($a->booking_date ?? now())->format('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-gray-500">
                                        ({{ substr($a->schedule->start_time ?? 'N/A', 0, 5) }}
                                        - {{ substr($a->schedule->end_time ?? 'N/A', 0, 5) }})
                                    </div>
                                </td>

                                {{-- Dokter --}}
                                <td class="px-4 py-3 align-top whitespace-nowrap">
                                    <div class="font-medium text-gray-800">
                                        {{ $a->doctor->user->name ?? 'Dokter Tidak Dikenal' }}
                                    </div>
                                </td>

                                {{-- Poli --}}
                                <td class="px-4 py-3 align-top whitespace-nowrap text-gray-700">
                                    {{ $a->doctor->poli->name ?? 'N/A' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 align-top">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold {{ $badge }}">
                                        {{ strtoupper($a->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
