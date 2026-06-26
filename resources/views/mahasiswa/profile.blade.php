@extends('layouts.app')
@section('content')

<section class="relative pt-24 pb-24 min-h-screen" style="background:linear-gradient(135deg,#FFF8F8 0%,#fbe2e2 100%);">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
    <div class="container mx-auto px-6 max-w-4xl relative z-10" data-aos="fade-up">

        {{-- PROFILE CARD --}}
        <div style="background:rgba(255, 255, 255, 0.9); backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); border-radius:32px; padding:40px; box-shadow:0 20px 40px -10px rgba(128,0,0,0.1); margin-bottom:40px; display:flex; gap:36px; align-items:center; flex-wrap:wrap; position:relative; overflow:hidden;">
            <div style="position:absolute; top:-50px; right:-50px; width:150px; height:150px; background:#800000; border-radius:50%; filter:blur(60px); opacity:0.1;"></div>
            <img src="{{ $user->avatarUrl() }}" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:6px solid #fff; box-shadow:0 8px 20px rgba(128,0,0,0.15);" alt="">
            <div style="flex:1;">
                <h1 style="font-size:1.8rem; font-weight:800; color:#111; margin:0 0 6px;">{{ $user->name }}</h1>
                <div style="color:#6b7280; font-size:0.9rem; line-height:2;">
                    <div>📧 {{ $user->email }}</div>
                    @if($user->prodi) <div>🎓 {{ $user->prodi }}</div> @endif
                    @if($user->nim_nip) <div>🆔 {{ $user->nim_nip }}</div> @endif
                    <div>📅 Bergabung {{ $user->created_at->format('d M Y') }}</div>
                </div>
                <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('mahasiswa.feedback.create') }}" style="background:linear-gradient(135deg, #800000, #570303); color:white; padding:10px 24px; border-radius:14px; font-weight:700; font-size:0.9rem; text-decoration:none; box-shadow:0 8px 20px rgba(128,0,0,0.2); transition:all 0.3s; display:inline-flex; align-items:center; gap:8px;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Beri Feedback
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:#f1f5f9; color:#475569; border:none; padding:10px 24px; border-radius:14px; font-weight:700; font-size:0.9rem; cursor:pointer; font-family:inherit; transition:all 0.3s; display:inline-flex; align-items:center; gap:8px;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#0f172a'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569'; this.style.transform='translateY(0)';">
                            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- LAYANAN CEPAT --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:40px;">
            <a href="#" onclick="document.getElementById('chatToggle').click(); return false;" style="background:white; border-radius:24px; padding:24px; text-align:center; text-decoration:none; box-shadow:0 10px 30px -10px rgba(0,0,0,0.05); border:1px solid rgba(255,255,255,0.8); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px -10px rgba(128,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px -10px rgba(0,0,0,0.05)';">
                <div style="font-size:2.5rem; margin-bottom:12px; filter:drop-shadow(0 4px 6px rgba(0,0,0,0.1));">💬</div>
                <div style="font-weight:800; color:#111; font-size:1.1rem; margin-bottom:6px;">Mulai Konsultasi</div>
                <div style="color:#64748b; font-size:0.8rem; line-height:1.5;">Tanyakan keluhan kesehatanmu ke AI atau Konselor.</div>
            </a>
            <a href="{{ url('/#artikel') }}" style="background:white; border-radius:24px; padding:24px; text-align:center; text-decoration:none; box-shadow:0 10px 30px -10px rgba(0,0,0,0.05); border:1px solid rgba(255,255,255,0.8); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px -10px rgba(128,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px -10px rgba(0,0,0,0.05)';">
                <div style="font-size:2.5rem; margin-bottom:12px; filter:drop-shadow(0 4px 6px rgba(0,0,0,0.1));">📰</div>
                <div style="font-weight:800; color:#111; font-size:1.1rem; margin-bottom:6px;">Baca Artikel</div>
                <div style="color:#64748b; font-size:0.8rem; line-height:1.5;">Edukasi kesehatan terpercaya dari ahli medis.</div>
            </a>
            <div style="background:linear-gradient(135deg, #800000, #570303); border-radius:24px; padding:24px; text-align:center; color:white; box-shadow:0 15px 30px -10px rgba(128,0,0,0.4); border:1px solid rgba(255,255,255,0.1); display:flex; flex-direction:column; justify-content:center; position:relative; overflow:hidden;">
                <div style="position:absolute; top:-20px; right:-20px; font-size:6rem; opacity:0.05; transform:rotate(15deg);">✨</div>
                <div style="font-size:2rem; margin-bottom:12px; position:relative; z-index:10;">✨</div>
                <div style="font-weight:800; font-size:1rem; margin-bottom:6px; line-height:1.4; position:relative; z-index:10;">"Kesehatan adalah kekayaan yang paling berharga."</div>
                <div style="color:#fca5a5; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; position:relative; z-index:10;">- Kutipan Hari Ini</div>
            </div>
        </div>

        {{-- ARTIKEL TERBARU --}}
        @php
            $rekomendasiArtikel = \App\Models\Artikel::where('status', 'published')->latest()->take(2)->get();
        @endphp
        @if($rekomendasiArtikel->count() > 0)
        <div style="margin-bottom:40px;">
            <h2 style="font-size:1.3rem; font-weight:800; color:#111; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                <span style="color:#800000;">🔥</span> Bacaan Spesial Untukmu
            </h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px;">
                @foreach($rekomendasiArtikel as $art)
                <a href="{{ route('artikel.show', $art->slug) }}" style="background:white; border-radius:20px; overflow:hidden; text-decoration:none; display:flex; flex-direction:column; box-shadow:0 10px 30px -10px rgba(0,0,0,0.05); border:1px solid rgba(255,255,255,0.8); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 40px -10px rgba(128,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px -10px rgba(0,0,0,0.05)';">
                    <img src="{{ $art->thumbnailUrl() }}" style="width:100%; height:160px; object-fit:cover;" alt="">
                    <div style="padding:20px; flex:1; display:flex; flex-direction:column;">
                        <div style="margin-bottom:10px;">
                            <span style="background:#fef2f2; color:#991b1b; padding:4px 10px; border-radius:8px; font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.5px;">{{ $art->kategori->nama_kategori ?? 'Kesehatan' }}</span>
                        </div>
                        <h3 style="font-size:1.1rem; font-weight:800; color:#111; margin:0 0 8px; line-height:1.4;">{{ Str::limit($art->judul, 45) }}</h3>
                        <p style="font-size:0.85rem; color:#64748b; margin:0 0 16px; line-height:1.6; flex:1;">{{ Str::limit(strip_tags($art->konten), 70) }}</p>
                        <div style="font-size:0.8rem; font-weight:700; color:#800000; display:flex; align-items:center; gap:6px;">
                            Baca Selengkapnya <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- KONSELOR COMMENTS --}}
        @if($konselorComments->isNotEmpty())
        <div style="background:#fffbeb; border:2px solid #fcd34d; border-radius:20px; padding:24px; margin-bottom:28px;">
            <div style="font-size:1.1rem; font-weight:800; color:#92400e; margin-bottom:16px;">📩 Pesan dari Konselor ({{ $konselorComments->count() }})</div>
            @foreach($konselorComments as $kom)
            <div style="background:white; border-radius:14px; padding:16px 20px; margin-bottom:12px; border-left:4px solid #f59e0b;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <img src="{{ $kom->konselor->avatarUrl() }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover;" alt="">
                    <div>
                        <div style="font-weight:700; font-size:0.875rem; color:#111;">{{ $kom->konselor->name }}</div>
                        <div style="font-size:0.72rem; color:#9ca3af;">{{ $kom->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div style="color:#374151; font-size:0.875rem; line-height:1.7;">{{ $kom->komentar }}</div>
                @if($kom->riwayat)
                <div style="margin-top:10px; background:#f9fafb; border-radius:8px; padding:10px 14px; font-size:0.78rem; color:#6b7280; border-left:3px solid #d1d5db;">
                    <strong>Tentang pesan:</strong> "{{ Str::limit($kom->riwayat->pesan_user, 80) }}"
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif


    </div>
</section>

@endsection
