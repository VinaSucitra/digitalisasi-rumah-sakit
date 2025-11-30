@extends('layouts.doctor')

@section('page_title', 'Detail Rekam Medis')

@section('content')

<div class="space-y-6">
    
    {{-- HEADER & TOMBOL AKSI --}}
    <div class="flex items-center justify-between border-b pb-3 border-teal-200">
        <h1 class="text-xl font-bold text-teal-900 flex items-center gap-2">
            <i class="fas fa-file-alt text-teal-600"></i>
            Detail Rekam Medis
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('doctor.medical_records.edit', $medical_record->id) }}" 
               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                <i class="fas fa-edit mr-2"></i> Edit RM
            </a>
            <a href="{{ route('doctor.medical_records.index') }}" 
               class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    {{-- KARTU INFO UTAMA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- INFORMASI PASIEN DAN KUNJUNGAN --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-md p-5 space-y-3 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Informasi Kunjungan</h3>
            
            <div class="text-sm">
                <p class="font-medium text-gray-800">Pasien</p>
                <p class="text-teal-600 font-semibold">{{ $medical_record->patient->user->name ?? 'N/A' }}</p>
            </div>
            <div class="text-sm">
                <p class="font-medium text-gray-800">Dokter</p>
                <p class="text-gray-600">Dr. {{ $medical_record->doctor->user->name ?? 'N/A' }}</p>
            </div>
            <div class="text-sm">
                <p class="font-medium text-gray-800">Tanggal Kunjungan</p>
                <p class="text-gray-600">{{ \Carbon\Carbon::parse($medical_record->visit_date)->format('d F Y') }}</p>
            </div>
            <div class="text-sm">
                <p class="font-medium text-gray-800">Janji Temu ID</p>
                <p class="text-gray-600">#{{ $medical_record->appointment_id }}</p>
            </div>
        </div>

        {{-- DIAGNOSIS & TINDAKAN --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-5 space-y-4 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Diagnosis & Tindakan</h3>
            
            <div>
                <p class="font-medium text-gray-800">Diagnosis</p>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-700">{{ $medical_record->diagnosis ?? '-' }}</p>
            </div>
            
            <div>
                <p class="font-medium text-gray-800">Tindakan Medis</p>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-700">{{ $medical_record->treatment ?? 'Tidak ada tindakan.' }}</p>
            </div>

            <div>
                <p class="font-medium text-gray-800">Catatan Dokter</p>
                <p class="mt-1 text-sm text-gray-700">{{ $medical_record->notes ?? 'Tidak ada catatan tambahan.' }}</p>
            </div>
        </div>
    </div>

    {{-- BAGIAN RESEP --}}
    <div class="bg-white rounded-xl shadow-md p-5 space-y-4 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 border-b pb-2 flex items-center justify-between">
            Resep Obat 
            @if($medical_record->prescriptions->isNotEmpty())
                 <span class="text-xs font-normal text-gray-500">Status Resep: 
                     <span class="font-semibold text-amber-600">
                         {{ ucfirst($medical_record->prescriptions->first()->status ?? 'pending') }}
                     </span>
                 </span>
            @endif
        </h3>

        @if($medical_record->prescriptions->isEmpty() || $medical_record->prescriptions->first()->items->isEmpty())
            <div class="text-sm text-gray-500 py-3 text-center bg-gray-50 rounded-lg">
                Tidak ada resep obat yang dikeluarkan pada kunjungan ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-teal-50">
                        <tr class="text-[11px] uppercase tracking-wide text-gray-600">
                            <th class="py-2 px-4 text-left">Nama Obat</th>
                            <th class="py-2 px-4 text-left">Jumlah</th>
                            <th class="py-2 px-4 text-left">Dosis/Aturan Pakai</th>
                            <th class="py-2 px-4 text-left">Stok Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($medical_record->prescriptions->first()->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 font-medium text-gray-800">{{ $item->medicine->name ?? 'N/A' }}</td>
                                <td class="py-2 px-4 text-teal-600 font-semibold">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 text-gray-600">{{ $item->dosage ?? '-' }}</td>
                                <td class="py-2 px-4 text-gray-500">{{ $item->medicine->stock ?? '?' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection