@extends('layouts.patient')

@section('title', 'Detail Rekam Medis')
@section('page_title', 'Detail Rekam Medis')

@section('content')
    <div class="bg-white shadow-xl rounded-xl p-6 md:p-8 max-w-3xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Detail Rekam Medis</h2>
        <p class="text-gray-600 mb-6">Informasi lengkap mengenai pemeriksaan Anda.</p>

        <div class="mb-4">
            <strong>Tanggal Pemeriksaan:</strong> {{ $medicalRecord->visit_date }}
        </div>

        <div class="mb-4">
            <strong>Dokter:</strong> {{ $medicalRecord->doctor->user->name }}
        </div>

        <div class="mb-4">
            <strong>Poli/Klinik:</strong> {{ $medicalRecord->doctor->poli->name }}
        </div>

        <div class="mb-4">
            <strong>Resep:</strong>
            @if($medicalRecord->prescriptions->count() > 0)
                <ul>
                    @foreach($medicalRecord->prescriptions as $prescription)
                        <li>
                            <strong>{{ $prescription->name }}</strong>
                            <ul>
                                @foreach($prescription->items as $item)
                                    <li>{{ $item->medicine->name }} - {{ $item->quantity }} pcs</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            @else
                <span class="text-red-500">Tidak ada resep untuk pemeriksaan ini.</span>
            @endif
        </div>

        <a href="{{ route('patient.medical_records.index') }}" class="text-teal-500 hover:text-teal-700">Kembali ke Daftar Rekam Medis</a>
    </div>
@endsection
