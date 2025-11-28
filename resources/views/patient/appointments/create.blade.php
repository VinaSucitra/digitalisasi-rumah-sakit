@extends('layouts.patient')

@section('title', 'Buat Janji Temu')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <h2 class="text-2xl font-bold mb-6">Buat Janji Temu Baru</h2>

    <form action="{{ route('patient.appointments.store') }}" method="POST">
        @csrf

        {{-- PILIH POLI --}}
        <div class="mb-4">
            <label class="font-semibold">Pilih Poli</label>
            <select name="poli_id" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Poli --</option>
                @foreach($polis as $poli)
                <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- PILIH DOKTER --}}
        <div class="mb-4">
            <label class="font-semibold">Pilih Dokter</label>
            <select name="doctor_id" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Dokter --</option>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}">{{ $doctor->name }} - ({{ $doctor->doctorDetail->poli->name }})</option>
                @endforeach
            </select>
        </div>

        {{-- PILIH JADWAL --}}
        <div class="mb-4">
            <label class="font-semibold">Pilih Jadwal</label>
            <select name="schedule_id" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih Jadwal --</option>
                @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}">
                    {{ $schedule->day }} - {{ $schedule->start_time }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- KELUHAN --}}
        <div class="mb-4">
            <label class="font-semibold">Keluhan Singkat</label>
            <textarea name="complaint" rows="3" class="w-full border p-3 rounded" required></textarea>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Buat Janji Temu
        </button>
    </form>
</div>
@endsection
