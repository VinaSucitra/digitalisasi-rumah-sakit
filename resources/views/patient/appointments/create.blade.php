@extends('layouts.patient')

@section('title', 'Buat Janji Temu')
@section('page_title', 'Buat Janji Temu')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6 md:p-8 max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Buat Janji Temu</h2>
    <p class="text-gray-600 mb-6">
        Silakan isi formulir di bawah untuk membuat janji temu dengan dokter.
    </p>

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded border border-red-300 bg-red-50 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- DATA PASIEN (read-only) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pasien</label>
                <input type="text"
                       class="w-full p-2 border rounded bg-gray-100"
                       value="{{ auth()->user()->name }}"
                       readonly>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Booking</label>
                <input type="date"
                       name="booking_date"
                       value="{{ old('booking_date') }}"
                       class="w-full p-2 border rounded @error('booking_date') border-red-500 @enderror"
                       required>
            </div>
        </div>

        {{-- PILIH POLI --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Poli</label>
            <select name="poli_id"
                    class="w-full p-2 border rounded @error('poli_id') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Poli --</option>
                @foreach ($polis as $poli)
                    <option value="{{ $poli->id }}"
                        {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                        {{ $poli->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- PILIH DOKTER --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Dokter</label>
            <select name="doctor_id"
                    class="w-full p-2 border rounded @error('doctor_id') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Dokter --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{-- Nama dokter dari relasi user --}}
                        {{ $doctor->user->name }}
                        {{-- Nama poli jika ada --}}
                        @if ($doctor->poli)
                            - ({{ $doctor->poli->name }})
                        @else
                            - (Poli belum diset)
                        @endif
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">
                Daftar dokter di atas sudah termasuk informasi poli masing-masing.
            </p>
        </div>

        {{-- PILIH JADWAL --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Jadwal Konsultasi</label>
            <select name="schedule_id"
                    class="w-full p-2 border rounded @error('schedule_id') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Slot Jadwal --</option>
                @foreach ($schedules as $schedule)
                    <option value="{{ $schedule->id }}"
                        {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                        {{-- contoh label: Senin 08:00 - 08:30 | dr. A (Poli Umum) --}}
                        {{ ucfirst($schedule->day_of_week) }}
                        {{ substr($schedule->start_time, 0, 5) }}
                        -
                        {{ substr($schedule->end_time, 0, 5) }}
                        |
                        {{ $schedule->doctor->user->name }}
                        @if($schedule->doctor->poli)
                            ({{ $schedule->doctor->poli->name }})
                        @endif
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">
                Slot jadwal sudah menggunakan durasi 30 menit untuk setiap konsultasi.
            </p>
        </div>

        {{-- KELUHAN --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Keluhan Singkat</label>
            <textarea name="complaint"
                      rows="4"
                      class="w-full p-2 border rounded @error('complaint') border-red-500 @enderror"
                      placeholder="Contoh: Demam sejak 3 hari, batuk kering, pusing..."
                      required>{{ old('complaint') }}</textarea>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="pt-2 flex justify-end gap-3">
            <a href="{{ route('patient.appointments.index') }}"
               class="px-4 py-2 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 shadow">
                Kirim Permohonan Janji Temu
            </button>
        </div>
    </form>
</div>
@endsection
