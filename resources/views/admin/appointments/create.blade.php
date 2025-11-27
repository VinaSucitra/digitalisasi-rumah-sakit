@extends('admin.layouts.app')

@section('title', 'Buat Janji Temu')

@section('content')
<div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- Breadcrumb / judul kecil --}}
    <div class="flex items-center text-sm text-emerald-900/80 space-x-2 mb-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">
            <i class="fas fa-calendar-check"></i>
        </span>
        <div>
            <p class="font-semibold tracking-wide">Manajemen Janji Temu</p>
            <p class="text-xs text-emerald-900/60">Buat janji temu baru antara pasien dan dokter.</p>
        </div>
    </div>

    {{-- Card utama --}}
    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 border border-emerald-900/5">
        <div class="px-6 sm:px-8 py-5 border-b border-emerald-900/5 flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-emerald-950">
                    Buat Janji Temu Baru
                </h1>
                <p class="mt-1 text-sm text-emerald-900/70">
                    Isi detail berikut untuk menjadwalkan janji temu pasien.
                </p>
            </div>
            <a href="{{ route('admin.appointments.index') }}"
               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-900/10 text-sm font-semibold text-emerald-900 hover:bg-emerald-50 transition">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.appointments.store') }}" method="POST" class="px-6 sm:px-8 py-6 space-y-6">
            @csrf

            {{-- Row 1: Pasien & Dokter --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Pasien --}}
                <div>
                    <label for="patient_id" class="block text-sm font-semibold text-emerald-950 mb-1.5">
                        Pasien
                    </label>
                    <select name="patient_id" id="patient_id"
                            class="w-full rounded-xl border border-emerald-900/15 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white @error('patient_id') border-red-400 @enderror"
                            required>
                        <option value="">— Pilih Pasien —</option>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->no_rm }} — {{ $p->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dokter + Jadwal --}}
                <div>
                    <label for="schedule_id" class="block text-sm font-semibold text-emerald-950 mb-1.5">
                        Dokter & Jadwal Praktik
                    </label>
                    <select name="schedule_id" id="schedule_id"
                            class="w-full rounded-xl border border-emerald-900/15 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white @error('schedule_id') border-red-400 @enderror"
                            required>
                        <option value="">— Pilih Jadwal Dokter —</option>
                        @foreach ($schedules as $s)
                            <option value="{{ $s->id }}" {{ old('schedule_id') == $s->id ? 'selected' : '' }}>
                                dr. {{ $s->doctor->user->name }}
                                • {{ ucfirst($s->day_of_week) }}
                                • {{ substr($s->start_time,0,5) }}–{{ substr($s->end_time,0,5) }}
                            </option>
                        @endforeach
                    </select>
                    @error('schedule_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-[11px] text-emerald-900/60">
                        Jadwal diambil dari data <strong>Jadwal Dokter</strong>. Dokter otomatis mengikuti jadwal yang dipilih.
                    </p>
                </div>
            </div>

            {{-- Row 2: Tanggal & Status --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Tanggal --}}
                <div>
                    <label for="booking_date" class="block text-sm font-semibold text-emerald-950 mb-1.5">
                        Tanggal Konsultasi
                    </label>
                    <input type="date" name="booking_date" id="booking_date"
                           value="{{ old('booking_date') }}"
                           class="w-full rounded-xl border border-emerald-900/15 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white @error('booking_date') border-red-400 @enderror"
                           required>
                    @error('booking_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-[11px] text-emerald-900/60">
                        Usahakan tanggal sesuai dengan hari praktik pada jadwal yang dipilih.
                    </p>
                </div>

                {{-- Status awal (opsional, default pending) --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-emerald-950 mb-1.5">
                        Status Janji Temu
                    </label>
                    <select name="status" id="status"
                            class="w-full rounded-xl border border-emerald-900/15 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white @error('status') border-red-400 @enderror">
                        <option value="pending" {{ old('status','pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Keluhan --}}
            <div>
                <label for="complaint" class="block text-sm font-semibold text-emerald-950 mb-1.5">
                    Keluhan / Alasan Janji Temu
                </label>
                <textarea name="complaint" id="complaint" rows="4"
                          class="w-full rounded-xl border border-emerald-900/15 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white @error('complaint') border-red-400 @enderror"
                          placeholder="Contoh: Demam 3 hari, batuk, dan sakit kepala.">{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="pt-3 border-t border-emerald-900/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('admin.appointments.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-900/80 hover:text-emerald-900">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Batal & Kembali
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow-md shadow-emerald-900/20 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <i class="fas fa-save text-xs"></i>
                    Simpan Janji Temu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
