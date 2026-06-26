@extends('layouts.admin')
@section('title', $artikel->judul)
@section('page-title', 'Detail Artikel')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:20px; display:flex; gap:12px;">
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-primary btn-sm">✏️ Edit Artikel</a>
    </div>

    <div class="card">
        {{-- HEADER --}}
        <div style="display:flex; gap:12px; align-items:center; margin-bottom:20px;">
            <span class="badge badge-{{ $artikel->status }}">{{ ucfirst($artikel->status) }}</span>
            <span style="color:#6b7280; font-size:0.8rem;">{{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }}</span>
            <span style="color:#9ca3af; font-size:0.8rem;">· {{ $artikel->created_at->format('d M Y, H:i') }}</span>
        </div>

        <h1 style="font-size:1.8rem; font-weight:800; color:#111; margin:0 0 16px; line-height:1.3;">{{ $artikel->judul }}</h1>

        <div style="display:flex; gap:16px; margin-bottom:24px; padding-bottom:24px; border-bottom:1px solid #f3f4f6;">
            <div style="display:flex; align-items:center; gap:8px;">
                <img src="{{ $artikel->pembuat->avatarUrl() }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;" alt="">
                <div>
                    <div style="font-size:0.8rem; font-weight:700; color:#374151;">{{ $artikel->pembuat->name }}</div>
                    <div style="font-size:0.72rem; color:#9ca3af;">Penulis</div>
                </div>
            </div>
            @if($artikel->konselor)
            <div style="display:flex; align-items:center; gap:8px;">
                <img src="{{ $artikel->konselor->avatarUrl() }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;" alt="">
                <div>
                    <div style="font-size:0.8rem; font-weight:700; color:#374151;">{{ $artikel->konselor->name }}</div>
                    <div style="font-size:0.72rem; color:#9ca3af;">Validator</div>
                </div>
            </div>
            @endif
        </div>

        @if($artikel->catatan_validasi)
        <div style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:12px; padding:16px 20px; margin-bottom:24px;">
            <div style="font-weight:700; color:#991b1b; margin-bottom:6px;">🚫 Catatan Penolakan dari Konselor:</div>
            <div style="color:#7f1d1d; font-size:0.875rem; line-height:1.6;">{{ $artikel->catatan_validasi }}</div>
        </div>
        @endif

        @if($artikel->thumbnail)
        <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; max-height:360px; object-fit:cover; border-radius:16px; margin-bottom:24px;" alt="">
        @endif

        <div style="font-size:0.95rem; line-height:1.8; color:#374151; white-space:pre-wrap;">{{ $artikel->isi_konten }}</div>

        @if($artikel->tanggal_publish)
        <div style="margin-top:24px; padding-top:20px; border-top:1px solid #f3f4f6; color:#9ca3af; font-size:0.8rem;">
            📅 Dipublikasikan: {{ $artikel->tanggal_publish->format('d M Y, H:i') }}
        </div>
        @endif
    </div>
</div>
@endsection
