@extends('doctor.layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Dashboard Dokter</h2>
    <p>Selamat datang, <strong>{{ $user->name }}</strong></p>

    <div class="row mb-4">

        {{-- Jumlah Janji Temu Pending --}}
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Janji Temu Pending</h5>
                    <h2>{{ $pendingCount }}</h2>
                    <p class="mb-0">
                        Janji temu yang menunggu persetujuan Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- Jadwal Praktik --}}
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jadwal Praktik</h5>
                    <h2>{{ $totalSchedules }}</h2>
                    <p class="mb-2">Total slot jadwal praktik yang Anda miliki.</p>
                    <a href="{{ route('doctor.appointments.index') }}" class="btn btn-light btn-sm">
                        Lihat Janji Temu
                    </a>
                </div>
            </div>
        </div>

        {{-- Tanggal Hari Ini --}}
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Hari Ini</h5>
                    <h3>{{ $today }}</h3>
                    <p class="mb-0">Daftar konsultasi yang sudah di-approve untuk hari ini.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Janji Temu Approved Hari Ini --}}
    <h4 class="mt-4">Janji Temu Approved Hari Ini</h4>

    @if($todayApproved->isEmpty())
        <div class="alert alert-info">Belum ada janji temu approved untuk hari ini.</div>
    @else
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>Pasien</th>
                    <th>Keluhan</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todayApproved as $a)
                <tr>
                    <td>{{ $a->patient->user->name }}</td>
                    <td>{{ $a->complaint }}</td>
                    <td>
                        {{ $a->schedule->start_time }} - {{ $a->schedule->end_time }}
                    </td>
                    <td>
                        <a href="{{ route('doctor.appointments.show', $a->id) }}" class="btn btn-sm btn-primary">
                            Detail Janji
                        </a>
                        <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                           class="btn btn-sm btn-success">
                            Buat Rekam Medis
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif


    {{-- ===================================================== --}}
    {{--                    ANTRIAN HARI INI                  --}}
    {{-- ===================================================== --}}
    <h4 class="mt-5">Antrian Konsultasi Hari Ini</h4>

    @if($todayQueue->isEmpty())
        <div class="alert alert-secondary">Belum ada pasien dalam antrean hari ini.</div>
    @else
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>No. Antrian</th>
                    <th>Nama Pasien</th>
                    <th>Keluhan</th>
                    <th>Jam</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todayQueue as $queue)
                <tr>
                    <td>
                        <span class="badge bg-success p-2">
                            {{ $queue->queue_number }}
                        </span>
                    </td>
                    <td>{{ $queue->patient->user->name }}</td>
                    <td>{{ $queue->complaint }}</td>
                    <td>{{ $queue->schedule->start_time }} - {{ $queue->schedule->end_time }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    {{-- ===================================================== --}}


    {{-- 5 Pasien Terakhir --}}
    <h4 class="mt-5">5 Pasien Terakhir yang Telah Diperiksa</h4>

    @if($latestPatients->isEmpty())
        <div class="alert alert-secondary">Belum ada rekam medis yang dibuat.</div>
    @else
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>Tanggal Periksa</th>
                    <th>Nama Pasien</th>
                    <th>Diagnosis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($latestPatients as $rec)
                <tr>
                    <td>{{ $rec->visit_date }}</td>
                    <td>{{ $rec->patient->user->name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($rec->diagnosis, 40) }}</td>
                    <td>
                        <a href="{{ route('doctor.medical_records.show', $rec->id) }}" class="btn btn-sm btn-outline-primary">
                            Lihat Rekam Medis
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
