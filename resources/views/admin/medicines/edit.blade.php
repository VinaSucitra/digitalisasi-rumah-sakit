@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Manajemen Obat & Tindakan</p>
            <h2 class="text-3xl font-bold text-gray-800">
                Edit: {{ $medicine->name }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Perbarui informasi obat atau tindakan medis.
            </p>
        </div>

        <a href="{{ route('admin.medicines.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-200">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-8 shadow-xl rounded-2xl max-w-2xl mx-auto border border-emerald-100">
        <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Obat / Tindakan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name', $medicine->name) }}"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis --}}
            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-semibold mb-2">
                    Jenis <span class="text-red-500">*</span>
                </label>
                <select name="type" id="type"
                        class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-500 @enderror">
                    <option value="Obat" {{ old('type', $medicine->type) === 'Obat' ? 'selected' : '' }}>Obat</option>
                    <option value="Tindakan" {{ old('type', $medicine->type) === 'Tindakan' ? 'selected' : '' }}>Tindakan Medis</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-semibold mb-2">
                    Harga (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="price" id="price" min="0" step="100"
                       value="{{ old('price', $medicine->price) }}"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">
                    Deskripsi (Opsional)
                </label>
                <textarea name="description" id="description" rows="4"
                          class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror">{{ old('description', $medicine->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Update Data
                </button>
                <a href="{{ route('admin.medicines.index') }}"
                   class="inline-block align-baseline font-semibold text-sm text-gray-500 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
