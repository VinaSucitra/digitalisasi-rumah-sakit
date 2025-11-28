@extends('layouts.patient') {{-- <--- MENGGUNAKAN LAYOUT BARU --}}

@section('title', 'Dashboard Pasien')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <h2 class="text-3xl font-extrabold mb-8 text-teal-800 border-b pb-2">
        <i class="fas fa-chart-line mr-2"></i> Dashboard Pasien
    </h2>

    {{-- Notifikasi Sukses/Error (Opsional) --}}
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">{{ session('error') }}</div>
    @endif
    
    <div class="mb-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-check text-teal-600"></i> Janji Temu Terakhir
        </h3>

        @if(!$latestAppointment)
            <div class="p-5 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg shadow-sm">
                Belum ada janji temu yang terdaftar. Ayo buat janji temu pertama Anda!
            </div>
        @else
            <div class="p-5 bg-white shadow-lg rounded-xl border border-teal-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <p><strong>Dokter:</strong> <span class="text-gray-700">{{ $latestAppointment->doctor->user->name ?? 'N/A' }}</span></p>
                    <p><strong>Poli:</strong> <span class="text-gray-700">{{ $latestAppointment->doctor->poli->name ?? 'N/A' }}</span></p>
                    <p><strong>Tanggal Kunjungan:</strong> <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($latestAppointment->booking_date)->format('d F Y') }}</span></p>
                    <p><strong>Status:</strong> 
                        {{-- Logika untuk menampilkan status yang berbeda warna --}}
                        @php
                            $statusClass = [
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Approved' => 'bg-teal-100 text-teal-800 font-semibold', // TEMA TEAL
                                'Rejected' => 'bg-red-100 text-red-800',
                                'Selesai' => 'bg-green-100 text-green-800',
                            ][$latestAppointment->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 text-xs rounded-full {{ $statusClass }}">
                            {{ ucfirst($latestAppointment->status) }}
                        </span>
                    </p>
                </div>
            </div>
        @endif
    </div>

    <hr class="my-6">

    <div class="mb-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-bell text-teal-600"></i> Pemberitahuan Janji Temu Disetujui
        </h3>

        @if($approvedAppointments->isEmpty())
            <div class="p-5 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg shadow-sm">
                Tidak ada janji temu yang disetujui saat ini.
            </div>
        @else
            <div class="space-y-4">
                @foreach($approvedAppointments as $appt)
                    <div class="p-4 bg-teal-50 shadow rounded-lg border border-teal-300 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-teal-800">Janji Temu Anda telah disetujui!</p>
                            <p class="text-sm text-gray-800 mt-1">
                                Bertemu Dr. **{{ $appt->doctor->user->name }}** ({{ $appt->doctor->poli->name }}) <br>
                                Pada Tanggal: **{{ \Carbon\Carbon::parse($appt->booking_date)->format('d F Y') }}** Pukul: **{{ $appt->schedule->start_time }}**
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm rounded-full bg-teal-600 text-white font-semibold flex-shrink-0">
                            Approved
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <hr class="my-6">

    <div class="mb-10">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="fas fa-file-medical text-teal-600"></i> Riwayat Pemeriksaan Terakhir
            </span>
            <a href="{{ route('patient.medical_records.index') ?? '#' }}" class="text-sm font-medium text-teal-600 hover:text-teal-800">
                Lihat Semua &rarr;
            </a>
        </h3>

        @if($latestRecords->isEmpty())
            <div class="p-5 bg-gray-50 border border-gray-200 text-gray-700 rounded-lg shadow-sm">
                Belum ada riwayat pemeriksaan (Rekam Medis) yang tercatat.
            </div>
        @else
            <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-teal-50 text-teal-800 uppercase text-xs">
                            <th class="p-3 text-left">Tanggal Kunjungan</th>
                            <th class="p-3 text-left">Dokter</th>
                            <th class="p-3 text-left">Diagnosis Utama</th>
                            <th class="p-3 text-center">Resep</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($latestRecords as $rec)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($rec->visit_date)->format('d M Y') }}</td>
                            <td class="p-3 text-gray-700 font-medium">{{ $rec->doctor->user->name ?? 'N/A' }}</td>
                            <td class="p-3 text-gray-600">{{ \Illuminate\Support\Str::limit($rec->diagnosis, 40) }}</td>
                            <td class="p-3 text-center">
                                @if($rec->prescriptions->isNotEmpty())
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-200 text-green-800 font-medium">Ada</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-600">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <a href="{{ route('patient.medical_records.show', $rec->id) ?? '#' }}"
                                   class="px-3 py-1 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-xs font-semibold transition shadow-md">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection