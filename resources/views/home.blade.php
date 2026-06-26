@extends('layouts.app')

@section('content')

{{-- HERO SECTION --}}
<section class="relative pt-24 pb-16 overflow-hidden bg-gradient-to-br from-[#FFF8F8] to-[#fbe2e2]">
    <!-- Decorative elements (clipped by overflow-hidden on section) -->
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-gradient-to-bl from-rose-200 to-transparent opacity-50 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-gradient-to-tr from-[#800000] to-transparent opacity-10 blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 flex flex-col md:grid md:grid-cols-2 gap-10 md:gap-16 items-center relative z-10">

        {{-- TOP ON MOBILE: Gambar --}}
        <div class="relative w-full order-first md:order-last" data-aos="zoom-in" data-aos-delay="200">
            <div class="absolute inset-0 bg-gradient-to-tr from-[#800000]/20 to-transparent rounded-[30px] md:rounded-[40px] transform rotate-3 scale-105 -z-10 transition-transform duration-500 hover:rotate-6"></div>
            <img src="sehat.png" alt="Mental & Physical Healthcare" class="rounded-[30px] md:rounded-[40px] shadow-2xl object-cover w-full h-[260px] sm:h-[320px] md:h-auto md:max-h-[500px] transform transition duration-500 hover:scale-[1.02]" onerror="this.src='https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=1000&auto=format&fit=crop';">
        </div>

        {{-- BOTTOM ON MOBILE: Teks --}}
        <div class="w-full order-last md:order-first">
            <div data-aos="fade-down" class="inline-block px-4 py-2 rounded-full bg-red-100 text-[#800000] font-semibold text-xs mb-4 tracking-wider uppercase border border-red-200 shadow-sm">
                Kesehatan Holistik & Mental
            </div>

            <h1 data-aos="fade-up" data-aos-delay="100" class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-[1.2] text-gray-900">
                Temukan Keseimbangan
                <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#800000] to-[#d63333]">Pikiran & Tubuh</span>
            </h1>

            <p data-aos="fade-up" data-aos-delay="200" class="mt-4 text-base sm:text-lg text-gray-600 leading-relaxed font-light">
                Kesehatan sejati bukan hanya fisik, tapi juga pikiran yang tenang. Dapatkan dukungan, informasi, dan konsultasi interaktif untuk kesehatan fisik dan mental Anda setiap saat.
            </p>

            <div data-aos="fade-up" data-aos-delay="300" class="mt-8 flex flex-col sm:flex-row flex-wrap items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row flex-wrap gap-4 w-full sm:w-auto">
                    <a href="#" onclick="document.getElementById('chatToggle').click(); return false;" class="bg-white text-[#800000] px-6 py-3 rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition duration-300 font-bold flex items-center justify-center gap-2">
                        Mulai Konsultasi 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="#layanan" class="bg-white text-gray-800 px-6 py-3 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition duration-300 font-semibold flex items-center justify-center gap-2">
                        Eksplorasi Layanan
                    </a>
                </div>

                <!-- Badge -->
                <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium leading-none mb-1">Layanan Digital</p>
                        <p class="font-bold text-gray-900 text-sm leading-none">Aktif 24/7</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- HEALTH STATS --}}
<section class="py-12 md:py-16 bg-white relative z-20 -mt-10 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-center">
            
            <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-2 transition duration-300">
                <div class="w-12 h-12 mx-auto bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">1 dari 4</h3>
                <p class="mt-2 text-xs text-gray-500 leading-relaxed">Orang akan mengalami masalah kesehatan mental</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-2 transition duration-300">
                <div class="w-12 h-12 mx-auto bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">7-8 Jam</h3>
                <p class="mt-2 text-xs text-gray-500 leading-relaxed">Waktu istirahat ideal untuk regenerasi sel otak & tubuh</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-2 transition duration-300">
                <div class="w-12 h-12 mx-auto bg-green-50 rounded-2xl flex items-center justify-center text-green-500 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">30 Menit</h3>
                <p class="mt-2 text-xs text-gray-500 leading-relaxed">Aktivitas fisik setiap hari menurunkan stres</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="400" class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 hover:-translate-y-2 transition duration-300">
                <div class="w-12 h-12 mx-auto bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Cegah</h3>
                <p class="mt-2 text-xs text-gray-500 leading-relaxed">Konsultasi dini sebelum masalah membesar</p>
            </div>

        </div>
    </div>
</section>

{{-- ABOUT SECTION --}}
<section id="about" class="py-16 md:py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            {{-- Kiri: Gambar/Ilustrasi --}}
            <div class="lg:w-1/2 relative" data-aos="fade-right">
                <div class="absolute inset-0 bg-gradient-to-tr from-red-100 to-transparent rounded-[3rem] transform -rotate-3 scale-105 -z-10"></div>
                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1000&auto=format&fit=crop" alt="Tim HealthSelf" class="rounded-[3rem] shadow-2xl object-cover w-full h-[450px]">
                
                {{-- Floating Badge --}}
                <div class="absolute bottom-10 -right-8 bg-white p-6 rounded-3xl shadow-[0_20px_50px_rgba(128,0,0,0.15)] hidden md:block">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center text-[#800000] text-2xl">
                            🤝
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-gray-900">100%</p>
                            <p class="text-sm font-semibold text-gray-500">Privasi Terjaga</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Teks --}}
            <div class="lg:w-1/2" data-aos="fade-left">
                <h4 class="text-[#800000] font-bold tracking-widest uppercase text-xs mb-2">Tentang Kami</h4>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                    Misi Kami: <br>Menyatukan Kesehatan Fisik & Mental
                </h2>
                <p class="text-gray-600 mb-6 text-base leading-relaxed">
                    HealthSelf didirikan dengan keyakinan bahwa kesehatan mental sama pentingnya dengan kesehatan fisik. Kami tidak hanya mengedukasi masyarakat mengenai gaya hidup sehat, tetapi juga menyediakan ruang konsultasi profesional yang aman.
                </p>
                <p class="text-gray-600 mb-6 text-base leading-relaxed">
                    Kesehatan sejati tidak bisa dipisahkan antara tubuh dan pikiran. Kami hadir untuk menghapus stigma mengenai perawatan kesehatan mental dan mempermudah akses informasi kesehatan yang valid.
                </p>
                <p class="text-base text-gray-600 mb-8 leading-relaxed">
                    Melalui integrasi teknologi AI untuk pertolongan pertama psikologis (chatbot) dan artikel edukasi dari para profesional, kami berharap bisa menjadi teman perjalanan Anda menuju versi diri yang lebih sehat, tenang, dan bahagia.
                </p>

                <div class="grid grid-cols-2 gap-8 mt-10 border-t border-gray-100 pt-10">
                    <div>
                        <h3 class="text-4xl font-extrabold text-[#800000] mb-2">24/7</h3>
                        <p class="font-bold text-gray-900 mb-1">Dukungan AI</p>
                        <p class="text-sm text-gray-500 leading-relaxed">Teman mengobrol kapan saja Anda butuhkan.</p>
                    </div>
                    <div>
                        <h3 class="text-4xl font-extrabold text-[#800000] mb-2">Aman</h3>
                        <p class="font-bold text-gray-900 mb-1">Kerahasiaan Data</p>
                        <p class="text-sm text-gray-500 leading-relaxed">Sistem terenkripsi untuk kenyamanan berbagi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MENTAL HEALTH FOCUS SECTION --}}
<section id="mental-health" class="py-16 md:py-24 bg-gray-50 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        
        <div class="flex flex-col md:flex-row gap-12 items-center">
            <div class="md:w-1/2" data-aos="fade-right">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=800&auto=format&fit=crop" alt="Mindfulness" class="rounded-3xl shadow-lg w-full h-64 object-cover">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop" alt="Support" class="rounded-3xl shadow-lg w-full h-64 object-cover mt-12">
                </div>
            </div>
            
            <div class="md:w-1/2" data-aos="fade-left">
                <h4 class="text-[#800000] font-bold tracking-widest uppercase text-xs mb-2">Fokus Kami</h4>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                    Kesehatan Mental Adalah <br>Prioritas Utama
                </h2>
                <p class="text-gray-600 mb-6 text-base leading-relaxed">
                    Stres, kecemasan, dan kelelahan mental dapat berdampak nyata pada kondisi fisik Anda. Mengenali gejalanya dan melakukan intervensi dini adalah kunci mencegah masalah yang lebih serius.
                </p>
                
                <ul class="space-y-6">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-[#800000] mt-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-900">Manajemen Stres</h4>
                            <p class="mt-1 text-gray-500">Teknik praktis untuk mengurangi ketegangan dan mengelola emosi sehari-hari.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-[#800000] mt-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-xl font-bold text-gray-900">Mindfulness & Meditasi</h4>
                            <p class="mt-1 text-gray-500">Panduan menemukan ketenangan dalam kebisingan dunia modern.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>

{{-- FEATURE SECTION --}}
<section class="py-16 md:py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">

        <div class="text-center max-w-2xl mx-auto mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Layanan Terintegrasi
            </h2>
            <p class="text-gray-600 mt-4 text-base">Kami memadukan teknologi pintar dengan empati profesional untuk memberikan layanan yang holistik dan praktis.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            {{-- CARD 1 --}}
            <div data-aos="fade-up" data-aos-delay="100" class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100 hover:bg-white hover:shadow-[0_20px_50px_rgba(87,3,3,0.08)] transition-all duration-300 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">
                    🧠
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Konsultasi Chatbot AI</h3>
                <p class="text-gray-600 leading-relaxed text-sm">
                    Tanya jawab kesehatan kapan saja dengan Chatbot pintar kami yang siap membantu Anda 24/7 sebelum Anda terhubung dengan konselor.
                </p>
            </div>

            {{-- CARD 2 --}}
            <div data-aos="fade-up" data-aos-delay="200" class="bg-[#800000] rounded-[2rem] p-8 shadow-xl transform md:-translate-y-4 hover:-translate-y-6 transition-transform duration-300 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 rounded-full bg-white opacity-10"></div>
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl mb-6">
                    💬
                </div>
                <h3 class="text-xl font-bold text-white mb-4">Konselor Profesional</h3>
                <p class="text-red-100 leading-relaxed text-sm">
                    Terhubung langsung dengan konselor berpengalaman untuk membicarakan masalah kesehatan mental, kecemasan, atau keluhan lainnya secara personal.
                </p>
            </div>

            {{-- CARD 3 --}}
            <div data-aos="fade-up" data-aos-delay="300" class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100 hover:bg-white hover:shadow-[0_20px_50px_rgba(87,3,3,0.08)] transition-all duration-300 group">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">
                    ❤️
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Artikel Edukatif</h3>
                <p class="text-gray-600 leading-relaxed text-sm">
                    Akses puluhan artikel mengenai kesehatan fisik dan mental, divalidasi oleh konselor untuk memastikan Anda mendapatkan informasi yang kredibel.
                </p>
            </div>
        </div>

    </div>
</section>


{{-- HEALTH TIPS --}}
<section class="py-12 md:py-16 bg-[#FFF8F8] relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-2" data-aos="fade-up">
            <div class="max-w-xl">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Tips Seimbang</h2>
                <p class="text-gray-600 mt-2 text-base">Langkah kecil harian yang berdampak besar untuk kualitas hidup Anda secara utuh.</p>
            </div>
            <a href="{{ url('/#artikel') }}" class="text-[#800000] font-bold hover:underline flex items-center gap-1">
                Baca Artikel <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            {{-- CARD --}}
            <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                <div class="text-3xl mb-4">🧘‍♀️</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Meditasi Pagi</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Sisihkan 5 menit sebelum memulai hari untuk mengatur nafas dan menenangkan pikiran.</p>
            </div>

            {{-- CARD --}}
            <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                <div class="text-3xl mb-4">📵</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Digital Detox</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Jauhkan layar gawai 1 jam sebelum tidur untuk meningkatkan kualitas tidur.</p>
            </div>

            {{-- CARD --}}
            <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                <div class="text-3xl mb-4">🥗</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Nutrisi Otak</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Konsumsi makanan kaya omega-3, buah, dan sayur untuk mood yang lebih baik.</p>
            </div>
            
            {{-- CARD --}}
            <div data-aos="fade-up" data-aos-delay="400" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg transition">
                <div class="text-3xl mb-4">🏃‍♂️</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Gerak Aktif</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Berjalan kaki ringan memicu endorfin yang dapat meredakan stres dan cemas.</p>
            </div>
        </div>

    </div>
</section>

{{-- DEEPER INFO SECTION --}}
<section class="py-16 md:py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Memulai Perjalanan Sehat Anda?</h2>
            <p class="text-gray-500 text-base mb-8 max-w-2xl mx-auto leading-relaxed">
                Kesehatan mental seringkali tidak terlihat. Mengenali gejala awalnya adalah langkah pertama menuju pemulihan dan kualitas hidup yang lebih baik.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
            <div class="bg-red-50/50 p-10 rounded-3xl border border-red-100">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-xl mb-6">⚠️</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Tanda Kelelahan Mental (Burnout)</h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start gap-3"><span class="text-[#800000] font-bold">•</span> Merasa lelah sepanjang waktu meski sudah cukup tidur.</li>
                    <li class="flex items-start gap-3"><span class="text-[#800000] font-bold">•</span> Kehilangan minat pada aktivitas yang biasanya disukai.</li>
                    <li class="flex items-start gap-3"><span class="text-[#800000] font-bold">•</span> Mudah marah, tersinggung, atau merasa hampa.</li>
                    <li class="flex items-start gap-3"><span class="text-[#800000] font-bold">•</span> Sulit berkonsentrasi dan produktivitas menurun drastis.</li>
                </ul>
            </div>

            <div class="bg-blue-50/50 p-10 rounded-3xl border border-blue-100">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-xl mb-6">🏥</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Kapan Mencari Bantuan Profesional?</h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start gap-3"><span class="text-blue-500 font-bold">•</span> Emosi negatif mulai mengganggu aktivitas sehari-hari & pekerjaan.</li>
                    <li class="flex items-start gap-3"><span class="text-blue-500 font-bold">•</span> Mengalami gangguan tidur kronis atau perubahan pola makan drastis.</li>
                    <li class="flex items-start gap-3"><span class="text-blue-500 font-bold">•</span> Muncul pemikiran untuk menyakiti diri sendiri.</li>
                    <li class="flex items-start gap-3"><span class="text-blue-500 font-bold">•</span> Merasa tidak ada jalan keluar atas masalah yang dihadapi.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- FAQ SECTION --}}
<section class="py-16 md:py-24 bg-gray-50 border-t border-gray-100 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 max-w-3xl">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900">FAQ (Tanya Jawab)</h2>
            <p class="text-gray-500 mt-4 text-lg">Pertanyaan yang sering diajukan mengenai layanan kami.</p>
        </div>

        <div class="space-y-4">
            <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                    Apakah konsultasi dengan HealthSelf AI berbayar?
                    <span class="transition group-open:rotate-180 text-gray-400">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <p class="text-gray-500 mt-4 leading-relaxed">
                    Tidak, saat ini penggunaan chatbot cerdas kami untuk mendapatkan informasi dan dukungan emosional awal sepenuhnya gratis dan dapat diakses 24 jam sehari.
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                    Apakah chatbot ini bisa mendiagnosis penyakit fisik atau mental saya?
                    <span class="transition group-open:rotate-180 text-gray-400">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <p class="text-gray-500 mt-4 leading-relaxed">
                    Tidak. HealthSelf AI dirancang sebagai sumber edukasi dan pertolongan pertama (first-aid information). AI ini tidak menggantikan peran dokter, psikolog, atau psikiater. Jika Anda memiliki gejala serius, kami sangat menyarankan Anda menghubungi tenaga medis profesional.
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                    Bagaimana dengan kerahasiaan data dan curhatan saya?
                    <span class="transition group-open:rotate-180 text-gray-400">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <p class="text-gray-500 mt-4 leading-relaxed">
                    Privasi Anda sangat penting bagi kami. Kami tidak pernah membagikan histori obrolan atau informasi pribadi Anda kepada pihak ketiga. Semua komunikasi dienkripsi dan diproses secara otomatis oleh sistem kami.
                </p>
            </details>

            <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                <summary class="flex items-center justify-between font-bold text-gray-900 text-lg">
                    Apakah ini layanan yang tepat untuk kondisi krisis darurat?
                    <span class="transition group-open:rotate-180 text-gray-400">
                        <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                    </span>
                </summary>
                <p class="text-gray-500 mt-4 leading-relaxed">
                    Tidak. Jika Anda sedang berada dalam keadaan darurat atau memiliki pikiran untuk mengakhiri hidup, mohon segera hubungi layanan gawat darurat di kota Anda (119) atau hotline kesehatan jiwa (119 ext 8) sekarang juga.
                </p>
            </details>
        </div>
    </div>
</section>

{{-- CTA SECTION --}}
<section class="py-16 md:py-24 relative overflow-hidden">
    <!-- Gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#570303] to-[#800000]"></div>
    
    <!-- Abstract shapes (contained) -->
    <div class="absolute top-0 right-0 w-72 h-72 bg-white opacity-5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-white opacity-5 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 sm:px-6 text-center text-white relative z-10">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight max-w-3xl mx-auto">
            Jangan Simpan Sendiri. Kami Di Sini Untuk Mendengar Anda.
        </h2>
        <p class="mt-6 text-xl text-red-100 max-w-2xl mx-auto font-light">
            Ceritakan keluhan fisik atau beban pikiran Anda. Chatbot cerdas kami siap memberikan respon yang empati dan informatif kapan pun Anda butuhkan.
        </p>

        <a href="#" onclick="document.getElementById('chatToggle').click(); return false;" class="inline-flex items-center gap-3 mt-10 bg-white text-[#800000] px-10 py-4 rounded-full font-bold hover:bg-red-50 hover:scale-105 transition duration-300 shadow-xl">
            <span>Mulai Bicara Sekarang</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>
</section>

{{-- ARTIKEL TERBARU --}}
<section id="artikel" class="py-16 md:py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">

        <div class="flex justify-between items-end mb-12">
            <div>
                <h4 class="text-[#800000] font-bold tracking-widest uppercase text-sm mb-2">Edukasi Kesehatan</h4>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900">Artikel Terbaru</h2>
            </div>
        </div>

        @if(isset($artikels) && $artikels->count() > 0)
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($artikels as $artikel)
            <a href="{{ route('artikel.show', $artikel) }}" class="group block bg-gray-50 rounded-[2rem] overflow-hidden border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div style="height:200px; overflow:hidden;">
                    <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; height:100%; object-fit:cover; transition:transform 0.4s;" class="group-hover:scale-105" alt="{{ $artikel->judul }}">
                </div>
                <div class="p-7">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold text-[#800000] bg-red-50 px-3 py-1 rounded-full border border-red-100">{{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 leading-tight mb-3 group-hover:text-[#800000] transition">{{ $artikel->judul }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ Str::limit(strip_tags($artikel->isi_konten), 100) }}</p>
                    <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-100">
                        <img src="{{ $artikel->pembuat->avatarUrl() }}" class="w-7 h-7 rounded-full object-cover" alt="">
                        <span class="text-xs text-gray-500">{{ $artikel->pembuat->name }}</span>
                        <span class="text-gray-300 text-xs ml-auto">{{ $artikel->tanggal_publish?->format('d M Y') }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border border-gray-100">
            <div class="text-5xl mb-4">📝</div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Artikel</h3>
            <p class="text-gray-500 text-sm">Artikel akan segera tersedia setelah diverifikasi oleh konselor.</p>
        </div>
        @endif

    </div>
</section>

@endsection