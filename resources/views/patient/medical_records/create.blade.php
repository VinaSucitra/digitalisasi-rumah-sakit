@extends('layouts.patient')

@section('title', 'Tidak Diizinkan')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md text-center">
    <h2 class="text-xl font-bold text-red-600">Akses Ditolak</h2>
    <p class="text-gray-600 mt-2">
        Pasien tidak dapat membuat rekam medis. Rekam medis hanya diisi oleh Dokter.
    </p>
</div>
@endsection
