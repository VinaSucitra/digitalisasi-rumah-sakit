@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-5">Detail Transaksi #{{ $transaction->id }}</h2>

    <div>
        <h3 class="font-semibold text-gray-700">Pasien: </h3>
        <p>{{ $transaction->appointment->patient->name }}</p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Dokter: </h3>
        <p>{{ $transaction->appointment->doctor->name }}</p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Total: </h3>
        <p>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Status Pembayaran: </h3>
        <p>{{ ucfirst($transaction->status) }}</p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Metode Pembayaran: </h3>
        <p>{{ ucfirst($transaction->payment_method) }}</p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Catatan: </h3>
        <p>{{ $transaction->notes ?? '-' }}</p>
    </div>

    <a href="{{ route('admin.transactions.index') }}" class="mt-4 inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-md">
        Kembali ke Daftar Transaksi
    </a>
</div>
@endsection
