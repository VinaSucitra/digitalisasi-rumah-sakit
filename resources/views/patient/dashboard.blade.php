@extends('layouts.patient')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <h2 class="text-3xl font-bold mb-6 text-gray-700">Dashboard Pasien</h2>


    {{-- =======================
        JANJI TEMU TERBARU
    ======================== --}}
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-3">Janji Temu Terakhir</h3>

        @if(!$latestAppointment)
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                Belum ada janji temu yang terdaftar.
            </div>
        @else
            <div class="p-5 bg-white shadow rounded-lg border border-gray-200">
                <p><strong>Dokter:</strong> {{ $latestAppointment->doctor->user->name }}</p>
                <p><strong>Poli:</strong> {{ $latestAppointment->doctor->poli->name }}</p>
                <p><strong>Tanggal:</strong> {{ $latestAppointment->booking_date }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-3 py-1 text-sm rounded bg-blue-600 text-white">
                        {{ ucfirst($latestAppointment->status) }}
                    </span>
                </p>
            </div>
        @endif
    </div>


    {{-- =======================
        NOTIFIKASI APPROVED
    ======================== --}}
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-3">Janji Temu Disetujui</h3>

        @if($approvedAppointments->isEmpty())
            <div class="p-4 bg-gray-100 text-gray-700 rounded-lg">
                Tidak ada janji temu yang disetujui.
            </div>
        @else
            <div class="space-y-3">
                @foreach($approvedAppointments as $appt)
                    <div class="p-4 bg-white shadow rounded border border-gray-200">
                        <strong>{{ $appt->doctor->user->name }}</strong>  
                        ({{ $appt->doctor->poli->name }}) <br>

                        <span class="text-gray-700">
                            {{ $appt->booking_date }} — 
                            {{ $appt->schedule->start_time }} - {{ $appt->schedule->end_time }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


    {{-- =======================
        RIWAYAT REKAM MEDIS
    ======================== --}}
    <div class="mb-10">
        <h3 class="text-xl font-semibold text-gray-800 mb-3">Riwayat Pemeriksaan Terakhir</h3>

        @if($latestRecords->isEmpty())
            <div class="p-4 bg-gray-100 text-gray-700 rounded-lg">
                Belum ada rekam medis.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded shadow border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Dokter</th>
                            <th class="p-3 text-left">Diagnosis</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestRecords as $rec)
                        <tr class="border-b">
                            <td class="p-3">{{ $rec->visit_date }}</td>
                            <td class="p-3">{{ $rec->doctor->user->name }}</td>
                            <td class="p-3">{{ \Illuminate\Support\Str::limit($rec->diagnosis, 45) }}</td>
                            <td class="p-3">
                                <a href="{{ route('patient.medical_records.show', $rec->id) }}"
                                   class="px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700 text-sm">
                                   Detail
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
