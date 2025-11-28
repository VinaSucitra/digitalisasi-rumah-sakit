@extends('layouts.patient')

@section('content')
<div class="container">

    <h2 class="mb-4">Riwayat Rekam Medis</h2>

    @if($records->isEmpty())
        <div class="alert alert-info">Belum ada rekam medis.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Dokter</th>
                    <th>Poli</th>
                    <th>Keluhan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $r)
                <tr>
                    <td>{{ $r->visit_date }}</td>
                    <td>{{ $r->doctor->user->name }}</td>
                    <td>{{ $r->doctor->poli->name }}</td>
                    <td>{{ $r->appointment->complaint }}</td>
                    <td>
                        <a href="{{ route('patient.medical_records.show', $r->id) }}"
                           class="btn btn-primary btn-sm">
                           Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
