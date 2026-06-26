@extends('layouts.app')
@section('page-title', $artikel->judul)
@section('meta-description', Str::limit(strip_tags($artikel->isi_konten), 155))

@section('content')

<article class="min-h-screen pt-8 pb-20" style="background:#FFF8F8;">
    <div class="container mx-auto px-6 max-w-3xl">

        <div style="margin-bottom:20px;">
            <a href="/" style="color:#800000; font-weight:600; font-size:0.875rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                ← Kembali ke Beranda
            </a>
        </div>

        {{-- METADATA --}}
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:20px;">
            <span style="background:#fef2f2; color:#800000; font-size:0.78rem; font-weight:700; padding:5px 12px; border-radius:20px; border:1px solid #fecaca;">
                {{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }}
            </span>
            @if($artikel->tanggal_publish)
                <span style="color:#9ca3af; font-size:0.8rem;">📅 {{ $artikel->tanggal_publish->format('d M Y') }}</span>
            @endif
        </div>

        <h1 style="font-size:2.2rem; font-weight:900; color:#111; line-height:1.25; margin-bottom:24px;">{{ $artikel->judul }}</h1>

        {{-- AUTHOR --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:32px; padding-bottom:24px; border-bottom:2px solid #f3f4f6;">
            <img src="{{ $artikel->pembuat->avatarUrl() }}" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:3px solid #fce7f3;" alt="">
            <div>
                <div style="font-weight:700; color:#111; font-size:0.95rem;">{{ $artikel->pembuat->name }}</div>
                <div style="color:#9ca3af; font-size:0.8rem;">
                    {{ $artikel->pembuat->isKonselor() ? '✅ Konselor HealthSelf' : 'Penulis' }}
                    @if($artikel->konselor && $artikel->konselor->id !== $artikel->pembuat->id)
                        · Diverifikasi oleh {{ $artikel->konselor->name }}
                    @endif
                </div>
            </div>
        </div>

        {{-- THUMBNAIL --}}
        <div style="border-radius:20px; overflow:hidden; margin-bottom:32px; box-shadow:0 12px 40px rgba(0,0,0,0.1);">
            <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; max-height:400px; object-fit:cover;" alt="{{ $artikel->judul }}">
        </div>

        {{-- CONTENT --}}
        <div style="font-size:1rem; line-height:1.9; color:#374151; white-space:pre-wrap; background:white; border-radius:20px; padding:36px; box-shadow:0 4px 20px rgba(0,0,0,0.04); border:1px solid #f3f4f6;">{{ $artikel->isi_konten }}</div>

        {{-- DISCLAIMER --}}
        <div style="background:#fef3c7; border:1.5px solid #fcd34d; border-radius:14px; padding:16px 20px; margin-top:28px; font-size:0.8rem; color:#92400e; line-height:1.6;">
            ⚠️ <strong>Disclaimer:</strong> Informasi dalam artikel ini hanya untuk tujuan edukasi dan tidak menggantikan saran, diagnosis, atau perawatan medis profesional.
        </div>

        {{-- FEEDBACK CTA --}}
        @auth
        @if(auth()->user()->isMahasiswa())
        <div style="background:linear-gradient(135deg,#800000,#570303); border-radius:20px; padding:28px 32px; margin-top:32px; text-align:center;">
            <div style="color:white; font-size:1.2rem; font-weight:800; margin-bottom:8px;">Apakah artikel ini bermanfaat?</div>
            <div style="color:rgba(255,255,255,0.8); font-size:0.875rem; margin-bottom:20px;">Berikan feedback Anda untuk membantu kami meningkatkan kualitas konten.</div>
            <a href="{{ route('mahasiswa.feedback.create') }}" style="background:white; color:#800000; padding:11px 28px; border-radius:50px; font-weight:700; text-decoration:none; font-size:0.875rem; display:inline-block; transition:all 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                ⭐ Beri Feedback
            </a>
        </div>
        @endif
        @else
        <div style="background:#f9fafb; border:2px dashed #e5e7eb; border-radius:20px; padding:28px 32px; margin-top:32px; text-align:center;">
            <div style="font-weight:700; color:#374151; margin-bottom:8px;">💬 Ingin konsultasi tentang topik ini?</div>
            <div style="color:#6b7280; font-size:0.875rem; margin-bottom:16px;">Login untuk menggunakan chatbot AI HealthSelf secara gratis.</div>
            <a href="{{ route('login') }}" style="background:#800000; color:white; padding:10px 24px; border-radius:50px; font-weight:700; text-decoration:none; font-size:0.875rem; display:inline-block;">
                Masuk Sekarang →
            </a>
        </div>
        @endauth

    </div>
</article>

@endsection
