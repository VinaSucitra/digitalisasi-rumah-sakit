@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Schedule Management</p>
            <h2 class="text-3xl font-bold text-gray-800">Tambah Jadwal Praktik Baru</h2>
            <p class="text-sm text-gray-600 mt-1">
                Tentukan hari dan jam praktik Anda. Jadwal tidak boleh saling tumpang tindih.
            </p>
        </div>

        <a href="{{ route('doctor.schedules.index') }}"
           class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">
            Kembali ke Daftar
        </a>
    </div>

    {{-- Kartu Form --}}
    <div class="bg-white shadow-xl rounded-xl p-6 lg:p-8">
        <form action="{{ route('doctor.schedules.store') }}" method="POST">
            @csrf

            {{-- Hari Praktik --}}
            <div class="mb-4">
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">
                    Hari Praktik
                </label>
                <select name="day_of_week" id="day_of_week" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('day_of_week') border-red-500 @enderror">
                    <option value="">-- Pilih Hari --</option>
                    @foreach ($days as $key => $dayLabel)
                        <option value="{{ $key }}" {{ old('day_of_week') == $key ? 'selected' : '' }}>
                            {{ $dayLabel }}
                        </option>
                    @endforeach
                </select>
                @error('day_of_week')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jam Mulai & Selesai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Mulai Praktik
                    </label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('start_time') border-red-500 @enderror">
                    @error('start_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Selesai Praktik
                    </label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('end_time') border-red-500 @enderror">
                    @error('end_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">
                        Durasi ideal praktik adalah 30 menit, pastikan rentang waktunya konsisten.
                    </p>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 border-t mt-4">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-200 w-full md:w-auto">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
