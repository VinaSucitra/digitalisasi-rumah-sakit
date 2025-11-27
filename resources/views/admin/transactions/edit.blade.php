@extends('admin.layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-emerald-900/80 space-x-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold">
            <i class="fas fa-calendar-check"></i>
        </span>
        <div>
            <p class="font-semibold tracking-wide">Dashboard Admin</p>
            <p class="text-xs text-emerald-900/60">Edit Transaksi</p>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950">
                Edit Transaksi
            </h1>
            <p class="mt-1 text-sm text-emerald-900/70">
                Edit detail transaksi yang sudah dicatat.
            </p>
        </div>
    </div>

    {{-- Form untuk mengedit transaksi --}}
    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-lg shadow-md">

            {{-- Total Amount --}}
            <div class="mb-4">
                <label for="total_amount" class="block font-semibold text-gray-700">Total</label>
                <input type="number" name="total_amount" id="total_amount" value="{{ old('total_amount', $transaction->total_amount) }}"
                    class="w-full p-2 border rounded-md @error('total_amount') border-red-500 @else border-emerald-300 @enderror" required>
                @error('total_amount')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Paid Amount --}}
            <div class="mb-4">
                <label for="paid_amount" class="block font-semibold text-gray-700">Jumlah Dibayar</label>
                <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $transaction->paid_amount) }}"
                    class="w-full p-2 border rounded-md @error('paid_amount') border-red-500 @else border-emerald-300 @enderror" required>
                @error('paid_amount')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Payment Method --}}
            <div class="mb-4">
                <label for="payment_method" class="block font-semibold text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method"
                        class="w-full p-2 border rounded-md @error('payment_method') border-red-500 @else border-emerald-300 @enderror" required>
                    <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer" {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="card" {{ old('payment_method', $transaction->payment_method) == 'card' ? 'selected' : '' }}>Kartu Kredit</option>
                </select>
                @error('payment_method')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label for="status" class="block font-semibold text-gray-700">Status</label>
                <select name="status" id="status"
                        class="w-full p-2 border rounded-md @error('status') border-red-500 @else border-emerald-300 @enderror" required>
                    <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="paid" {{ old('status', $transaction->status) == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="canceled" {{ old('status', $transaction->status) == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label for="notes" class="block font-semibold text-gray-700">Catatan</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full p-2 border rounded-md @error('notes') border-red-500 @else border-emerald-300 @enderror">{{ old('notes', $transaction->notes) }}</textarea>
                @error('notes')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-4">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow-md shadow-emerald-900/20 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
