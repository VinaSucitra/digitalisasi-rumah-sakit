@extends('layouts.doctor')

@section('page_title', 'Buat Rekam Medis Baru')

@section('content')

<div class="space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex items-center justify-between border-b pb-3 border-teal-200">
        <h1 class="text-xl font-bold text-teal-900 flex items-center gap-2">
            <i class="fas fa-plus-square text-teal-600"></i>
            Buat Rekam Medis Baru
        </h1>
        <a href="{{ route('doctor.medical_records.index') }}" 
           class="text-sm font-medium text-gray-500 hover:text-teal-600">
            &larr; Kembali ke Antrean
        </a>
    </div>

    {{-- KARTU INFORMASI PASIEN --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 shadow-sm">
        <h3 class="font-bold text-lg text-teal-900">Pasien: {{ $appointment->patient->user->name ?? 'N/A' }}</h3>
        <p class="text-sm text-teal-700">Poli: {{ $appointment->doctor->poli->name ?? 'Umum' }}</p>
        <p class="text-xs text-teal-600 mt-1">
            Janji Temu: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }} 
            ({{ date('H:i', strtotime($appointment->schedule->start_time)) ?? '-' }} WIB)
        </p>
        <p class="mt-2 text-sm italic text-gray-700">
            Keluhan: **{{ $appointment->complaint }}**
        </p>
        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
    </div>

    {{-- FORM REKAM MEDIS --}}
    <form action="{{ route('doctor.medical_records.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
        <input type="hidden" name="visit_date" value="{{ now()->toDateString() }}"> {{-- Diambil dari tanggal kunjungan hari ini --}}

        {{-- BAGIAN DIAGNOSIS & TINDAKAN --}}
        <div class="bg-white rounded-xl shadow-md p-6 space-y-5 border border-gray-100">
            <h2 class="text-base font-semibold text-teal-800 border-b pb-2">1. Diagnosis & Tindakan Medis</h2>

            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 required">Diagnosis Utama</label>
                <textarea name="diagnosis" id="diagnosis" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('diagnosis') border-rose-500 @enderror" 
                          required>{{ old('diagnosis') }}</textarea>
                @error('diagnosis') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="treatment" class="block text-sm font-medium text-gray-700">Tindakan Medis</label>
                <textarea name="treatment" id="treatment" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('treatment') border-rose-500 @enderror">{{ old('treatment') }}</textarea>
                @error('treatment') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Dokter (Opsional)</label>
                <textarea name="notes" id="notes" rows="2" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('notes') border-rose-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- BAGIAN RESEP (DINAMIS) --}}
        <div class="bg-white rounded-xl shadow-md p-6 space-y-5 border border-gray-100">
            <h2 class="text-base font-semibold text-teal-800 border-b pb-2">2. Resep Obat</h2>
            
            <div id="medicine-list" class="space-y-4">
                {{-- Template item obat pertama --}}
                <div class="medicine-item grid grid-cols-6 gap-3 p-3 border border-dashed border-gray-300 rounded-lg bg-gray-50">
                    <div class="col-span-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Obat</label>
                        <select name="medicines[0][id]" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                            <option value="">-- Pilih Obat --</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }} (Stok: {{ $medicine->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jmlh</label>
                        <input type="number" name="medicines[0][quantity]" min="1" placeholder="Qty" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                    </div>
                    <div class="col-span-2 flex items-end gap-2">
                        <div>
                             <label class="block text-xs font-medium text-gray-700 mb-1">Dosis/Aturan Pakai</label>
                             <input type="text" name="medicines[0][dosage]" placeholder="e.g. 3x sehari 1 tablet" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                        </div>
                        {{-- Tombol Hapus disembunyikan untuk item pertama --}}
                    </div>
                </div>
            </div>

            <button type="button" id="add-medicine" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                <i class="fas fa-plus mr-1"></i> Tambah Obat
            </button>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="pt-5 border-t border-gray-200">
            <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600 transition">
                <i class="fas fa-save mr-2"></i> Simpan Rekam Medis & Selesaikan Kunjungan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let medicineCount = 1;
    const medicineList = document.getElementById('medicine-list');
    const addMedicineButton = document.getElementById('add-medicine');
    
    // HTML untuk template item obat
    const medicineTemplate = (index) => `
        <div class="medicine-item grid grid-cols-6 gap-3 p-3 border border-dashed border-gray-300 rounded-lg bg-gray-50">
             <div class="col-span-3">
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Obat</label>
                <select name="medicines[${index}][id]" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($medicines as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->name }} (Stok: {{ $medicine->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1">
                <label class="block text-xs font-medium text-gray-700 mb-1">Jmlh</label>
                <input type="number" name="medicines[${index}][quantity]" min="1" placeholder="Qty" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
            </div>
            <div class="col-span-2 flex items-end gap-2">
                <div>
                     <label class="block text-xs font-medium text-gray-700 mb-1">Dosis/Aturan Pakai</label>
                     <input type="text" name="medicines[${index}][dosage]" placeholder="e.g. 3x sehari 1 tablet" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                </div>
                <button type="button" class="remove-medicine flex-shrink-0 text-rose-500 hover:text-rose-700 text-sm p-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    addMedicineButton.addEventListener('click', () => {
        const newMedicineItem = document.createElement('div');
        newMedicineItem.innerHTML = medicineTemplate(medicineCount).trim();
        medicineList.appendChild(newMedicineItem.firstChild);
        medicineCount++;
    });

    // Delegasi event untuk tombol hapus
    medicineList.addEventListener('click', (e) => {
        if (e.target.closest('.remove-medicine')) {
            e.target.closest('.medicine-item').remove();
        }
    });
});
</script>

@endsection