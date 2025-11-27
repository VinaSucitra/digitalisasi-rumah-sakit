@extends('layouts.guest')

@section('title', 'Daftar Dokter & Jadwal')

@section('content')
    {{-- 💡 PERBAIKAN UTAMA: Tambahkan pt-[80px] di sini untuk menggeser konten ke bawah, menjauhi Navbar. --}}
    <section class="py-16 pt-[80px] bg-gray-50"> 
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- HEADER HALAMAN --}}
            <header class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-teal-800 mb-3">JADWAL PRAKTIK DOKTER</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Temukan dokter spesialis dan jadwal praktik mereka. Anda dapat mencari berdasarkan nama atau spesialisasi.
                </p>
            </header>

            {{-- FILTER DAN PENCARIAN (Opsional, untuk fitur lanjutan) --}}
            <div class="mb-10 p-6 bg-white rounded-lg shadow-md">
                <form action="{{ route('public.doctors') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- Pencarian Nama --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Dokter</label>
                        <input type="text" id="search" name="search" placeholder="Masukkan nama dokter..." 
                                class="w-full p-3 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500 transition">
                    </div>
                    
                    {{-- Filter Poli/Spesialisasi --}}
                    <div>
                        <label for="poli" class="block text-sm font-medium text-gray-700 mb-1">Filter Spesialisasi</label>
                        <select id="poli" name="poli_id" 
                                class="w-full p-3 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500 transition bg-white">
                            <option value="">-- Semua Spesialisasi --</option>
                            {{-- Kita asumsikan PublicDoctorController mengirimkan $polis --}}
                            @isset($polis)
                                @foreach($polis as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Tombol Cari --}}
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-md transition shadow-md">
                            CARI
                        </button>
                    </div>
                </form>
            </div>

            ---

            {{-- DAFTAR DOKTER --}}
            <div class="space-y-8">
                
                @forelse ($doctors as $doctor)
                    {{-- Kartu Dokter --}}
                    <div class="bg-white p-6 rounded-lg shadow-xl border-l-4 border-teal-500 hover:shadow-2xl transition duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 items-start">
                            
                            {{-- Profil Dokter (Kolom 1) --}}
                            <div class="md:col-span-2 flex flex-col items-center text-center">
                                <img src="{{ asset($doctor->user->photo_url ?? 'image/default-doctor.jpg') }}" 
                                        alt="Foto {{ $doctor->user->name }}" 
                                        class="w-32 h-32 object-cover rounded-full mb-3 border-4 border-gray-200">
                                
                                <h2 class="text-xl font-bold text-teal-800">{{ $doctor->user->name }}</h2>
                                <p class="text-teal-600 font-semibold mb-2">{{ $doctor->poli->name ?? 'Umum' }}</p>
                                <p class="text-sm text-gray-500 italic">{{ $doctor->about_me ?? 'Dokter tanpa deskripsi.' }}</p>
                            </div>
                            
                            {{-- Jadwal Praktik (Kolom 2) --}}
                            <div class="md:col-span-4 border-t pt-4 md:border-t-0 md:border-l md:pl-6 md:pt-0">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-calendar-alt text-teal-500 mr-2"></i> Jadwal Praktik Mingguan
                                </h3>
                                
                                @if ($doctor->schedules->count())
                                    <ul class="space-y-2">
                                        @foreach ($doctor->schedules as $schedule)
                                            <li class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded">
                                                <span class="font-medium text-gray-700">
                                                    {{ $schedule->day_of_week_name }}
                                                </span>
                                                <span class="font-bold text-teal-700">
                                                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 text-sm italic">Jadwal praktik belum tersedia.</p>
                                @endif

                                {{-- Tombol Reservasi (Mengarah ke fitur yang akan dibuat) --}}
                                <div class="mt-4 text-right">
                                    <a href="#" class="inline-block bg-teal-500 text-white font-semibold px-6 py-2 rounded-md hover:bg-teal-600 transition text-sm shadow-md">
                                        <i class="fas fa-calendar-plus mr-2"></i> Buat Janji
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- Jika tidak ada data dokter yang ditemukan --}}
                    <div class="text-center py-20 bg-white rounded-lg shadow-lg">
                        <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                        <p class="text-xl font-semibold text-gray-700">Mohon maaf, data dokter tidak ditemukan.</p>
                        <p class="text-gray-500 mt-2">Coba hapus filter pencarian atau cek kembali koneksi database Anda.</p>
                    </div>
                @endforelse

            </div>
            
            {{-- Paginasi (Jika menggunakan paginate() di Controller) --}}
            {{-- <div class="mt-10">
                {{ $doctors->links() }}
            </div> --}}

        </div>
    </section>
@endsection