@extends('layouts.guest')

@section('title', 'Beranda')

{{-- ========================================================= --}}
{{-- HERO SECTION --}}
{{-- ========================================================= --}}
@section('hero')
    <div id="hero-section"
         class="relative w-full overflow-hidden bg-gradient-to-r from-teal-500 via-teal-600 to-emerald-500">

        {{-- Background image --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-15"
             style="background-image: url('{{ asset('image/doctor1.jpg') }}');">
        </div>

        {{-- Overlay pattern halus --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.15),_transparent_55%)]"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 md:py-24 lg:py-28 text-white">
            <div class="grid md:grid-cols-2 items-center gap-10">

                {{-- Text --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/30 mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] md:text-[11px] uppercase tracking-[0.25em] text-teal-50">
                            Rumah Sakit Digital • 24/7
                        </p>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4">
                        Layanan Kesehatan Terintegrasi<br>
                        <span class="text-emerald-200">Untuk Anda & Keluarga.</span>
                    </h1>
                    <p class="text-sm md:text-base mb-6 max-w-md text-teal-50/90">
                        Pantau jadwal dokter, informasi poli, dan layanan rumah sakit secara online.
                        Untuk membuat janji temu dan melihat rekam medis, silakan login sebagai pasien.
                    </p>

                    <div class="flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('public.doctors') }}"
                           class="bg-white text-teal-700 font-semibold px-7 py-2.5 rounded-full
                                  hover:bg-teal-50 shadow-lg shadow-teal-900/20 transition">
                            Lihat Dokter & Jadwal
                        </a>
                        <a href="#services-section"
                           class="border border-teal-100/80 text-white font-semibold px-6 py-2.5 rounded-full
                                  hover:bg-white/10 transition">
                            Layanan & Poli
                        </a>
                    </div>

                    {{-- Small stats strip --}}
                    <div class="mt-6 flex flex-wrap gap-4 text-xs md:text-[11px] text-teal-50/80">
                        <div class="flex items-center gap-2">
                            <i class="fa fa-check-circle text-emerald-200"></i>
                            <span>Rekam Medis Digital</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa fa-user-md text-emerald-200"></i>
                            <span>Dokter Terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa fa-clock text-emerald-200"></i>
                            <span>Janji Temu Online</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')

    {{-- ========================================================= --}}
    {{-- QUICK CARDS (di bawah hero) --}}
    {{-- ========================================================= --}}
    <section id="services-section-top"
             class="max-w-7xl mx-auto px-6 py-10 -mt-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-xl shadow-xl text-center border-t-4 border-teal-500
                        hover:shadow-2xl hover:-translate-y-0.5 transition">
                <i class="fa fa-user-md text-3xl text-teal-700 mb-3"></i>
                <h3 class="text-sm font-semibold text-gray-800 mb-1 uppercase tracking-wide">Jadwal Dokter</h3>
                <p class="text-xs text-gray-500">
                    Cek jadwal praktik dokter secara real-time sebelum berkunjung.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-xl text-center border-t-4 border-teal-500
                        hover:shadow-2xl hover:-translate-y-0.5 transition">
                <i class="fa fa-hospital text-3xl text-teal-700 mb-3"></i>
                <h3 class="text-sm font-semibold text-gray-800 mb-1 uppercase tracking-wide">Daftar Poli</h3>
                <p class="text-xs text-gray-500">
                    Poli umum, gigi, anak, dan layanan spesialis lainnya dalam satu portal.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-xl text-center border-t-4 border-teal-500
                        hover:shadow-2xl hover:-translate-y-0.5 transition">
                <i class="fa fa-laptop-medical text-3xl text-teal-700 mb-3"></i>
                <h3 class="text-sm font-semibold text-gray-800 mb-1 uppercase tracking-wide">Akses Online</h3>
                <p class="text-xs text-gray-500">
                    Terhubung dengan sistem janji temu dan rekam medis digital.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-xl text-center border-t-4 border-teal-500
                        hover:shadow-2xl hover:-translate-y-0.5 transition">
                <i class="fa fa-headset text-3xl text-teal-700 mb-3"></i>
                <h3 class="text-sm font-semibold text-gray-800 mb-1 uppercase tracking-wide">Kontak & Bantuan</h3>
                <p class="text-xs text-gray-500">
                    Informasi kontak resmi bila membutuhkan bantuan lebih lanjut.
                </p>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('public.doctors') }}"
               class="inline-block bg-teal-500 text-white font-semibold px-8 py-3 rounded-full
                      hover:bg-teal-600 transition text-xs md:text-sm shadow-md">
                Lihat Dokter & Jadwal Lengkap
            </a>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- WHY RS DIGITAL (Highlight) --}}
    {{-- ========================================================= --}}
    <section class="bg-gray-50 py-14">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    Kenapa Memilih <span class="text-teal-700">RS Digital?</span>
                </h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto">
                    Project ini menggambarkan bagaimana rumah sakit modern mengelola layanan secara
                    terpusat melalui satu sistem digital, mulai dari admin, dokter, hingga pasien.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-5 border border-teal-50">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 mb-3">
                        <i class="fa fa-database"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Data Terpusat</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Semua modul – pengguna, obat, poli, jadwal, janji temu, dan rekam medis –
                        terintegrasi dalam satu sistem CMS.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-5 border border-teal-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                        <i class="fa fa-user-shield"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Peran yang Jelas</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Admin, dokter, pasien, dan guest punya akses berbeda sesuai tugas:
                        dari validasi janji hingga pembuatan rekam medis.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-5 border border-teal-50">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 mb-3">
                        <i class="fa fa-route"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Alur Layanan Jelas</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pasien booking, admin/dokter validasi, dokter periksa, lalu sistem
                        membuat rekam medis dan resep secara otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- ABOUT SECTION --}}
    {{-- ========================================================= --}}
    <section id="about-section" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-10">

            <div class="relative">
                <img src="{{ asset('image/doctorcewek.jpg') }}"
                     alt="Dokter"
                     class="rounded-xl shadow-xl w-full h-auto object-cover max-h-[460px]">
            </div>

            <div>
                <h4 class="text-teal-600 font-semibold text-xs uppercase mb-2">Tentang Kami</h4>
                <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-4 leading-snug">
                    Rumah Sakit Modern dengan Layanan Terintegrasi.
                </h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                    Sistem ini dirancang untuk mendukung digitalisasi rumah sakit:
                    mulai dari manajemen pengguna, poli, obat, jadwal, hingga rekam medis pasien.
                </p>
                <p class="text-sm text-gray-600 leading-relaxed mb-6">
                    Dari sisi pasien, portal memberikan transparansi status janji temu dan riwayat
                    perawatan. Dari sisi dokter dan admin, dashboard memudahkan monitoring dan
                    pengambilan keputusan.
                </p>
                <a href="#services-section"
                   class="inline-block bg-teal-500 text-white font-semibold px-6 py-2.5 rounded-full
                          hover:bg-teal-600 transition text-xs">
                    Lihat Spesialisasi & Poli
                </a>
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- SERVICES / POLI SECTION --}}
    {{-- ========================================================= --}}
    <section id="services-section" class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-2xl md:text-3xl font-bold text-teal-700 mb-3 text-center">
            Layanan & Spesialisasi
        </h2>
        <p class="text-center text-sm text-gray-600 mb-10 max-w-2xl mx-auto">
            Daftar poli dan layanan medis yang tersedia di rumah sakit kami.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @if(isset($polis) && $polis->count())
                @foreach ($polis as $poli)
                    <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-teal-500
                                hover:shadow-xl hover:-translate-y-0.5 transition">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">{{ $poli->name }}</h3>
                                <p class="text-[11px] text-teal-500 uppercase tracking-wide">
                                    Poli / Layanan
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $poli->description ?? 'Deskripsi poli ini belum tersedia.' }}
                        </p>

                        @if(isset($poli->doctors_count))
                            <p class="mt-3 text-[11px] text-gray-500">
                                <i class="fa fa-user-md mr-1 text-teal-500"></i>
                                {{ $poli->doctors_count }} dokter terdaftar di poli ini.
                            </p>
                        @endif

                        <a href="{{ route('public.doctors') }}"
                           class="mt-3 inline-flex items-center text-[11px] text-teal-600 font-semibold hover:text-teal-800">
                            Lihat dokter di poli ini
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="lg:col-span-3 text-center py-10">
                    <p class="text-sm text-gray-500">
                        Data layanan (poli) belum tersedia.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- FLOW / ALUR LAYANAN ONLINE --}}
    {{-- ========================================================= --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 text-center">
                Alur Layanan Online
            </h2>
            <p class="text-center text-sm text-gray-600 mb-10 max-w-2xl mx-auto">
                Gambaran sederhana perjalanan pasien di dalam sistem: dari guest, menjadi
                pasien terdaftar, hingga konsultasi dengan dokter.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col items-start">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">
                            1
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800">Guest & Informasi Publik</h3>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pengunjung melihat daftar poli, profil dokter, dan jadwal praktik tanpa
                        perlu login. Halaman yang sedang Anda lihat adalah bagian ini.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col items-start">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">
                            2
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800">Pasien Login & Booking</h3>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Setelah registrasi, pasien dapat membuat janji temu dengan memilih poli,
                        dokter, dan jadwal yang tersedia langsung dari portal.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-5 flex flex-col items-start">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-sm font-bold">
                            3
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800">Dokter & Rekam Medis</h3>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Dokter memvalidasi janji, melakukan pemeriksaan, lalu membuat rekam
                        medis dan resep yang dapat diakses kembali oleh pasien.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- TESTIMONI / FEEDBACK DUMMY --}}
    {{-- ========================================================= --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 text-center">
                Apa Kata Pasien?
            </h2>
            <p class="text-center text-sm text-gray-600 mb-10 max-w-2xl mx-auto">
                Contoh testimoni untuk menggambarkan pengalaman pengguna dengan sistem
                rumah sakit digital ini.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-xl shadow-sm p-5 border border-gray-100">
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        “Saya bisa cek jadwal dokter dan buat janji tanpa harus antre di rumah sakit.
                        Notifikasi status janji juga sangat membantu.”
                    </p>
                    <p class="text-xs font-semibold text-gray-800">Rani • Pasien Rawat Jalan</p>
                </div>

                <div class="bg-gray-50 rounded-xl shadow-sm p-5 border border-gray-100">
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        “Sebagai dokter, saya mudah melihat antrean, mengisi rekam medis, dan
                        menuliskan resep dalam satu layar yang rapi.”
                    </p>
                    <p class="text-xs font-semibold text-gray-800">dr. Dimas • Dokter Umum</p>
                </div>

                <div class="bg-gray-50 rounded-xl shadow-sm p-5 border border-gray-100">
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        “Dari sisi admin, data janji temu dan stok obat lebih terkontrol. Proses
                        operasional terasa jauh lebih terstruktur.”
                    </p>
                    <p class="text-xs font-semibold text-gray-800">Nia • Admin Rumah Sakit</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- CONTACT SECTION --}}
    {{-- ========================================================= --}}
    <section id="contact-section" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-teal-700 mb-3 text-center">
                Hubungi Kami
            </h2>
            <p class="text-center text-sm text-gray-600 mb-10 max-w-2xl mx-auto">
                Untuk informasi lebih lanjut mengenai layanan rumah sakit, silakan gunakan kontak di bawah ini.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                {{-- Info kontak --}}
                <div class="p-6 bg-white rounded-xl shadow-md border-t-4 border-teal-500 space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fa fa-map-marker-alt text-xl text-teal-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Alamat Rumah Sakit</p>
                            <p class="text-xs text-gray-600">
                                Jl. Perintis Kemerdekaan 8, Jakarta, Indonesia
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa fa-phone text-xl text-teal-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Telepon</p>
                            <p class="text-xs text-gray-600">+62 812 3456 7890</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa fa-envelope text-xl text-teal-500 mt-1"></i>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Email</p>
                            <p class="text-xs text-gray-600">info@rs-digital.com</p>
                        </div>
                    </div>
                </div>

                {{-- Form kontak (dummy) --}}
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <h3 class="text-sm font-semibold text-teal-700 mb-4">
                        Kirim Pesan
                    </h3>
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                                          focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                                          focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-xs font-medium text-gray-700 mb-1">Pesan</label>
                            <textarea id="message" name="message" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                                             focus:border-teal-500 focus:ring-teal-500"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full md:w-auto bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold
                                       py-2.5 px-6 rounded-full transition">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
