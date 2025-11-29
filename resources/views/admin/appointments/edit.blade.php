@extends('admin.layouts.app')

@section('title', 'Manajemen Janji Temu')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- Breadcrumb / header kecil --}}
    <div class="flex items-center text-sm text-emerald-900/80 space-x-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">
            <i class="fas fa-calendar-check"></i>
        </span>
        <div>
            <p class="font-semibold tracking-wide">Dashboard Admin</p>
            <p class="text-xs text-emerald-900/60">Manajemen Janji Temu Pasien &amp; Dokter.</p>
        </div>
    </div>

    {{-- Header judul + tombol --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950">
                Edit Janji Temu (Appointment)
            </h1>
            <p class="mt-1 text-sm text-emerald-900/70">
                Perbarui detail janji temu.
            </p>
        </div>

        <a href="{{ route('admin.appointments.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gray-300 text-gray-700 text-sm font-semibold shadow-md shadow-gray-900/20 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Card tabel --}}
    <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 border border-emerald-900/5 overflow-hidden">
        {{-- Header card --}}
        <div class="px-6 sm:px-8 py-3 border-b border-emerald-900/5 flex items-center justify-between text-xs sm:text-sm text-emerald-900/70">
            <span class="font-semibold">
                Edit Janji Temu
            </span>
        </div>

        {{-- Form untuk mengedit Janji Temu --}}
        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Untuk mengupdate data menggunakan metode PUT --}}
            <div class="px-6 sm:px-8 py-6 space-y-4">
                
                {{-- Tanggal & waktu --}}
                <div class="mb-4">
                    <label for="booking_date" class="block font-semibold text-gray-700 mb-1">Tanggal dan Waktu</label>
                    <input type="datetime-local" id="booking_date" name="booking_date" 
                        value="{{ old('booking_date', $appointment->booking_date ? $appointment->booking_date->format('Y-m-d\TH:i') : '') }}" 
                        class="w-full p-2 rounded-md border border-emerald-300 @error('booking_date') border-red-500 @enderror" required>
                    @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Pasien --}}
                <div class="mb-4">
                    <label for="patient_id" class="block font-semibold text-gray-700 mb-1">Pasien</label>
                    <select name="patient_id" id="patient_id" class="w-full p-2 rounded-md border border-emerald-300 @error('patient_id') border-red-500 @enderror" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name ?? 'Pasien Tanpa Nama' }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Dokter --}}
                <div class="mb-4">
                    <label for="doctor_id" class="block font-semibold text-gray-700 mb-1">Dokter</label>
                    <select name="doctor_id" id="doctor_id" class="w-full p-2 rounded-md border border-emerald-300 @error('doctor_id') border-red-500 @enderror" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name ?? 'Dokter Tanpa Nama' }} ({{ $doctor->poli->name ?? 'Poli T/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-4">
                    <label for="complaint" class="block font-semibold text-gray-700 mb-1">Keluhan</label>
                    <textarea id="complaint" name="complaint" rows="3" class="w-full p-2 rounded-md border border-emerald-300 @error('complaint') border-red-500 @enderror" required>{{ old('complaint', $appointment->complaint) }}</textarea>
                    @error('complaint') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label for="status" class="block font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full p-2 rounded-md border border-emerald-300 @error('status') border-red-500 @enderror">
                        @php
                            $statuses = ['pending', 'approved', 'rejected', 'done'];
                        @endphp
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $appointment->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tombol submit --}}
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow-md shadow-emerald-900/20 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <i class="fas fa-save text-xs"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection