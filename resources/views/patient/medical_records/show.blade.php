@extends('layouts.patient')

@section('content')
<div class="container">

    <h2 class="mb-4">Detail Rekam Medis</h2>

    <div class="card mb-3">
        <div class="card-body">

            <h5>Data Pemeriksaan</h5>

            <p>
                <strong>Dokter:</strong> {{ $medical_record->doctor->user->name }} <br>
                <strong>Poli:</strong> {{ $medical_record->doctor->poli->name }} <br>
                <strong>Tanggal Periksa:</strong> {{ $medical_record->visit_date }} <br>
                <strong>Keluhan:</strong> {{ $medical_record->appointment->complaint }}
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

            @foreach ($medical_record->prescriptions as $rx)
                <div class="card mb-3">
                    <div class="card-body">

                        <strong>Status Resep:</strong> {{ $rx->status }} <br>

                        <ul>
                            @foreach ($rx->items as $item)
                            <li>
                                <strong>{{ $item->medicine->name }}</strong> <br>
                                Jumlah: {{ $item->quantity }} <br>
                                Aturan Pakai: <em>{{ $item->dosage }}</em>
                            </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

</div>
@endsection
