@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-emerald-100 mb-1">
                Manajemen Obat & Tindakan
            </p>
            <h2 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-pills text-white/90"></i>
                Edit: {{ $medicine->name }}
            </h2>
            <p class="text-sm text-emerald-100 mt-1 max-w-xl">
                Perbarui informasi obat atau tindakan medis yang digunakan di rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.medicines.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/40
                  text-xs sm:text-sm font-semibold text-white hover:bg-white hover:text-emerald-700
                  transition shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
            Kembali ke Daftar
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 lg:p-8 shadow-sm rounded-2xl border border-gray-100">

            {{-- Header kecil --}}
            <div class="flex items-center gap-3 mb-5 pb-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide text-emerald-700 uppercase">
                        Form Edit Obat / Tindakan
                    </p>
                    <p class="text-[11px] text-gray-500">
                        Lengkapi detail di bawah ini. Kolom bertanda * wajib diisi.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.medicines.update', $medicine->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-medium text-gray-800">
                        Nama Obat / Tindakan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $medicine->name) }}"
                           class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                  @error('name') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis --}}
                <div class="space-y-1">
                    <label for="type" class="block text-sm font-medium text-gray-800">
                        Jenis Item <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="type" id="type"
                                class="w-full px-3 py-2.5 pr-9 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                       @error('type') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            @foreach($types as $value)
                                <option value="{{ $value }}" {{ old('type', $medicine->type) === $value ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                    @error('type')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Obat --}}
                <div class="space-y-1">
                    <label for="drug_type" class="block text-sm font-medium text-gray-800">
                        Tipe Obat
                    </label>
                    <div class="relative">
                        <select name="drug_type" id="drug_type"
                                class="w-full px-3 py-2.5 pr-9 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                       @error('drug_type') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            <option value="">-- Pilih Tipe Obat (Opsional) --</option>
                            @foreach($drugTypes as $value)
                                <option value="{{ $value }}" {{ old('drug_type', $medicine->drug_type) === $value ? 'selected' : '' }}>
                                    {{ ucfirst($value) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>
                    @error('drug_type')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400">
                        Wajib diisi untuk <strong>Obat</strong>. Untuk <strong>Tindakan</strong> boleh dikosongkan.
                    </p>
                </div>

                {{-- Stok & Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Stok --}}
                    <div class="space-y-1">
                        <label for="stock" class="block text-sm font-medium text-gray-800">
                            Stok <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="stock" id="stock" min="0" step="1"
                               value="{{ old('stock', $medicine->stock) }}"
                               class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                      @error('stock') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                        @error('stock')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="space-y-1">
                        <label for="price" class="block text-sm font-medium text-gray-800">
                            Harga (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="price" id="price" min="0" step="100"
                               value="{{ old('price', $medicine->price) }}"
                               class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                      @error('price') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                        @error('price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1">
                    <label for="description" class="block text-sm font-medium text-gray-800">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                                     focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                     @error('description') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">{{ old('description', $medicine->description) }}</textarea>
                    @error('description')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="space-y-2">
                    <label for="image" class="block text-sm font-medium text-gray-800">
                        Gambar Obat (Opsional)
                    </label>

                    @if($medicine->image)
                        <div class="flex items-center gap-3">
                            <div class="h-20 w-20 rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
                                <img src="{{ Storage::url($medicine->image) }}"
                                     alt="Gambar {{ $medicine->name }}"
                                     class="h-full w-full object-cover">
                            </div>
                            <p class="text-[11px] text-gray-500">
                                Gambar saat ini. Anda dapat mengunggah gambar baru untuk menggantinya.
                            </p>
                        </div>
                    @endif

                    <input type="file" name="image" id="image"
                           class="block w-full text-sm text-gray-700
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-emerald-50 file:text-emerald-700
                                  hover:file:bg-emerald-100
                                  @error('image') border border-rose-500 rounded-lg @enderror">
                    @error('image')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400">
                        Kosongkan jika tidak ingin mengubah gambar. Format JPG/PNG, maksimal 2MB.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.medicines.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white
                              text-xs sm:text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 rounded-xl bg-emerald-600 text-white
                                   text-xs sm:text-sm font-semibold shadow-md hover:bg-emerald-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500">
                        <i class="fas fa-save mr-2 text-[11px]"></i>
                        Update Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
