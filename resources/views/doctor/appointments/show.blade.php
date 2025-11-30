@extends('layouts.doctor')

@section('page_title', 'Detail Janji Temu')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-xs text-teal-600 font-semibold uppercase tracking-wide">Janji Temu</p>
            <h2 class="text-2xl font-bold text-gray-900 mt-1">Detail Janji Temu</h2>
        </div>

        <a href="{{ route('doctor.appointments.index') }}"
           class="px-4 py-2 text-xs md:text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold">
            Kembali ke Daftar
        </a>
    </div>

    {{-- INFORMASI UTAMA --}}
    <div class="grid md:grid-cols-2 gap-6 mb-6 text-sm">
        <div class="space-y-3">
            <div>
                <span class="block text-gray-500 text-xs">Nama Pasien</span>
                <p class="font-semibold text-gray-900">
                    {{ $appointment->patient->user->name ?? 'Pasien Tidak Dikenal' }}
                </p>
            </div>

            <div>
                <span class="block text-gray-500 text-xs">Poli</span>
                <p class="font-semibold text-gray-900">
                    {{ $appointment->doctor->poli->name ?? '-' }}
                </p>
            </div>

            <div>
                <span class="block text-gray-500 text-xs">Tanggal Konsultasi</span>
                <p class="font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($appointment->booking_date)->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <span class="block text-gray-500 text-xs">Jam</span>
                <p class="font-semibold text-gray-900">
                    @if($appointment->schedule)
                        {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('H:i') }} WIB
                        – {{ \Carbon\Carbon::parse($appointment->schedule->end_time)->format('H:i') }} WIB
                    @else
                        -
                    @endif
                </p>
            </div>

            <div>
                <span class="block text-gray-500 text-xs">Status Saat Ini</span>
                @php
                    $status = $appointment->status ?? 'pending';
                    $color = match($status) {
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'rejected' => 'bg-rose-100 text-rose-700',
                        'done'     => 'bg-sky-100 text-sky-700',
                        default    => 'bg-amber-100 text-amber-700',
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                    {{ strtoupper($status) }}
                </span>
            </div>

            @if($appointment->rejected_reason)
                <div>
                    <span class="block text-gray-500 text-xs">Alasan Penolakan</span>
                    <p class="text-sm text-gray-800">
                        {{ $appointment->rejected_reason }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- KELUHAN --}}
    <div class="mb-6">
        <span class="block text-gray-500 text-xs mb-1">Keluhan / Alasan Janji Temu</span>
        <div class="p-4 rounded-lg bg-gray-50 text-sm text-gray-800">
            {{ $appointment->complaint ?: '-' }}
        </div>
    </div>

    {{-- FORM UPDATE STATUS OLEH DOKTER --}}
    <div class="border-t pt-6 mt-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fas fa-clipboard-check text-teal-600"></i>
            Ubah Status Janji Temu
        </h3>

        <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-4">
                {{-- Pilihan Status --}}
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-1">
                        Pilih Status
                    </label>
                    <select name="status" id="status"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500 @error('status') border-rose-500 @enderror">
                        <option value="approved" {{ old('status', $appointment->status) === 'approved' ? 'selected' : '' }}>
                            Approved (Disetujui)
                        </option>
                        <option value="rejected" {{ old('status', $appointment->status) === 'rejected' ? 'selected' : '' }}>
                            Rejected (Ditolak)
                        </option>
                        <option value="done" {{ old('status', $appointment->status) === 'done' ? 'selected' : '' }}>
                            Done (Selesai)
                        </option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alasan Penolakan --}}
                <div>
                    <label for="rejected_reason" class="block text-xs font-semibold text-gray-700 mb-1">
                        Alasan Penolakan (wajib jika memilih Rejected)
                    </label>
                    <input type="text" name="rejected_reason" id="rejected_reason"
                           value="{{ old('rejected_reason', $appointment->rejected_reason) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-teal-500 focus:border-teal-500 @error('rejected_reason') border-rose-500 @enderror"
                           placeholder="Contoh: Jadwal bentrok, pasien diminta pilih hari lain">
                    @error('rejected_reason')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-2">
                <button type="submit"
                        class="inline-flex items-center px-5 py-2 rounded-lg bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan Status
                </button>

                {{-- Shortcut ke Rekam Medis jika sudah di-approve --}}
                @if($appointment->status === 'approved')
                    <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $appointment->id]) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs md:text-sm font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-notes-medical mr-2"></i> Mulai Pemeriksaan / Rekam Medis
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
