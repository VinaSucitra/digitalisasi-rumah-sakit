@extends('layouts.patient')

@section('title', 'Detail Rekam Medis')
@section('page_title', 'Detail Rekam Medis')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-teal-500
                rounded-2xl shadow-lg px-6 py-5 flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-emerald-100 mb-1">
                Rekam Medis Pasien
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-file-medical-alt text-white/90"></i>
                Detail Rekam Medis
            </h1>
            <p class="text-sm text-emerald-100 mt-1">
                Informasi lengkap mengenai salah satu pemeriksaan Anda.
            </p>
        </div>

        <a href="{{ route('patient.medical_records.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                  text-sm font-semibold shadow-md hover:bg-emerald-50 transition">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            Kembali ke Riwayat
        </a>
    </div>

    {{-- CARD DETAIL --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 md:p-8 max-w-3xl">

        {{-- INFO UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="col-span-1">
                <p class="text-xs font-semibold text-gray-500 mb-1">
                    Tanggal Pemeriksaan
                </p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($medicalRecord->visit_date)->format('d M Y') }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">
                    Dokter Penanggung Jawab
                </p>
                <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-md text-emerald-500"></i>
                    {{ $medicalRecord->doctor->user->name }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">
                    Poli / Klinik
                </p>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold
                           bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <i class="fas fa-hospital-user mr-1"></i>
                    {{ $medicalRecord->doctor->poli->name }}
                </span>
            </div>
        </div>

        <hr class="border-dashed border-gray-200 mb-6">

        {{-- RESEP --}}
        <div class="space-y-3">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2 mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                Resep Obat
            </h2>

            @if($medicalRecord->prescriptions->count() > 0)
                <p class="text-xs text-gray-500 mb-2">
                    Daftar resep yang diberikan dokter pada kunjungan ini.
                </p>

                <div class="space-y-4">
                    @foreach($medicalRecord->prescriptions as $prescription)
                        <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/60">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                        Rx
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $prescription->name }}
                                        </p>
                                        @if($prescription->note)
                                            <p class="text-[11px] text-gray-500">
                                                {{ $prescription->note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($prescription->items->count() > 0)
                                <ul class="space-y-1.5 text-sm text-gray-700">
                                    @foreach($prescription->items as $item)
                                        <li class="flex items-start justify-between gap-3">
                                            <span class="font-medium">
                                                {{ $item->medicine->name }}
                                            </span>
                                            <span class="text-xs text-gray-600">
                                                {{ $item->quantity }} pcs
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-gray-500">
                                    Tidak ada item obat yang tercatat pada resep ini.
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-600 flex items-center gap-2">
                    <i class="fas fa-info-circle text-gray-400"></i>
                    <span>Tidak ada resep obat untuk pemeriksaan ini.</span>
                </div>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
            <a href="{{ route('patient.medical_records.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2 text-xs"></i>
                Kembali ke Riwayat Pemeriksaan
            </a>
        </div>
    </div>
</div>
@endsection
