@extends('layouts.patient')

@section('title', 'Riwayat Pemeriksaan')
@section('page_title', 'Riwayat Pemeriksaan')

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
                <i class="fas fa-notes-medical text-white/90"></i>
                Riwayat Pemeriksaan
            </h1>
            <p class="text-sm text-emerald-100 mt-1">
                Berikut adalah riwayat rekam medis Anda yang telah tercatat di sistem.
            </p>
        </div>

        <a href="{{ route('patient.dashboard') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-teal-700
                  text-sm font-semibold shadow-md hover:bg-emerald-50 transition">
            <i class="fas fa-home mr-2 text-xs"></i>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- KONTEN --}}
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5 md:p-6 max-w-4xl">

        @if($medicalRecords->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-10 text-center gap-3">
                <div
                    class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-file-medical text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-gray-800">
                    Belum ada rekam medis yang tercatat
                </h2>
                <p class="text-sm text-gray-500 max-w-md">
                    Rekam medis akan muncul setelah dokter menyelesaikan pemeriksaan dan mengisi hasil konsultasi Anda.
                </p>
            </div>
        @else
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                    Daftar Rekam Medis
                </h2>
                <span class="text-[11px] text-gray-500">
                    Total: {{ $medicalRecords->total() }} kunjungan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-[11px] uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-2 text-left">Tanggal Pemeriksaan</th>
                            <th class="px-4 py-2 text-left">Dokter</th>
                            <th class="px-4 py-2 text-left">Poli</th>
                            <th class="px-4 py-2 text-left">Resep</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($medicalRecords as $record)
                            <tr class="hover:bg-gray-50">
                                {{-- Tanggal --}}
                                <td class="px-4 py-3 whitespace-nowrap align-top">
                                    <div class="font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($record->visit_date)->format('d M Y') }}
                                    </div>
                                </td>

                                {{-- Dokter --}}
                                <td class="px-4 py-3 whitespace-nowrap align-top">
                                    <div class="font-medium text-gray-800">
                                        {{ $record->doctor->user->name }}
                                    </div>
                                </td>

                                {{-- Poli --}}
                                <td class="px-4 py-3 whitespace-nowrap align-top text-gray-700">
                                    {{ $record->doctor->poli->name }}
                                </td>

                                {{-- Resep --}}
                                <td class="px-4 py-3 align-top">
                                    @if($record->prescriptions->count() > 0)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-prescription-bottle-alt mr-1"></i>
                                            Ada Resep
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">
                                            <i class="fas fa-minus-circle mr-1"></i>
                                            Tidak Ada
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 text-center align-top">
                                    <a href="{{ route('patient.medical_records.show', $record->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold
                                              bg-teal-50 text-teal-700 hover:bg-teal-100">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $medicalRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
