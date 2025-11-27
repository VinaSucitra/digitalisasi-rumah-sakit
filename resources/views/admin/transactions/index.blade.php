@extends('admin.layouts.app')

@section('title', 'Daftar Transaksi')
@section('header', 'Daftar Transaksi')

@section('content')

<!-- Notifikasi -->
@if (session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl font-bold text-gray-800">Semua Transaksi</h2>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('admin.transactions.index') }}" method="GET" class="mb-4">
        <div class="flex items-center space-x-4">
            <!-- Pencarian -->
            <input type="text" name="search" value="{{ request()->search }}" class="p-2 border rounded-md" placeholder="Cari Pasien/Dokter/No. Referensi">
            
            <!-- Filter Status -->
            <select name="status" class="p-2 border rounded-md">
                <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request()->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="canceled" {{ request()->status == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <!-- Tombol Filter -->
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Filter</button>
        </div>
    </form>

    <!-- Tabel Daftar Transaksi -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Pasien</th>
                    <th class="px-4 py-2 text-left">Dokter</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $transaction->id }}</td>
                        <td class="px-4 py-3">{{ $transaction->appointment->patient->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $transaction->appointment->doctor->name ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $transaction->created_at->format('d M Y') }}</td>

                        <td class="px-4 py-3">
                            <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3 text-center text-gray-500" colspan="6">
                            Belum ada transaksi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
