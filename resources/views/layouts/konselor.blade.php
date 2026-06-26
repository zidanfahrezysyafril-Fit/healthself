<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – Konselor HealthSelf</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: #f1f5f9; color: #334155; min-height: 100dvh; }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 280px; height: 100%; min-height: 100dvh;
            background: linear-gradient(180deg, #0a1128 0%, #16213e 100%); /* Deep Blue */
            background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent 300px), linear-gradient(180deg, #0a1128 0%, #16213e 100%);
            display: flex; flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 40px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 28px 24px 20px;
            text-align: center;
        }
        .sidebar-brand h1 {
            font-size: 1.7rem; font-weight: 800; margin: 0;
            background: linear-gradient(135deg, #ffffff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }
        .sidebar-brand p {
            color: #94a3b8; font-size: 0.75rem; margin: 4px 0 0;
            text-transform: uppercase; letter-spacing: 2px; font-weight: 700;
        }
        .sidebar-nav {
            flex: 1; padding: 20px 16px; overflow-y: auto;
            -ms-overflow-style: none; scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }
        .nav-label {
            color: #64748b; font-size: 0.7rem; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 16px 12px 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 14px; color: #cbd5e1; border-radius: 12px;
            text-decoration: none; font-size: 0.9rem; font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 4px;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1); color: #fff;
            transform: translateX(6px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .nav-icon { 
            width: 36px; height: 36px; border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.1rem; background: rgba(255,255,255,0.05); 
            transition: all 0.3s; color: #94a3b8;
        }
        .nav-item.active .nav-icon { 
            background: white; 
            color: #0f3460; box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2); 
        }
        .nav-item:hover .nav-icon { color: white; background: rgba(255,255,255,0.15); }
        .nav-badge {
            margin-left: auto; background: #ef4444; color: white;
            font-size: 0.7rem; font-weight: 800; padding: 3px 8px;
            border-radius: 20px; box-shadow: 0 2px 10px rgba(239,68,68,0.4);
        }

        .sidebar-user {
            padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: center; gap: 14px; background: rgba(0,0,0,0.2);
        }
        .user-avatar {
            width: 42px; height: 42px; border-radius: 50%; object-fit: cover; 
            border: 2px solid #334155;
        }
        .user-info { flex: 1; }
        .user-name { color: #f8fafc; font-weight: 700; font-size: 0.85rem; }
        .user-role { color: #94a3b8; font-size: 0.75rem; font-weight: 500; }
        .btn-logout {
            color: #64748b; font-size: 1.2rem; cursor: pointer; background: none; border: none;
            transition: all 0.3s; padding: 6px; border-radius: 10px;
        }
        .btn-logout:hover { color: #fca5a5; background: rgba(239,68,68,0.1); }

        /* MAIN */
        .main-content {
            margin-left: 280px; min-height: 100vh; display: flex; flex-direction: column;
        }
        .topbar {
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.6);
            padding: 0 40px; height: 80px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50; box-shadow: 0 4px 30px rgba(0,0,0,0.03);
        }
        .topbar-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-badge {
            background: #e0e7ff; color: #3730a3; font-size: 0.75rem; font-weight: 800;
            padding: 6px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px;
        }
        .topbar-date { color: #64748b; font-weight: 600; font-size: 0.85rem; }

        @keyframes fadeInPage { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .page-body { padding: 40px; flex: 1; animation: fadeInPage 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }

        /* CARDS */
        .stat-card {
            background: white; border-radius: 24px; padding: 24px;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.03);
            border: 1px solid rgba(255,255,255,0.9);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-end;
            min-height: 160px;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
            opacity: 0.8; transition: opacity 0.4s;
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
        }
        .stat-card:hover::before { opacity: 1; }
        
        .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            position: absolute; top: 24px; right: 24px;
            background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
            color: white;
            box-shadow: 0 8px 20px -6px var(--card-color-1);
        }
        .stat-value { font-size: 2.5rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -1px; margin-bottom: 8px;}
        .stat-label { color: #64748b; font-size: 0.95rem; font-weight: 600; }

        /* DATA CARDS */
        .card { 
            background: white; border-radius: 28px; padding: 32px; 
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.03); 
            border: 1px solid rgba(255,255,255,0.9); 
        }
        
        /* TABLE */
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .data-table th {
            text-align: left; padding: 16px; font-size: 0.75rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px; color: #64748b; 
            border-bottom: 2px solid #f1f5f9; background: #f8fafc;
        }
        .data-table th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .data-table th:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
        .data-table td {
            padding: 16px; border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem; color: #334155; font-weight: 500;
            transition: background 0.2s;
        }
        .data-table tr:hover td { background: #f8fafc; }
        .data-table tr:last-child td { border-bottom: none; }

        /* BADGE */
        .badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; }
        .badge-admin    { background: #fef3c7; color: #d97706; }
        .badge-konselor { background: #e0e7ff; color: #4f46e5; }
        .badge-mahasiswa{ background: #d1fae5; color: #059669; }
        .badge-published{ background: #d1fae5; color: #059669; }
        .badge-pending  { background: #fef3c7; color: #d97706; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }
        .badge-draft    { background: #f1f5f9; color: #64748b; }
        .badge-flagged  { background: #fef2f2; color: #dc2626; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-size: 0.9rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: inherit; }
        .btn-primary { background: linear-gradient(135deg, #0f3460, #16213e); color: white; box-shadow: 0 4px 15px rgba(15, 52, 96, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15, 52, 96, 0.4); }
        .btn-secondary { background: #f1f5f9; color: #475569; }
        .btn-secondary:hover { background: #e2e8f0; color: #0f172a; transform: translateY(-2px); }
        .btn-sm { padding: 6px 14px; font-size: 0.8rem; border-radius: 10px; }
        .btn-danger { background: #fef2f2; color: #991b1b; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-success { background: #f0fdf4; color: #166534; }
        .btn-success:hover { background: #dcfce7; }

        /* FORM */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 700; font-size: 0.85rem; color: #374151; margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 12px 16px; border: 1.5px solid #e5e7eb; border-radius: 12px;
            font-size: 0.95rem; font-family: inherit; transition: all 0.3s; outline: none; background: #fafafa;
        }
        .form-control:focus { border-color: #0f3460; background: #fff; box-shadow: 0 0 0 4px rgba(15,52,96,0.08); transform: translateY(-1px); }
        .form-control.error { border-color: #ef4444; }
        .form-error { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }

        /* ALERT */
        .alert { padding: 16px 20px; border-radius: 16px; font-size: 0.9rem; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; animation: slideDown 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .alert-success-box { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error-box   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        /* SECTION HEADER */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .section-title { font-size: 1.4rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; letter-spacing: -0.5px; }

        /* CHAT BUBBLE */
        .chat-bubble-user { background: #0f3460; color: white; border-radius: 18px 18px 4px 18px; padding: 10px 16px; max-width: 80%; font-size: 0.875rem; }
        .chat-bubble-bot  { background: #f3f4f6; color: #374151; border-radius: 18px 18px 18px 4px; padding: 10px 16px; max-width: 80%; font-size: 0.875rem; }
        .chat-row { display: flex; gap: 10px; margin-bottom: 12px; }
        .chat-row.user { flex-direction: row-reverse; }
        .chat-time { font-size: 0.7rem; color: #9ca3af; margin-top: 4px; text-align: right; }
        .flag-row { background: #fef2f2 !important; }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* MOBILE SIDEBAR OVERLAY */
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90;
            opacity: 0; visibility: hidden; transition: all 0.3s;
        }
        .sidebar-overlay.show { opacity: 1; visibility: visible; }
        
        .mobile-toggle { display: none; background: none; border: none; cursor: pointer; color: #0f172a; padding: 5px; margin-right: 15px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; padding: 2px; margin-right: 10px; }
            .mobile-toggle svg { width: 24px !important; height: 24px !important; }
            .topbar { padding: 0 16px; height: 60px; }
            .topbar-badge, .topbar-date { display: none; }
            .topbar-title { font-size: 1.1rem; }
            .page-body { padding: 16px; }
            .card { overflow-x: auto; padding: 16px; border-radius: 20px; }
            .stat-card { padding: 16px; min-height: 120px; border-radius: 20px; }
            .stat-value { font-size: 1.8rem; }
            .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; top: 16px; right: 16px; border-radius: 10px; }
            .section-title { font-size: 1.1rem; margin-bottom: 20px; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div style="display:flex; justify-content:center; margin-bottom:18px;">
            <div style="background: white; padding: 6px; border-radius: 22px; box-shadow: 0 8px 30px rgba(0,0,0,0.4); display: inline-flex;">
                <img src="{{ asset('images/logo-konselor.jpg') }}" alt="Logo" style="width: 58px; height: 58px; border-radius: 16px; object-fit:cover;">
            </div>
        </div>
        <h1 style="font-size: 1.9rem; font-weight: 800; letter-spacing: -0.5px; line-height: 1; text-shadow: 0 2px 10px rgba(0,0,0,0.3); color: #ffffff;">HealthSelf</h1>
        <p style="font-size: 0.7rem; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; margin-top: 8px; color: #e2e8f0; line-height: 1; opacity: 0.9;">Mind & Body Care</p>
        <div style="margin-top: 18px; font-size: 0.75rem; background: rgba(255,255,255,0.15); display: inline-block; padding: 6px 16px; border-radius: 10px; color: #ffffff; font-weight: 800; letter-spacing: 0.5px; border: 1px solid rgba(255,255,255,0.1);">🩺 Panel Konselor</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('konselor.dashboard') }}" class="nav-item {{ request()->routeIs('konselor.dashboard') ? 'active' : '' }}">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></div> Dashboard
        </a>

        <div class="nav-label">Artikel</div>
        <a href="{{ route('konselor.artikel.index') }}" class="nav-item {{ request()->routeIs('konselor.artikel.*') ? 'active' : '' }}">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div> Kelola Artikel
            @php $pendingCount = \App\Models\Artikel::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('konselor.artikel.create') }}" class="nav-item">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div> Tulis Artikel
        </a>

        <div class="nav-label">Monitor</div>
        <a href="{{ route('konselor.chat.index') }}" class="nav-item {{ request()->routeIs('konselor.chat.index') ? 'active' : '' }}">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></div> Monitor Chat
        </a>
        <a href="{{ route('konselor.chat.flagged') }}" class="nav-item {{ request()->routeIs('konselor.chat.flagged') ? 'active' : '' }}">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg></div> Chat Berbahaya
            @php $flaggedCount = \App\Models\RiwayatChat::where('is_flagged', true)->count(); @endphp
            @if($flaggedCount > 0)
                <span class="nav-badge">{{ $flaggedCount }}</span>
            @endif
        </a>
        <a href="{{ route('konselor.feedback.index') }}" class="nav-item {{ request()->routeIs('konselor.feedback.*') ? 'active' : '' }}">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg></div> Feedback
        </a>

        <div class="nav-label">Lainnya</div>
        <a href="{{ route('home') }}" class="nav-item">
            <div class="nav-icon"><svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg></div> Lihat Website
        </a>
    </nav>

    <div class="sidebar-user">
        <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar" class="user-avatar">
        <div class="user-info">
            <div class="user-name">{{ Str::limit(auth()->user()->name, 18) }}</div>
            <div class="user-role">Konselor</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout" title="Keluar">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-content">
    <div class="topbar">
        <div class="flex items-center">
            <button class="mobile-toggle" id="mobileToggleBtn">
                <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <span class="topbar-badge">Konselor Access</span>
            <span class="topbar-date">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="page-body">
        @if(session('success'))
            <div class="alert alert-success-box">
                <svg style="width:24px;height:24px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error-box">
                <svg style="width:24px;height:24px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    const mobileToggleBtn = document.getElementById('mobileToggleBtn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if(mobileToggleBtn) {
        mobileToggleBtn.addEventListener('click', () => {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
        });
    }

    if(sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }
</script>

@yield('scripts')
</body>
</html>
