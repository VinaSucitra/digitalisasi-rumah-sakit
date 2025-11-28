@extends('layouts.doctor')

@section('page_title', 'Rekam Medis')

@section('content')

<div class="space-y-8">

    {{-- HEADER HALAMAN --}}
    <div class="flex items-center justify-between border-b pb-3 border-teal-200">
        <h1 class="text-xl font-bold text-teal-900 flex items-center gap-2">
            <i class="fas fa-notes-medical text-teal-600"></i>
            Manajemen Rekam Medis
        </h1>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-lg text-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- PENGELOMPOKAN DATA --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- 1. ANTRIAN KONSULTASI HARI INI --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-teal-100 p-6 space-y-4">
            <h3 class="text-lg font-semibold text-teal-800">
                Antrean Konsultasi Hari Ini ({{ \Carbon\Carbon::now()->format('d M Y') }})
            </h3>

            @if($appointments->isEmpty())
                <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-lg text-sm text-center">
                    <i class="fas fa-check-circle mr-2"></i> Tidak ada antrean konsultasi yang disetujui hari ini.
                </div>
            @else
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full text-xs">
                        <thead class="bg-teal-50">
                            <tr class="text-[11px] uppercase tracking-wide text-gray-600">
                                <th class="py-3 px-4 text-left">Pasien</th>
                                <th class="py-3 px-4 text-left">Jam</th>
                                <th class="py-3 px-4 text-left">Keluhan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($appointments as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-semibold text-gray-800">
                                        {{ $a->patient->user->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-teal-700 font-medium">
                                        {{ date('H:i', strtotime($a->schedule->start_time)) ?? '-' }} WIB
                                    </td>
                                    <td class="py-3 px-4 text-gray-500">
                                        {{ \Illuminate\Support\Str::limit($a->complaint, 40) }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-600 text-white hover:bg-teal-700 transition shadow-md">
                                            <i class="fas fa-plus-square mr-1"></i> Buat Rekam Medis
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- 2. RIWAYAT REKAM MEDIS TERBARU --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center justify-between">
                Riwayat Rekam Medis (Terakhir)
                <a href="#" class="text-xs font-medium text-teal-600 hover:text-teal-800">
                    Lihat Semua &rarr;
                </a>
            </h3>

            @if($records->isEmpty())
                <p class="text-sm text-gray-500">Belum ada Rekam Medis yang Anda buat.</p>
            @else
                <ul class="space-y-3">
                    @foreach($records as $rec)
                        <li class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-800 truncate">
                                    Pasien: {{ $rec->patient->user->name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($rec->created_at)->format('d M Y') }} - 
                                    {{ \Illuminate\Support\Str::limit($rec->diagnosis, 30) }}
                                </p>
                            </div>
                            <a href="{{ route('doctor.medical_records.show', $rec->id) }}" class="ml-4 flex-shrink-0 text-teal-600 hover:text-teal-800 text-sm font-medium">
                                <i class="fas fa-eye"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection