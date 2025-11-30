@extends('layouts.doctor')

@section('page_title', 'Janji Temu Saya')

@section('content')

<div class="space-y-7">

    {{-- HERO / HEADER --}}
    <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-md px-6 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1 flex items-center gap-2">
                <i class="fas fa-calendar-check text-teal-50"></i>
                Janji Temu
            </p>
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                Daftar Janji Temu Saya
            </h1>
            <p class="text-sm text-teal-100 mt-1">
                Pantau janji temu pasien, validasi permintaan baru, dan mulai pemeriksaan dengan lebih cepat.
            </p>
        </div>

        <div class="flex flex-col items-start md:items-end gap-2 text-xs text-teal-50">
            <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2 flex flex-col gap-1">
                <p class="text-[10px] uppercase tracking-widest text-teal-100">Ringkasan Status</p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-rose-500/20 border border-rose-300/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                        Pending: <span class="font-semibold">{{ $counts['pending'] ?? 0 }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-500/20 border border-emerald-300/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                        Approved: <span class="font-semibold">{{ $counts['approved'] ?? 0 }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white/10 border border-white/40">
                        Total: <span class="font-semibold">{{ $counts['all'] ?? $appointments->total() }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
            <i class="fas fa-circle-check mt-0.5"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
            <i class="fas fa-circle-exclamation mt-0.5"></i>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- FILTER TABS --}}
    <div class="bg-white/70 border border-teal-50 rounded-2xl px-4 py-3 flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <div class="flex flex-wrap gap-2 text-xs sm:text-sm font-medium">

            {{-- Semua --}}
            <a href="{{ route('doctor.appointments.index') }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                      {{ empty($statusFilter)
                            ? 'bg-teal-600 text-white shadow-sm'
                            : 'bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-100' }}">
                <i class="fas fa-layer-group text-[11px]"></i>
                <span>Semua</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 {{ empty($statusFilter) ? 'border border-white/40' : 'bg-white text-teal-700 border border-teal-200' }}">
                    {{ $counts['all'] ?? $appointments->total() }}
                </span>
            </a>

            {{-- Pending --}}
            <a href="{{ route('doctor.appointments.index', ['status' => 'pending']) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                      {{ $statusFilter === 'pending'
                            ? 'bg-rose-600 text-white shadow-sm'
                            : 'bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-100' }}">
                <i class="fas fa-hourglass-half text-[11px]"></i>
                <span>Menunggu Validasi</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 {{ $statusFilter === 'pending' ? 'border border-white/40' : 'bg-white text-rose-700 border border-rose-200' }}">
                    {{ $counts['pending'] ?? 0 }}
                </span>
            </a>

            {{-- Approved --}}
            <a href="{{ route('doctor.appointments.index', ['status' => 'approved']) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                      {{ $statusFilter === 'approved'
                            ? 'bg-emerald-600 text-white shadow-sm'
                            : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100' }}">
                <i class="fas fa-badge-check text-[11px]"></i>
                <span>Sudah Disetujui</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 {{ $statusFilter === 'approved' ? 'border border-white/40' : 'bg-white text-emerald-700 border border-emerald-200' }}">
                    {{ $counts['approved'] ?? 0 }}
                </span>
            </a>
        </div>

        {{-- Keterangan kecil --}}
        <p class="text-[11px] text-gray-500">
            Tips: mulai dari tab <span class="font-semibold text-rose-600">Menunggu Validasi</span> untuk memproses janji baru.
        </p>
    </div>

    {{-- TABLE WRAPPER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead class="bg-teal-50/70">
                    <tr class="text-[11px] uppercase tracking-wide text-gray-600 border-b border-teal-100">
                        <th class="py-3 px-4 text-left">Pasien</th>
                        <th class="py-3 px-4 text-left">Tanggal & Jam</th>
                        <th class="py-3 px-4 text-left">Keluhan Singkat</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $app)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Pasien --}}
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-800">
                                        {{ $app->patient->user->name ?? 'Pasien Tidak Dikenal' }}
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        ID: {{ $app->id }}
                                    </span>
                                </div>
                            </td>

                            {{-- Tanggal & Jam --}}
                            <td class="py-3 px-4 text-gray-600">
                                <span class="block font-medium">
                                    {{ \Carbon\Carbon::parse($app->booking_date)->format('d M Y') }}
                                </span>
                                @if($app->schedule)
                                    <span class="block text-[11px] text-teal-600 font-semibold">
                                        {{ \Carbon\Carbon::parse($app->schedule->start_time)->format('H:i') }} WIB
                                    </span>
                                @else
                                    <span class="block text-[11px] text-gray-400">
                                        Jadwal belum tersedia
                                    </span>
                                @endif
                            </td>

                            {{-- Keluhan --}}
                            <td class="py-3 px-4 text-gray-500">
                                {{ \Illuminate\Support\Str::limit($app->complaint, 50) }}
                            </td>

                            {{-- Status --}}
                            <td class="py-3 px-4">
                                @php
                                    $status = $app->status ?? 'pending';
                                    [$badgeColor, $icon] = match($status) {
                                        'approved' => ['bg-emerald-100 text-emerald-700', 'fa-circle-check'],
                                        'rejected' => ['bg-rose-100 text-rose-700', 'fa-circle-xmark'],
                                        'done'     => ['bg-sky-100 text-sky-700', 'fa-circle-check'],
                                        default    => ['bg-amber-100 text-amber-700', 'fa-hourglass-half'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] font-semibold {{ $badgeColor }}">
                                    <i class="fas {{ $icon }}"></i>
                                    {{ strtoupper($status) }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('doctor.appointments.show', $app->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold bg-teal-600 text-white hover:bg-teal-700 shadow-sm transition">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10">
                                <div class="flex flex-col items-center justify-center text-gray-500 text-sm">
                                    <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center mb-2">
                                        <i class="fas fa-calendar-xmark text-teal-400 text-lg"></i>
                                    </div>
                                    <p class="font-semibold">Tidak ada janji temu yang ditemukan untuk Anda.</p>
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        Jika pasien membuat janji baru, data akan muncul otomatis di halaman ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($appointments->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
