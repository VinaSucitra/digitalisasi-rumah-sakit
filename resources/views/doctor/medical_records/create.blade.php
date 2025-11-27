@extends('doctor.layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Buat Rekam Medis</h2>

    <div class="card">
        <div class="card-body">

            <h5>Data Pasien:</h5>
            <p>
                <strong>Nama:</strong> {{ $appointment->patient->user->name }} <br>
                <strong>Keluhan:</strong> {{ $appointment->complaint }} <br>
                <strong>Jadwal:</strong> 
                {{ $appointment->schedule->day_of_week }} 
                ({{ $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }})
            </p>

            <form action="{{ route('doctor.medical_records.store') }}" method="POST">
                @csrf

                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                <div class="mb-3">
                    <label>Diagnosis</label>
                    <textarea name="diagnosis" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Tindakan</label>
                    <textarea name="treatment" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Tanggal Periksa</label>
                    <input type="date" name="visit_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                <hr>

                <h5>Resep Obat</h5>

                <div id="medicine-wrapper">
                    <div class="medicine-item mb-3">
                        <label>Obat</label>
                        <select name="medicines[0][id]" class="form-control">
                            <option value="">-- Pilih Obat --</option>
                            @foreach($medicines as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>

                        <label>Jumlah</label>
                        <input type="number" name="medicines[0][quantity]" class="form-control">

                        <label>Aturan Pakai</label>
                        <input type="text" name="medicines[0][dosage]" class="form-control">
                    </div>
                </div>

                <button type="button" id="add-medicine" class="btn btn-secondary mb-3">
                    + Tambah Obat
                </button>

                <button type="submit" class="btn btn-primary">Simpan Rekam Medis</button>

            </form>
        </div>
    </div>

</div>

<script>
let index = 1;

document.getElementById('add-medicine').onclick = function() {

    let wrapper = document.getElementById('medicine-wrapper');

    let html = `
        <div class="medicine-item mb-3">
            <label>Obat</label>
            <select name="medicines[${index}][id]" class="form-control">
                <option value="">-- Pilih Obat --</option>
                @foreach($medicines as $m)
                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
            </select>

            <label>Jumlah</label>
            <input type="number" name="medicines[${index}][quantity]" class="form-control">

            <label>Aturan Pakai</label>
            <input type="text" name="medicines[${index}][dosage]" class="form-control">
        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    index++;
}
</script>

@endsection
