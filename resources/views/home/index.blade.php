@extends('layouts.guest')

@section('title', 'Beranda')

{{-- ====================================================================== --}}
{{-- 1. HERO SECTION (ID: #hero-section) --}}
{{-- ====================================================================== --}}
@section('hero')
    <div id="hero-section" class="relative w-full overflow-hidden bg-gradient-to-r from-teal-500 to-teal-700"> 
        
        {{-- Area Gambar Latar Belakang (Menggunakan doctor1.jpg) --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-20" 
             style="background-image: url('{{ asset('image/doctor1.jpg') }}');">
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 md:py-32 lg:py-40 text-white">
            <div class="grid md:grid-cols-2 items-center gap-10 text-left">
                
                <div class="md:col-span-1">
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4 uppercase"> 
                        KAMI MENYEDIAKAN <br> LAYANAN KESEHATAN TERBAIK
                    </h1>
                    <p class="text-lg mb-8 max-w-md">
                        Kami berkomitmen untuk memberikan perawatan yang unggul dan penuh kasih sayang, menggunakan teknologi medis terdepan.
                    </p>
                    {{-- Tautan ke Daftar Dokter --}}
                    <a href="{{ route('public.doctors') }}" class="bg-white text-teal-700 font-semibold px-8 py-3 rounded-md hover:bg-gray-100 transition shadow-lg uppercase text-sm">
                        LIHAT DOKTER & JADWAL
                    </a>
                </div>
                
                {{-- END GAMBAR DOKTER HERO --}}

            </div>
        </div>
    </div>
@endsection

@section('content')
    
    {{-- OUR DEPARTMENTS / LAYANAN KAMI (Bagian Beranda, di bawah Hero) --}}
    <section id="services-section-top" class="max-w-7xl mx-auto px-6 py-12 -mt-20 relative z-20"> 
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Kartu Layanan (Contoh Dashboard Tamu) --}}
            <div class="bg-white p-6 rounded-lg shadow-xl text-center border-t-4 border-teal-500 hover:shadow-2xl transition duration-300">
                <i class="fa fa-calendar-check text-4xl text-teal-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">RESERVASI SAYA</h3>
                <p class="text-gray-500 text-sm">Lihat detail pemesanan, waktu check-in/out Anda.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-xl text-center border-t-4 border-teal-500 hover:shadow-2xl transition duration-300">
                <i class="fa fa-swimming-pool text-4xl text-teal-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">FASILITAS & AMENITAS</h3>
                <p class="text-gray-500 text-sm">Informasi kolam renang, gym, dan layanan kamar.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-xl text-center border-t-4 border-teal-500 hover:shadow-2xl transition duration-300">
                <i class="fa fa-map-marked-alt text-4xl text-teal-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">PANDUAN LOKAL</h3>
                <p class="text-gray-500 text-sm">Rekomendasi restoran, tempat wisata, dan transportasi.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-xl text-center border-t-4 border-teal-500 hover:shadow-2xl transition duration-300">
                <i class="fa fa-headset text-4xl text-teal-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">HUBUNGI KAMI</h3>
                <p class="text-gray-500 text-sm">Akses cepat ke layanan bantuan 24 jam.</p>
            </div>
        </div>

        <div class="text-center mt-10">
            {{-- Tautan ke Daftar Dokter --}}
            <a href="{{ route('public.doctors') }}" class="inline-block bg-teal-500 text-white font-semibold px-8 py-3 rounded-md hover:bg-teal-600 transition uppercase text-sm shadow-md">
                LIHAT DOKTER & JADWAL LENGKAP
            </a>
        </div>
    </section>

    ---
    
    {{-- ====================================================================== --}}
    {{-- 2. TENTANG KAMI SECTION (ID: #about-section) --}}
    {{-- ====================================================================== --}}
    <section id="about-section" class="py-16 bg-white pt-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-12">
            
            {{-- Gambar --}}
            <div class="relative">
                <img src="{{ asset('image/doctorcewek.jpg') }}" alt="Dokter Wanita Tersenyum" 
                     class="rounded-lg shadow-lg w-full h-auto object-cover max-h-[450px] md:max-h-[500px]"> 
            </div>

            {{-- Konten Teks --}}
            <div>
                <h4 class="text-teal-600 font-semibold text-sm uppercase mb-2">ABOUT US</h4>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 leading-tight">
                    Kami Adalah Ahli Kesehatan Terbaik <br> Untuk Keluarga Anda.
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Kami menyediakan berbagai layanan kesehatan modern dan terpercaya. Tim medis kami yang profesional dan berpengalaman siap melayani dengan sepenuh hati, didukung fasilitas canggih untuk diagnosa dan perawatan optimal.
                </p>
                {{-- Tautan ini tetap mengarah ke #services-section di halaman yang sama --}}
                <a href="#services-section" class="bg-teal-500 text-white font-semibold px-6 py-3 rounded-md hover:bg-teal-600 transition uppercase text-sm">
                    LIHAT SPESIALISASI
                </a>
            </div>
        </div>
    </section>

    ---

    {{-- ====================================================================== --}}
    {{-- 3. LAYANAN KAMI SECTION (ID: #services-section) --}}
    {{-- ====================================================================== --}}
    <section id="services-section" class="max-w-7xl mx-auto px-6 py-12 pt-20">
        
        <h2 class="text-4xl font-bold text-teal-700 mb-4 text-center">SEMUA LAYANAN DAN SPESIALISASI</h2>
        <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            Daftar lengkap spesialisasi medis dan poli yang kami sediakan.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            {{-- Data $polis diharapkan dikirim dari HomeController. Jika data tidak ada, tidak ada yang ditampilkan. --}}
            @if(isset($polis) && count($polis) > 0)
                @foreach ($polis as $poli)
                    <div class="bg-white p-6 rounded-lg shadow-xl border-t-4 border-teal-500 hover:shadow-2xl transition duration-300">
                        
                        <div class="text-5xl text-teal-500 mb-4">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $poli->name }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $poli->description ?? 'Deskripsi poli ini belum tersedia.' }}
                        </p>
                        
                        {{-- Tautan ini tetap mengarah ke #contact-section di halaman yang sama --}}
                        <a href="#contact-section" class="mt-4 inline-block text-teal-600 font-semibold hover:text-teal-800 transition text-sm">
                            Hubungi Kami <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endforeach
            @else
                {{-- Pesan jika data poli belum dikirim/ditemukan --}}
                <div class="lg:col-span-3 text-center py-10">
                    <p class="text-gray-500">Data layanan (poli) belum dimuat dari database.</p>
                </div>
            @endif
        </div>
    </section>

    ---

    {{-- ====================================================================== --}}
    {{-- 4. KONTAK KAMI SECTION (ID: #contact-section) --}}
    {{-- ====================================================================== --}}
    <section id="contact-section" class="py-16 bg-gray-100 pt-20">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl font-bold text-teal-700 mb-4 text-center">HUBUNGI KAMI</h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
                Silakan isi formulir di bawah ini atau gunakan kontak kami untuk pertanyaan lebih lanjut.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                
                {{-- Bagian Informasi Kontak --}}
                <div class="p-6 bg-white rounded-lg shadow-lg border-t-4 border-teal-500">
                    <h3 class="text-xl font-semibold text-teal-700 mb-4">Informasi Kontak</h3>
                    
                    <div class="flex items-start mb-4">
                        <i class="fa fa-map-marker-alt text-2xl text-teal-500 mr-4 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Alamat Utama</p>
                            <p class="text-gray-600">Jl. Perintis Kemerdekaan 8, Jakarta, Indonesia</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-4">
                        <i class="fa fa-phone text-2xl text-teal-500 mr-4 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Nomor Telepon</p>
                            <p class="text-gray-600">+62 812 3456 7890</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-4">
                        <i class="fa fa-envelope text-2xl text-teal-500 mr-4 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Email Resmi</p>
                            <p class="text-gray-600">info@rs-digital.com</p>
                        </div>
                    </div>
                </div>
                
                {{-- Bagian Form Kontak --}}
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-teal-700 mb-4">Kirim Pesan kepada Kami</h3>
                    <form action="#" method="POST">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500 transition" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500 transition" required>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700">Pesan Anda</label>
                            <textarea id="message" name="message" rows="4" class="w-full p-3 border border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500 transition" required></textarea>
                        </div>
                        <button type="submit" 
                                class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-8 rounded-md transition w-full md:w-auto">
                            KIRIM PESAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Map (Diletakkan di bagian akhir content) --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h4 class="text-teal-600 font-semibold text-sm uppercase mb-2 text-center">LOKASI KAMI</h4>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-10 text-center">
                Temukan Kami di Peta
            </h2>
            <div class="aspect-w-16 aspect-h-9 w-full rounded-lg shadow-xl overflow-hidden">
                {{-- URL Iframe ini adalah placeholder. Ganti dengan embed Google Maps RS Anda yang sebenarnya. --}}
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1!2d0!3d0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDQ5JzAxLjEiTiAxMjPCsDI0JzAzLjIiRQ!5e0!3m2!1sen!2sid!4w100" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

@endsection