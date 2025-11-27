@extends('doctor.layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Detail Rekam Medis</h2>

    <div class="card">
        <div class="card-body">

            <h5>Data Pasien:</h5>
            <p>
                <strong>Nama:</strong> {{ $medical_record->patient->user->name }} <br>
                <strong>Keluhan:</strong> {{ $medical_record->appointment->complaint }} <br>
                <strong>Tanggal Periksa:</strong> {{ $medical_record->visit_date }}
            </p>

            <hr>

            <h5>Diagnosis:</h5>
            <p>{{ $medical_record->diagnosis }}</p>

            <h5>Tindakan:</h5>
            <p>{{ $medical_record->treatment ?? '-' }}</p>

            <h5>Catatan:</h5>
            <p>{{ $medical_record->notes ?? '-' }}</p>

            <hr>

            <h4>Resep Obat</h4>

            @if($medical_record->prescriptions->isEmpty())
                <p class="text-muted">Tidak ada resep obat.</p>
            @else
                @foreach($medical_record->prescriptions as $rx)
                    <div class="card mb-3">
                        <div class="card-body">
                            <strong>Status:</strong> {{ $rx->status }} <br>

                            <ul>
                                @foreach($rx->items as $item)
                                <li>
                                    <strong>{{ $item->medicine->name }}</strong> — 
                                    {{ $item->quantity }} pcs <br>
                                    <em>{{ $item->dosage }}</em>
                                </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

</div>
@endsection
