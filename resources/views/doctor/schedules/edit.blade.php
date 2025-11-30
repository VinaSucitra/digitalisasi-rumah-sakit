@extends('layouts.doctor')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Schedule Management</p>
            <h2 class="text-3xl font-bold text-gray-800">Edit Jadwal Praktik</h2>
            <p class="text-sm text-gray-600 mt-1">
                Perbarui informasi jadwal praktik Anda. Pastikan tidak bertabrakan dengan jadwal lain.
            </p>
        </div>

        <a href="{{ route('doctor.schedules.index') }}"
            class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-400 transition duration-200">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-xl p-6 lg:p-8">
        <form action="{{ route('doctor.schedules.update', $schedule->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Hari --}}
            <div class="mb-4">
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">
                    Hari Praktik
                </label>
                <select name="day_of_week" id="day_of_week" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('day_of_week') border-red-500 @enderror">
                    @foreach ($days as $key => $label)
                        <option value="{{ $key }}"
                            {{ old('day_of_week', $schedule->day_of_week) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('day_of_week')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Jam Mulai --}}
                <div class="mb-4">
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Mulai Praktik
                    </label>
                    <input type="time" name="start_time" id="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('start_time') border-red-500 @enderror">
                    @error('start_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Selesai --}}
                <div class="mb-4">
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Selesai Praktik
                    </label>
                    <input type="time" name="end_time" id="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 @error('end_time') border-red-500 @enderror">
                    @error('end_time')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t mt-4">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-200 w-full md:w-auto">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
