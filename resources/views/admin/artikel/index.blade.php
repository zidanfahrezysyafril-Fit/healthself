@extends('layouts.admin')
@section('title', 'Manajemen Artikel')
@section('page-title', 'Manajemen Artikel')

@section('content')

<div class="section-header">
    <div>
        <div class="section-title">Daftar Artikel</div>
        <div style="color:#6b7280; font-size:0.875rem; margin-top:4px;">{{ $artikels->total() }} artikel</div>
    </div>
    <a href="{{ route('admin.artikel.create') }}" class="btn btn-primary">✍️ Tulis Artikel</a>
</div>

{{-- FILTER --}}
<div class="card" style="padding:16px 20px; margin-bottom:24px;">
    <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..." class="form-control" style="flex:1; min-width:200px; margin:0;">
        <select name="status" class="form-control" style="width:160px; margin:0;">
            <option value="">Semua Status</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="btn btn-primary">🔍 Filter</button>
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
    @forelse($artikels as $artikel)
    <div class="card" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
        <div style="height:160px; overflow:hidden; position:relative;">
            <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; height:100%; object-fit:cover;" alt="">
            <div style="position:absolute; top:12px; left:12px;">
                <span class="badge badge-{{ $artikel->status }}">{{ ucfirst($artikel->status) }}</span>
            </div>
        </div>
        <div style="padding:20px; flex:1; display:flex; flex-direction:column;">
            <div style="font-size:0.75rem; color:#9ca3af; margin-bottom:8px;">
                {{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }}
                · {{ $artikel->created_at->format('d M Y') }}
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#111; margin:0 0 8px; line-height:1.4;">{{ $artikel->judul }}</h3>
            <p style="color:#6b7280; font-size:0.8rem; line-height:1.5; flex:1;">
                {{ Str::limit(strip_tags($artikel->isi_konten), 100) }}
            </p>
            <div style="margin-top:4px; font-size:0.75rem; color:#9ca3af;">
                oleh {{ $artikel->pembuat->name }}
                @if($artikel->konselor)
                    · diverifikasi {{ $artikel->konselor->name }}
                @endif
            </div>
            @if($artikel->catatan_validasi)
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:8px 12px; margin-top:10px; font-size:0.78rem; color:#991b1b;">
                <strong>Catatan:</strong> {{ $artikel->catatan_validasi }}
            </div>
            @endif
            <div style="display:flex; gap:8px; margin-top:16px; flex-wrap:wrap;">
                <a href="{{ route('admin.artikel.show', $artikel) }}" class="btn btn-secondary btn-sm">👁 Detail</a>
                <a href="{{ route('admin.artikel.edit', $artikel) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.artikel.destroy', $artikel) }}" onsubmit="return confirm('Hapus artikel ini?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; color:#9ca3af; padding:60px; background:white; border-radius:18px;">
        Tidak ada artikel ditemukan.
    </div>
    @endforelse
</div>

<div style="margin-top:24px; display:flex; justify-content:center;">
    {{ $artikels->withQueryString()->links() }}
</div>

@endsection
