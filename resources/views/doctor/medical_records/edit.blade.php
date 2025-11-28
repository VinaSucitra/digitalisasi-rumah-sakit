@extends('layouts.doctor')

@section('page_title', 'Edit Rekam Medis')

@section('content')

<div class="space-y-6">

    {{-- HEADER HALAMAN --}}
    <div class="flex items-center justify-between border-b pb-3 border-teal-200">
        <h1 class="text-xl font-bold text-teal-900 flex items-center gap-2">
            <i class="fas fa-edit text-teal-600"></i>
            Edit Rekam Medis
        </h1>
        <a href="{{ route('doctor.medical_records.show', $medical_record->id) }}" 
           class="text-sm font-medium text-gray-500 hover:text-teal-600">
            &larr; Kembali ke Detail RM
        </a>
    </div>

    {{-- KARTU INFORMASI PASIEN --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 shadow-sm">
        <h3 class="font-bold text-lg text-teal-900">Pasien: {{ $medical_record->patient->user->name ?? 'N/A' }}</h3>
        <p class="text-sm text-teal-700">Tanggal Kunjungan: **{{ \Carbon\Carbon::parse($medical_record->visit_date)->format('d F Y') }}**</p>
        <p class="text-xs text-teal-600 mt-1">
            Janji Temu ID: #{{ $medical_record->appointment_id }}
        </p>
    </div>

    {{-- FORM REKAM MEDIS --}}
    <form action="{{ route('doctor.medical_records.update', $medical_record->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}
        
        {{-- BAGIAN DIAGNOSIS & TINDAKAN --}}
        <div class="bg-white rounded-xl shadow-md p-6 space-y-5 border border-gray-100">
            <h2 class="text-base font-semibold text-teal-800 border-b pb-2">1. Diagnosis & Tindakan Medis</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="visit_date" class="block text-sm font-medium text-gray-700 required">Tanggal Kunjungan</label>
                    <input type="date" name="visit_date" id="visit_date" 
                           value="{{ old('visit_date', $medical_record->visit_date) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('visit_date') border-rose-500 @enderror" 
                           required>
                    @error('visit_date') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 required">Diagnosis Utama</label>
                <textarea name="diagnosis" id="diagnosis" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('diagnosis') border-rose-500 @enderror" 
                          required>{{ old('diagnosis', $medical_record->diagnosis) }}</textarea>
                @error('diagnosis') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="treatment" class="block text-sm font-medium text-gray-700">Tindakan Medis</label>
                <textarea name="treatment" id="treatment" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('treatment') border-rose-500 @enderror">{{ old('treatment', $medical_record->treatment) }}</textarea>
                @error('treatment') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Dokter (Opsional)</label>
                <textarea name="notes" id="notes" rows="2" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 @error('notes') border-rose-500 @enderror">{{ old('notes', $medical_record->notes) }}</textarea>
                @error('notes') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- BAGIAN RESEP (DINAMIS) --}}
        <div class="bg-white rounded-xl shadow-md p-6 space-y-5 border border-gray-100">
            <h2 class="text-base font-semibold text-teal-800 border-b pb-2">2. Resep Obat (Perlu penyesuaian di Controller jika ingin di-update)</h2>
            
            <div id="medicine-list" class="space-y-4">
                
                @php
                    // Ambil item resep yang sudah ada, atau array kosong jika tidak ada
                    $existingItems = $medical_record->prescriptions->isNotEmpty() ? $medical_record->prescriptions->first()->items : collect();
                    $medicineCounter = 0;
                @endphp

                @forelse($existingItems as $item)
                    <div class="medicine-item grid grid-cols-6 gap-3 p-3 border border-dashed border-gray-300 rounded-lg bg-gray-50">
                        <div class="col-span-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Obat</label>
                            <select name="medicines[{{ $medicineCounter }}][id]" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                                <option value="">-- Pilih Obat --</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>
                                        {{ $medicine->name }} (Stok: {{ $medicine->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jmlh</label>
                            <input type="number" name="medicines[{{ $medicineCounter }}][quantity]" min="1" placeholder="Qty" value="{{ $item->quantity }}" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                        </div>
                        <div class="col-span-2 flex items-end gap-2">
                            <div>
                                 <label class="block text-xs font-medium text-gray-700 mb-1">Dosis/Aturan Pakai</label>
                                 <input type="text" name="medicines[{{ $medicineCounter }}][dosage]" placeholder="e.g. 3x sehari 1 tablet" value="{{ $item->dosage }}" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm">
                            </div>
                            <button type="button" class="remove-medicine flex-shrink-0 text-rose-500 hover:text-rose-700 text-sm p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @php $medicineCounter++; @endphp
                @empty
                    {{-- Jika tidak ada item resep, tampilkan satu form kosong --}}
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
                            {{-- Tombol hapus disembunyikan untuk item awal kosong --}}
                        </div>
                    </div>
                    @php $medicineCounter = 1; @endphp
                @endforelse
                
            </div>

            <button type="button" id="add-medicine" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                <i class="fas fa-plus mr-1"></i> Tambah Obat Baru
            </button>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="pt-5 border-t border-gray-200">
            <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600 transition">
                <i class="fas fa-save mr-2"></i> Perbarui Rekam Medis
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi counter berdasarkan jumlah item yang sudah ada
    let medicineCount = {{ $medicineCounter }};
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