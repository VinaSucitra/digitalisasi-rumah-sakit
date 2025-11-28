@extends('layouts.doctor')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    
    <h2 class="text-2xl font-semibold mb-4">Detail Janji Temu</h2>

    <div class="space-y-3">
        <div>
            <span class="font-semibold">Nama Pasien:</span>
            {{ $appointment->patient->name }}
        </div>

        <div>
            <span class="font-semibold">Poli:</span>
            {{ $appointment->poli->name }}
        </div>

        <div>
            <span class="font-semibold">Tanggal:</span>
            {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
        </div>

        <div>
            <span class="font-semibold">Jam:</span>
            {{ $appointment->time }}
        </div>

        <div>
            <span class="font-semibold">Keluhan:</span>
            <p class="mt-1">{{ $appointment->complaint }}</p>
        </div>

        <div>
            <span class="font-semibold">Status:</span>
            <span class="px-3 py-1 rounded bg-blue-100 text-blue-700">
                {{ ucfirst($appointment->status) }}
            </span>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('doctor.appointments.index') }}" 
           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
            Kembali
        </a>

        <a href="{{ route('doctor.medical-records.create', $appointment->id) }}" 
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
            Buat Rekam Medis
        </a>
    </div>
</div>
@endsection
