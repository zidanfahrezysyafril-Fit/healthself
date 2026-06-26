<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'HealthSelf') – Platform Kesehatan Digital</title>
    <meta name="description" content="@yield('meta-description', 'Platform informasi kesehatan digital yang membantu Anda mendapatkan edukasi dan konsultasi kesehatan.')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        html { overflow-x: hidden; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; max-width: 100vw; }

        @keyframes chatFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .chat-anim { animation: chatFadeIn 0.3s ease-out forwards; }

        /* USER DROPDOWN */
        .user-dropdown { position: relative; }
        .user-dropdown-menu {
            position: absolute; top: calc(100% + 10px); right: 0;
            background: white; border-radius: 16px; padding: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            border: 1px solid #f3f4f6;
            min-width: 200px;
            opacity: 0; visibility: hidden;
            transform: translateY(-6px);
            transition: all 0.2s;
            z-index: 999;
        }
        .user-dropdown:hover .user-dropdown-menu,
        .user-dropdown.open .user-dropdown-menu {
            opacity: 1; visibility: visible; transform: translateY(0);
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            font-size: 0.875rem; font-weight: 500; color: #374151;
            text-decoration: none; transition: background 0.15s;
            border: none; background: none; width: 100%; cursor: pointer;
            font-family: inherit;
        }
        .dropdown-item:hover { background: #f9fafb; }
        .dropdown-item.danger { color: #991b1b; }
        .dropdown-item.danger:hover { background: #fef2f2; }
        .dropdown-divider { height: 1px; background: #f3f4f6; margin: 6px 0; }

        /* NOTIFICATION BADGE */
        .notif-badge {
            background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800;
            padding: 2px 5px; border-radius: 10px; position: absolute; top: -4px; right: -6px;
        }

        /* SESSION FLASH */
        .flash-toast {
            position: fixed; top: 80px; right: 20px; z-index: 9999;
            padding: 14px 20px; border-radius: 14px; font-size: 0.875rem; font-weight: 600;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            animation: toastIn 0.3s ease-out forwards;
        }
        .flash-success { background: #f0fdf4; color: #166534; border: 1.5px solid #86efac; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1.5px solid #fca5a5; }
        @keyframes toastIn { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
    </style>
</head>

<body class="bg-[#fcfdfd] text-gray-800 antialiased overflow-x-hidden">

    {{-- SESSION FLASH TOASTS --}}
    @if(session('success'))
        <div class="flash-toast flash-success" id="flash-toast">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-toast flash-error" id="flash-toast">⚠ {{ session('error') }}</div>
    @endif

    {{-- NAVBAR --}}
    <nav id="navbar" class="bg-white/85 backdrop-blur-xl shadow-[0_4px_40px_rgba(0,0,0,0.04)] fixed top-0 left-0 w-full z-50 transition-all duration-500 border-b border-gray-100">

        <div class="w-full mx-auto px-4 sm:px-8 md:px-12 lg:px-24 py-3 md:py-4 flex justify-between items-center">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.jpg') }}" alt="HealthSelf Logo" class="w-12 h-12 object-cover rounded-[14px] shadow-sm transform group-hover:scale-105 transition-all duration-300" style="mix-blend-mode: multiply;">
                <div class="flex flex-col justify-center text-left">
                    <span class="text-[24px] font-extrabold bg-gradient-to-r from-[#800000] to-[#570303] bg-clip-text text-transparent tracking-tight leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">HealthSelf</span>
                    <span class="text-[11px] font-bold text-[#800000] tracking-widest uppercase mt-[4px] leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif; opacity: 0.85;">Mind & Body Care</span>
                </div>
            </a>

            {{-- MENU --}}
            <ul class="hidden md:flex items-center gap-8 text-[15px] font-bold text-gray-600">
                <li>
                    <a href="{{ url('/') }}" class="relative px-2 py-2 hover:text-[#800000] transition-colors duration-300 group flex flex-col items-center">
                        Home
                        <span class="absolute -bottom-1 w-0 h-[3px] bg-gradient-to-r from-[#800000] to-[#b91c1c] rounded-full transition-all duration-300 group-hover:w-full opacity-0 group-hover:opacity-100"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/#artikel') }}" class="relative px-2 py-2 hover:text-[#800000] transition-colors duration-300 group flex flex-col items-center">
                        Artikel
                        <span class="absolute -bottom-1 w-0 h-[3px] bg-gradient-to-r from-[#800000] to-[#b91c1c] rounded-full transition-all duration-300 group-hover:w-full opacity-0 group-hover:opacity-100"></span>
                    </a>
                </li>
                <li>
                    @auth
                        <a href="#" onclick="document.getElementById('chatToggle').click(); return false;" class="relative px-2 py-2 hover:text-[#800000] transition-colors duration-300 group flex flex-col items-center">
                            Konsultasi
                            <span class="absolute -bottom-1 w-0 h-[3px] bg-gradient-to-r from-[#800000] to-[#b91c1c] rounded-full transition-all duration-300 group-hover:w-full opacity-0 group-hover:opacity-100"></span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="relative px-2 py-2 hover:text-[#800000] transition-colors duration-300 group flex flex-col items-center">
                            Konsultasi
                            <span class="absolute -bottom-1 w-0 h-[3px] bg-gradient-to-r from-[#800000] to-[#b91c1c] rounded-full transition-all duration-300 group-hover:w-full opacity-0 group-hover:opacity-100"></span>
                        </a>
                    @endauth
                </li>
                <li>
                    <a href="{{ url('/#about') }}" class="relative px-2 py-2 hover:text-[#800000] transition-colors duration-300 group flex flex-col items-center">
                        About
                        <span class="absolute -bottom-1 w-0 h-[3px] bg-gradient-to-r from-[#800000] to-[#b91c1c] rounded-full transition-all duration-300 group-hover:w-full opacity-0 group-hover:opacity-100"></span>
                    </a>
                </li>
            </ul>

            {{-- BUTTON / USER --}}
            <div class="hidden md:flex items-center gap-6">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="bg-[#FFF4F4] text-[#800000] hover:bg-[#800000] hover:text-white px-5 py-2.5 rounded-full text-[14px] font-bold transition-all duration-300 shadow-sm border border-[#FCE7E7]">Panel Admin</a>
                    @elseif(auth()->user()->role === 'konselor')
                        <a href="{{ route('konselor.dashboard') }}" class="bg-[#EEF2FF] text-[#4F46E5] hover:bg-[#4F46E5] hover:text-white px-5 py-2.5 rounded-full text-[14px] font-bold transition-all duration-300 shadow-sm border border-[#E0E7FF]">Panel Konselor</a>
                    @endif
                    
                    <div class="user-dropdown">
                        <button onclick="this.closest('.user-dropdown').classList.toggle('open')" class="flex items-center gap-3 text-gray-800 font-bold focus:outline-none bg-white border border-gray-200 pl-2 pr-4 py-1.5 rounded-full shadow-sm hover:shadow-md hover:border-gray-300 transition-all text-[15px] group">
                            <img src="{{ auth()->user()->avatarUrl() }}" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform" alt="">
                            <span class="hidden lg:block truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-[#800000] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        {{-- DROPDOWN MENU --}}
                        <div class="user-dropdown-menu">
                            <div style="padding:12px 16px; border-bottom:1px solid #f3f4f6; margin-bottom:8px; background:#f8fafc; border-radius:12px 12px 0 0;">
                                <div style="font-weight:800; font-size:0.9rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                                <div style="font-size:0.75rem; color:#64748b; font-weight:600; margin-top:2px; text-transform:uppercase; letter-spacing:0.5px;">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>

                            @if(auth()->user()->isMahasiswa())
                                <a href="{{ route('mahasiswa.profile') }}" class="dropdown-item">👤 Profil Saya</a>
                                <a href="{{ route('mahasiswa.feedback.create') }}" class="dropdown-item">⭐ Beri Feedback</a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">📊 Dashboard Admin</a>
                                <a href="{{ route('admin.users.index') }}" class="dropdown-item">👥 Kelola User</a>
                            @elseif(auth()->user()->isKonselor())
                                <a href="{{ route('konselor.dashboard') }}" class="dropdown-item">📊 Dashboard</a>
                                <a href="{{ route('konselor.chat.index') }}" class="dropdown-item">💬 Monitor Chat</a>
                            @endif

                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="dropdown-item danger font-bold">⏻ Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#800000] font-bold text-[15px] transition-colors px-2">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#800000] to-[#570303] text-white px-7 py-3 rounded-full shadow-[0_4px_15px_rgba(128,0,0,0.2)] hover:shadow-[0_8px_25px_rgba(128,0,0,0.3)] hover:-translate-y-0.5 transition-all duration-300 font-bold text-[15px]">Daftar</a>
                @endauth
            </div>

            {{-- HAMBURGER BUTTON (MOBILE) --}}
            <button id="mobileMenuBtn" class="md:hidden flex items-center justify-center p-2 text-gray-800 hover:text-[#800000] focus:outline-none transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

        </div>

        {{-- MOBILE MENU DROPDOWN --}}
        <div id="mobileMenu" class="absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl border-b border-gray-100 shadow-xl hidden flex-col py-4 px-6 md:hidden transition-all origin-top">
            <ul class="flex flex-col gap-4 text-[15px] font-bold text-gray-600 mb-6 border-b border-gray-100 pb-6">
                <li><a href="{{ url('/') }}" class="block w-full hover:text-[#800000] transition">Home</a></li>
                <li><a href="{{ url('/#artikel') }}" class="block w-full hover:text-[#800000] transition">Artikel</a></li>
                <li>
                    @auth
                        <a href="#" onclick="document.getElementById('chatToggle').click(); document.getElementById('mobileMenuBtn').click(); return false;" class="block w-full hover:text-[#800000] transition">Konsultasi</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full hover:text-[#800000] transition">Konsultasi</a>
                    @endauth
                </li>
                <li><a href="{{ url('/#about') }}" class="block w-full hover:text-[#800000] transition">About</a></li>
            </ul>

            <div class="flex flex-col gap-4">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-center bg-[#FFF4F4] text-[#800000] px-5 py-2.5 rounded-full font-bold">Panel Admin</a>
                    @elseif(auth()->user()->role === 'konselor')
                        <a href="{{ route('konselor.dashboard') }}" class="text-center bg-[#EEF2FF] text-[#4F46E5] px-5 py-2.5 rounded-full font-bold">Panel Konselor</a>
                    @endif
                    <a href="{{ route('mahasiswa.profile') }}" class="text-center border border-gray-200 text-gray-700 px-5 py-2.5 rounded-full font-bold">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0; width:100%;">
                        @csrf
                        <button type="submit" class="w-full text-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full font-bold">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-center border border-gray-200 text-gray-700 font-bold text-[15px] px-5 py-2.5 rounded-full">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center bg-gradient-to-r from-[#800000] to-[#570303] text-white px-5 py-2.5 rounded-full font-bold text-[15px] shadow-md">Daftar</a>
                @endauth
            </div>
        </div>

    </nav>


    {{-- CONTENT --}}
    <main class="pt-20">
        @yield('content')
    </main>


    {{-- FOOTER --}}
    <footer class="relative mt-20 pt-20 pb-10 overflow-hidden bg-[#2d0000]">
        <!-- Decorative Backgrounds -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-[150px] -right-[150px] w-[500px] h-[500px] bg-[#800000] rounded-full blur-[100px] opacity-30"></div>
            <div class="absolute -bottom-[150px] -left-[150px] w-[500px] h-[500px] bg-[#570303] rounded-full blur-[120px] opacity-40"></div>
        </div>
        
        <div class="container mx-auto px-8 md:px-12 lg:px-24 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 border-b border-white/10 pb-14">
                
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6 group">
                        <img src="{{ asset('images/logo.jpg') }}" alt="HealthSelf Logo" class="w-12 h-12 object-cover rounded-xl shadow-lg transform group-hover:scale-105 transition duration-300">
                        <div class="flex flex-col justify-center text-left">
                            <span class="text-[24px] font-extrabold text-white tracking-tight leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">HealthSelf</span>
                            <span class="text-[11px] font-bold text-white tracking-widest uppercase mt-[4px] leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif; opacity: 0.85;">Mind & Body Care</span>
                        </div>
                    </a>
                    <p class="text-red-100/70 text-sm leading-relaxed mb-6">
                        Platform kesehatan digital yang mendedikasikan diri untuk memberikan informasi terpercaya, konsultasi awal yang mudah, dan mengedukasi masyarakat menuju gaya hidup yang lebih sehat.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#800000] hover:border-[#800000] hover:-translate-y-1 transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#800000] hover:border-[#800000] hover:-translate-y-1 transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-[#800000] hover:border-[#800000] hover:-translate-y-1 transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Navigasi -->
                <div class="lg:col-span-1 lg:pl-10">
                    <h3 class="text-white text-sm font-bold uppercase tracking-wider mb-6">Navigasi</h3>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="{{ url('/') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Beranda</a></li>
                        <li><a href="{{ url('/#artikel') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Edukasi Artikel</a></li>
                        <li><a href="{{ url('/#konsultasi') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Konsultasi AI</a></li>
                        <li><a href="{{ url('/#about') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Bantuan & Layanan -->
                <div class="lg:col-span-1">
                    <h3 class="text-white text-sm font-bold uppercase tracking-wider mb-6">Layanan User</h3>
                    <ul class="space-y-4 text-sm font-medium">
                        @auth
                            @if(auth()->user()->isMahasiswa())
                            <li><a href="{{ route('mahasiswa.profile') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Profil Saya</a></li>
                            <li><a href="{{ route('mahasiswa.feedback.create') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Beri Feedback & Testimoni</a></li>
                            @else
                            <li><a href="{{ route('home') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Masuk ke Dashboard</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Masuk / Registrasi</a></li>
                            <li><a href="{{ route('login') }}" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Pusat Bantuan</a></li>
                        @endauth
                        <li><a href="#" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-red-100/70 hover:text-white hover:translate-x-1 inline-block transition duration-300">Syarat Ketentuan</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="lg:col-span-1">
                    <h3 class="text-white text-sm font-bold uppercase tracking-wider mb-6">Berlangganan</h3>
                    <p class="text-red-100/70 text-sm mb-4 leading-relaxed">
                        Dapatkan notifikasi langsung ke email Anda untuk setiap pembaruan artikel kesehatan terbaru.
                    </p>
                    <form onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan!');" class="relative">
                        <input type="email" placeholder="Alamat Email Anda" required class="w-full bg-white/10 border border-white/20 text-white placeholder-red-200/50 text-sm px-5 py-3.5 rounded-xl focus:outline-none focus:border-red-400 focus:bg-white/15 transition duration-300">
                        <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-gradient-to-r from-[#800000] to-[#b91c1c] text-white px-5 rounded-lg text-sm font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            Kirim
                        </button>
                    </form>
                </div>

            </div>

            <!-- Copyright -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium text-red-100/50">
                <p>&copy; {{ date('Y') }} HealthSelf Platform. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span>Dibuat dengan</span>
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span>untuk Kesehatan Indonesia.</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCROLL NAVBAR --}}
    <script>
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function () {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            navbar.style.top = scrollTop > lastScrollTop ? '-120px' : '0';
            lastScrollTop = scrollTop;
        });

        // Auto-dismiss toast
        const toast = document.getElementById('flash-toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity 0.4s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown')) {
                document.querySelectorAll('.user-dropdown.open').forEach(d => d.classList.remove('open'));
            }
        });
    </script>

    {{-- FLOATING CHAT BUTTON --}}
    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatToggle" class="w-16 h-16 rounded-full bg-[#800000] shadow-2xl flex items-center justify-center text-white text-2xl hover:scale-110 transition duration-300 relative">
            💬
            @guest
                <span style="position:absolute; top:-4px; right:-4px; background:#ef4444; color:white; font-size:0.6rem; font-weight:800; padding:3px 5px; border-radius:8px; white-space:nowrap;">Login</span>
            @endguest
        </button>
    </div>

    {{-- CHATBOX --}}
    <div id="chatBox" style="position:fixed; bottom:88px; right:16px; width:calc(100vw - 32px); max-width:380px; height:72vh; max-height:560px; border-radius:24px; box-shadow:0 20px 60px rgba(0,0,0,0.18); overflow:hidden; z-index:50; opacity:0; transform:translateY(40px); pointer-events:none; transition:opacity 0.4s, transform 0.4s, width 0.35s cubic-bezier(0.4,0,0.2,1), height 0.35s cubic-bezier(0.4,0,0.2,1), max-width 0.35s, max-height 0.35s; display:flex; flex-direction:column; background:white;">

        {{-- HEADER --}}
        <div class="bg-[#800000] text-white px-5 py-4 flex justify-between items-center flex-shrink-0">
            <div>
                <h2 class="text-xl font-bold">HealthSelf AI</h2>
                <p class="text-red-100 text-sm mt-1">
                    @auth Konsultasi kesehatan digital @else Login untuk mulai chat @endauth
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- RESIZE BUTTON --}}
                <button id="resizeChat" title="Perbesar/Perkecil" class="text-white hover:text-red-200 transition duration-200 flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/10">
                    <svg id="resizeIconExpand" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
                    </svg>
                    <svg id="resizeIconShrink" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
                    </svg>
                </button>
                <button id="closeChat" class="text-4xl leading-none hover:rotate-90 transition duration-300">×</button>
            </div>
        </div>

        @guest
        {{-- NOT LOGGED IN STATE --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 bg-[#FFF8F8] text-center">
            <div class="text-5xl mb-4">🔒</div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Chatbot Terkunci</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">Silakan login terlebih dahulu untuk menggunakan fitur konsultasi AI HealthSelf.</p>
            <a href="{{ route('login') }}" class="bg-[#800000] text-white px-8 py-3 rounded-2xl font-semibold hover:bg-[#660000] transition text-sm">
                Masuk Sekarang →
            </a>
        </div>
        @else
        {{-- CHAT AREA --}}
        <div id="chatContent" class="flex-1 overflow-y-auto p-4 bg-[#FFF8F8] space-y-5">
            <div class="flex chat-anim">
                <div class="bg-white max-w-[85%] px-5 py-3 rounded-2xl rounded-bl-sm shadow-md text-[15px] text-gray-700 leading-relaxed">
                    Halo <strong>{{ auth()->user()->name }}</strong> 👋<br>
                    Saya HealthSelf Assistant.<br><br>
                    Silakan tanyakan seputar kesehatan Anda.
                </div>
            </div>
        </div>

        {{-- INPUT AREA --}}
        <div class="p-4 border-t bg-white flex-shrink-0">
            <div class="flex items-end gap-4">
                <textarea id="userInput" placeholder="Tanyakan kesehatan Anda..." rows="2" class="flex-1 border border-gray-300 rounded-2xl px-5 py-3 focus:outline-none focus:border-[#800000] text-gray-700 resize-none overflow-y-auto"></textarea>
                <button id="sendMessage" class="w-14 h-14 rounded-2xl bg-[#800000] text-white text-2xl flex items-center justify-center hover:bg-[#660000] transition shadow-lg">➤</button>
            </div>
        </div>
        @endauth

    </div>

    <script>
        const chatToggle = document.getElementById('chatToggle');
        const chatBox    = document.getElementById('chatBox');
        const closeChat  = document.getElementById('closeChat');

        const isMobile = () => window.innerWidth < 768;

        // Set ukuran chatbox sesuai layar
        function initChatSize() {
            if (isMobile()) {
                chatBox.style.width      = (window.innerWidth - 32) + 'px';
                chatBox.style.maxWidth   = '';
                chatBox.style.height     = Math.min(Math.floor(window.innerHeight * 0.68), 560) + 'px';
                chatBox.style.maxHeight  = '';
                chatBox.style.right      = '16px';
                chatBox.style.bottom     = '88px';
                chatBox.style.borderRadius = '24px';
            } else {
                chatBox.style.width      = '340px';
                chatBox.style.maxWidth   = '380px';
                chatBox.style.height     = '520px';
                chatBox.style.maxHeight  = '560px';
                chatBox.style.right      = '24px';
                chatBox.style.bottom     = '96px';
                chatBox.style.borderRadius = '30px';
            }
        }

        // Inisialisasi ukuran saat halaman dimuat
        initChatSize();
        window.addEventListener('resize', () => { if (!isExpanded) initChatSize(); });

        function openChat() {
            chatBox.style.opacity       = '1';
            chatBox.style.transform     = 'translateY(0)';
            chatBox.style.pointerEvents = 'auto';
            sessionStorage.setItem('chatOpen', 'true');
        }

        function closeThisChat() {
            chatBox.style.opacity       = '0';
            chatBox.style.transform     = 'translateY(40px)';
            chatBox.style.pointerEvents = 'none';
            sessionStorage.setItem('chatOpen', 'false');
        }

        // Kembalikan state chat box jika sebelumnya terbuka
        if (sessionStorage.getItem('chatOpen') === 'true') {
            openChat();
        }

        chatToggle.addEventListener('click', () => {
            const isHidden = chatBox.style.opacity === '0' || chatBox.style.opacity === '';
            if (isHidden) {
                openChat();
            } else {
                closeThisChat();
            }
        });

        closeChat.addEventListener('click', () => {
            closeThisChat();
        });

        // ========== RESIZE CHATBOX ==========
        const resizeBtn     = document.getElementById('resizeChat');
        const iconExpand    = document.getElementById('resizeIconExpand');
        const iconShrink    = document.getElementById('resizeIconShrink');
        let   isExpanded    = false;

        if (resizeBtn) {
            resizeBtn.addEventListener('click', () => {
                isExpanded = !isExpanded;

                if (isExpanded) {
                    if (isMobile()) {
                        // Mobile expanded: hampir full screen
                        const safeTop    = 72;
                        const safeBottom = 84;
                        chatBox.style.width    = (window.innerWidth - 24) + 'px';
                        chatBox.style.height   = (window.innerHeight - safeTop - safeBottom) + 'px';
                        chatBox.style.maxWidth  = '';
                        chatBox.style.maxHeight = '';
                        chatBox.style.right    = '12px';
                        chatBox.style.bottom   = safeBottom + 'px';
                        chatBox.style.borderRadius = '20px';
                    } else {
                        // Desktop expanded
                        const maxH = Math.min(700, window.innerHeight - 120);
                        const maxW = Math.min(600, window.innerWidth - 60);
                        chatBox.style.width    = maxW + 'px';
                        chatBox.style.height   = maxH + 'px';
                        chatBox.style.maxWidth  = '';
                        chatBox.style.maxHeight = '';
                        chatBox.style.right    = '24px';
                        chatBox.style.bottom   = '96px';
                        chatBox.style.borderRadius = '20px';
                    }
                    iconExpand.classList.add('hidden');
                    iconShrink.classList.remove('hidden');
                } else {
                    // Kembali ke ukuran normal
                    initChatSize();
                    iconExpand.classList.remove('hidden');
                    iconShrink.classList.add('hidden');
                }

                // Scroll ke bawah setelah resize
                const cc = document.getElementById('chatContent');
                if (cc) setTimeout(() => cc.scrollTop = cc.scrollHeight, 380);
            });
        }

        // Toggle Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
            });
        }
    </script>

    @auth
    <script>
        const sendButton  = document.getElementById('sendMessage');
        const userInput   = document.getElementById('userInput');
        const chatContent = document.getElementById('chatContent');

        // Kembalikan riwayat chat jika ada
        if (sessionStorage.getItem('chatHistory')) {
            chatContent.innerHTML = sessionStorage.getItem('chatHistory');
            chatContent.scrollTop = chatContent.scrollHeight;
        }

        function saveChatHistory() {
            sessionStorage.setItem('chatHistory', chatContent.innerHTML);
        }

        if (userInput) {
            userInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendButton.click();
                }
            });
        }

        if (sendButton) {
            sendButton.addEventListener('click', async () => {
                const message = userInput.value.trim();
                if (!message) return;

                chatContent.innerHTML += `
                    <div class="flex justify-end chat-anim">
                        <div class="bg-[#800000] text-white px-5 py-3 rounded-2xl rounded-br-sm shadow-md max-w-[85%] text-[15px] leading-relaxed">${message.replace(/\n/g, '<br>')}</div>
                    </div>`;

                userInput.value = '';
                saveChatHistory();

                chatContent.innerHTML += `
                    <div id="loading" class="flex chat-anim">
                        <div class="bg-white px-5 py-3 rounded-2xl rounded-bl-sm shadow-md text-gray-500 text-[15px] flex items-center gap-2">
                            <span class="flex gap-1">
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.2s"></span>
                                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0.4s"></span>
                            </span>
                        </div>
                    </div>`;

                chatContent.scrollTop = chatContent.scrollHeight;

                try {
                    const response = await fetch('{{ route('chat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await response.json();
                    const loading = document.getElementById('loading');
                    if (loading) loading.remove();

                    if (data.error && data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }

                    chatContent.innerHTML += `
                        <div class="flex chat-anim">
                            <div class="bg-white px-5 py-3 rounded-2xl rounded-bl-sm shadow-md max-w-[85%] text-[15px] text-gray-700 leading-relaxed">
                                ${data.reply ? String(data.reply).replace(/\n/g, '<br>') : 'Maaf, terjadi kesalahan.'}
                            </div>
                        </div>`;

                    chatContent.scrollTop = chatContent.scrollHeight;
                    saveChatHistory();

                } catch (e) {
                    const loading = document.getElementById('loading');
                    if (loading) loading.remove();
                    chatContent.innerHTML += `
                        <div class="flex chat-anim">
                            <div class="bg-white px-5 py-3 rounded-2xl rounded-bl-sm shadow-md text-red-500 text-[15px]">Koneksi bermasalah. Coba lagi nanti.</div>
                        </div>`;
                    saveChatHistory();
                }
            });
        }
    </script>
    @endauth

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 600,
            easing: 'ease-out-cubic',
        });
    </script>
</body>

</html>