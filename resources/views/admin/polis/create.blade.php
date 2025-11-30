@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">

    {{-- HERO / HEADER --}}
    <div
        class="bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-500 rounded-2xl shadow-lg px-6 py-5
               flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-white">
        <div>
            <p class="text-[11px] uppercase tracking-[0.25em] text-teal-100 mb-1">
                Manajemen Data Poli
            </p>
            <h2 class="text-2xl md:text-3xl font-extrabold flex items-center gap-2">
                <i class="fas fa-stethoscope text-white/90"></i>
                Tambah Poli Baru
            </h2>
            <p class="text-sm text-teal-100 mt-1 max-w-xl">
                Tambahkan poli / klinik baru yang tersedia di rumah sakit.
            </p>
        </div>

        <a href="{{ route('admin.polis.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/40
                  text-xs sm:text-sm font-semibold text-white hover:bg-white hover:text-teal-700
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
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-wide text-teal-700 uppercase">
                        Form Tambah Poli
                    </p>
                    <p class="text-[11px] text-gray-500">
                        Isi informasi dasar poli yang akan ditampilkan ke pasien.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.polis.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama Poli --}}
                <div class="space-y-1">
                    <label for="name" class="block text-sm font-medium text-gray-800">
                        Nama Poli <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Poli Umum"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                               @error('name') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400">
                        Nama poli sebaiknya <span class="font-semibold">singkat</span> dan mudah dikenali pasien.
                    </p>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1">
                    <label for="description" class="block text-sm font-medium text-gray-800">
                        Deskripsi (Opsional)
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Jelaskan layanan yang dicakup oleh poli ini."
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-300 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                               @error('description') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400">
                        Contoh: <em>Layanan pemeriksaan umum dan keluhan dasar.</em>
                    </p>
                </div>

                {{-- Ikon Poli --}}
                <div class="space-y-1">
                    <label for="icon" class="block text-sm font-medium text-gray-800">
                        Ikon Poli (Opsional)
                    </label>

                    <div class="relative">
                        <select
                            name="icon"
                            id="icon"
                            class="w-full px-3 py-2.5 pr-9 rounded-lg border border-gray-300 text-sm shadow-sm bg-white
                                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500
                                   @error('icon') border-rose-500 focus:ring-rose-400 focus:border-rose-500 @enderror">
                            <option value="">-- Pilih Ikon --</option>
                            @foreach($icons as $value => $label)
                                <option value="{{ $value }}" {{ old('icon') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </span>
                    </div>

                    @error('icon')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-[11px] text-gray-400 mt-1">
                        Ikon menggunakan FontAwesome, misalnya
                        <code class="bg-gray-100 px-1 rounded text-[11px]">
                            fa-{{ old('icon') ?: 'stethoscope' }}
                        </code>.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <a href="{{ route('admin.polis.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white
                              text-xs sm:text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2 text-[10px]"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 rounded-xl bg-teal-600 text-white
                                   text-xs sm:text-sm font-semibold shadow-md hover:bg-teal-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500">
                        <i class="fas fa-save mr-2 text-[11px]"></i>
                        Simpan Poli
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
