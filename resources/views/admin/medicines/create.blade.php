@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-emerald-700 mb-1">Manajemen Obat & Tindakan</p>
            <h2 class="text-3xl font-bold text-gray-800">Tambah Obat / Tindakan Baru</h2>
            <p class="text-sm text-gray-500 mt-1">
                Tambahkan data obat atau tindakan medis yang tersedia di rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.medicines.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-200">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-8 shadow-xl rounded-2xl max-w-2xl mx-auto border border-emerald-100">
        <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Obat / Tindakan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Paracetamol / Pemeriksaan Dokter Umum"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis: Obat / Tindakan --}}
            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-semibold mb-2">
                    Jenis Item <span class="text-red-500">*</span>
                </label>
                <select name="type" id="type"
                        class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-500 @enderror">
                    @foreach($types as $value)
                        <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">
                    Pilih <strong>Obat</strong> untuk obat farmasi, atau <strong>Tindakan</strong> untuk layanan medis seperti pemeriksaan dokter.
                </p>
            </div>

            {{-- Tipe Obat: keras / biasa --}}
            <div class="mb-4">
                <label for="drug_type" class="block text-gray-700 text-sm font-semibold mb-2">
                    Tipe Obat
                </label>
                <select name="drug_type" id="drug_type"
                        class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('drug_type') border-red-500 @enderror">
                    <option value="">-- Pilih Tipe Obat (Opsional) --</option>
                    @foreach($drugTypes as $value)
                        <option value="{{ $value }}" {{ old('drug_type') === $value ? 'selected' : '' }}>
                            {{ ucfirst($value) }}
                        </option>
                    @endforeach
                </select>
                @error('drug_type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror>
                <p class="text-xs text-gray-400 mt-1">
                    Wajib diisi untuk <strong>Obat</strong>. Untuk <strong>Tindakan</strong> boleh dikosongkan.
                </p>
            </div>

            {{-- Stok --}}
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 text-sm font-semibold mb-2">
                    Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" name="stock" id="stock" min="0" step="1"
                       value="{{ old('stock', 0) }}"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">
                    Untuk <strong>Tindakan</strong> bisa diisi 0.
                </p>
            </div>

            {{-- Harga --}}
            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-semibold mb-2">
                    Harga (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="price" id="price" min="0" step="100"
                       value="{{ old('price') }}"
                       class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-semibold mb-2">
                    Deskripsi (Opsional)
                </label>
                <textarea name="description" id="description" rows="4"
                          class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gambar Obat --}}
            <div class="mb-6">
                <label for="image" class="block text-gray-700 text-sm font-semibold mb-2">
                    Gambar Obat (Opsional)
                </label>
                <input type="file" name="image" id="image"
                       class="block w-full text-sm text-gray-700
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-emerald-50 file:text-emerald-700
                              hover:file:bg-emerald-100
                              @error('image') border border-red-500 rounded-lg @enderror">
                @error('image')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">
                    Format: JPG/PNG, maksimal 2MB.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Simpan Data
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
