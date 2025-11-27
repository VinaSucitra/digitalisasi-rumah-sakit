@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Transaksi &amp; Jadwal</p>
            <h2 class="text-3xl font-bold text-gray-800">
                Detail Janji Temu
            </h2>
        </div>

        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg shadow-md hover:bg-gray-300 transition">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-6 lg:p-8 border border-emerald-100 space-y-6">

        {{-- Status Badge --}}
        @php
            $status = $appointment->status;
            $badgeClasses = match ($status) {
                'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'rejected' => 'bg-red-100 text-red-700 border-red-200',
                'done'     => 'bg-sky-100 text-sky-800 border-sky-200',
                default    => 'bg-gray-100 text-gray-700 border-gray-200',
            };
        @endphp

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Status Janji Temu</p>
                <span class="inline-flex items-center px-3 py-1 mt-1 rounded-full border text-xs font-semibold {{ $badgeClasses }}">
                    {{ strtoupper($status) }}
                </span>
            </div>
            @if($appointment->queue_number)
                <div class="text-right">
                    <p class="text-sm text-gray-500">Nomor Antrian</p>
                    <p class="text-xl font-bold text-emerald-700">
                        {{ $appointment->queue_number }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Info Utama --}}
        <div class="grid md:grid-cols-2 gap-6 border-t border-gray-100 pt-6 text-sm">
            <div class="space-y-3">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Pasien</h3>
                    <p class="text-gray-900 font-semibold">
                        {{ $appointment->patient?->user?->name ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        No. RM: {{ $appointment->patient?->no_rm ?? '-' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Kontak Pasien</h3>
                    <p class="text-gray-800">
                        {{ $appointment->patient?->phone ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $appointment->patient?->address ?? '' }}
                    </p>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Dokter</h3>
                    <p class="text-gray-900 font-semibold">
                        {{ $appointment->doctor?->user?->name ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $appointment->doctor?->poli?->name ?? '-' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal &amp; Waktu</h3>
                    <p class="text-gray-900 font-semibold">
                        {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        @if($appointment->schedule)
                            {{ substr($appointment->schedule->start_time,0,5) }}
                            - {{ substr($appointment->schedule->end_time,0,5) }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Keluhan & catatan penolakan --}}
        <div class="border-t border-gray-100 pt-6 text-sm space-y-4">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Keluhan Pasien</h3>
                <p class="text-gray-800 whitespace-pre-line">
                    {{ $appointment->complaint }}
                </p>
            </div>

            @if($appointment->status === 'rejected' && $appointment->rejected_reason)
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Alasan Penolakan</h3>
                    <p class="text-red-700 whitespace-pre-line bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                        {{ $appointment->rejected_reason }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Tombol Aksi Cepat --}}
        <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
            <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
               class="inline-flex items-center px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
                Ubah Status
            </a>

            <form action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus janji temu ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-sm font-semibold text-red-600 hover:text-red-800">
                    Hapus Janji Temu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
