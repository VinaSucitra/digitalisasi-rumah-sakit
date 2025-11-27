@extends('doctor.layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Antrean Konsultasi Hari Ini</h2>

    <p>Tanggal: <strong>{{ $today }}</strong></p>

    @if($appointments->isEmpty())
        <div class="alert alert-info">Tidak ada antrean konsultasi hari ini.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pasien</th>
                    <th>Keluhan</th>
                    <th>Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $a)
                <tr>
                    <td>{{ $a->patient->user->name }}</td>
                    <td>{{ $a->complaint }}</td>
                    <td>
                        {{ $a->schedule->day_of_week }} <br>
                        {{ $a->schedule->start_time }} - {{ $a->schedule->end_time }}
                    </td>
                    <td>
                        <a href="{{ route('doctor.medical_records.create', ['appointment_id' => $a->id]) }}"
                           class="btn btn-primary btn-sm">
                            Buat Rekam Medis
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
