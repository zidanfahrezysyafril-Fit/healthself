@extends('layouts.konselor')
@section('title', 'Kelola Artikel')
@section('page-title', 'Kelola Artikel')

@section('content')

<div class="section-header">
    <div>
        <div class="section-title">Artikel</div>
        @if($pendingCount > 0)
            <div style="background:#fef3c7; border:1px solid #fcd34d; color:#92400e; padding:6px 12px; border-radius:8px; font-size:0.8rem; margin-top:8px; display:inline-block;">
                ⏳ {{ $pendingCount }} artikel menunggu validasi Anda
            </div>
        @endif
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('konselor.artikel.index', ['tab' => 'validasi']) }}" class="btn btn-secondary {{ request('tab') === 'validasi' ? 'btn-primary' : '' }}">⏳ Perlu Validasi {{ $pendingCount > 0 ? "($pendingCount)" : '' }}</a>
        <a href="{{ route('konselor.artikel.create') }}" class="btn btn-primary">✍️ Tulis Artikel</a>
    </div>
</div>

<div class="card" style="padding:16px 20px; margin-bottom:24px;">
    <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="form-control" style="flex:1; min-width:200px; margin:0;">
        <select name="status" class="form-control" style="width:150px; margin:0;">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status')==='published'?'selected':'' }}>Published</option>
            <option value="pending"   {{ request('status')==='pending'?'selected':'' }}>Pending</option>
            <option value="rejected"  {{ request('status')==='rejected'?'selected':'' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn btn-primary">🔍</button>
        <a href="{{ route('konselor.artikel.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px;">
    @forelse($artikels as $artikel)
    <div class="card" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
        <div style="height:150px; overflow:hidden; position:relative;">
            <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; height:100%; object-fit:cover;" alt="">
            <div style="position:absolute; top:12px; left:12px; display:flex; gap:6px;">
                <span class="badge badge-{{ $artikel->status }}">{{ ucfirst($artikel->status) }}</span>
                @if($artikel->id_user === auth()->id())
                    <span style="background:rgba(15,52,96,0.85); color:white; font-size:0.7rem; font-weight:700; padding:4px 8px; border-radius:12px;">Saya</span>
                @else
                    <span style="background:rgba(0,0,0,0.6); color:white; font-size:0.7rem; padding:4px 8px; border-radius:12px;">Admin</span>
                @endif
            </div>
        </div>
        <div style="padding:16px; flex:1; display:flex; flex-direction:column;">
            <div style="font-size:0.73rem; color:#9ca3af; margin-bottom:6px;">{{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }} · {{ $artikel->created_at->format('d M Y') }}</div>
            <h3 style="font-size:0.95rem; font-weight:700; color:#111; margin:0 0 8px; line-height:1.4;">{{ $artikel->judul }}</h3>
            <p style="color:#6b7280; font-size:0.78rem; line-height:1.5; flex:1;">{{ Str::limit(strip_tags($artikel->isi_konten), 90) }}</p>
            <div style="font-size:0.72rem; color:#9ca3af; margin-bottom:12px;">oleh {{ $artikel->pembuat->name }}</div>
            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                @if($artikel->status === 'pending' && $artikel->id_user !== auth()->id())
                    <a href="{{ route('konselor.artikel.validasi', $artikel) }}" class="btn btn-success btn-sm">✅ Validasi</a>
                @endif
                <form method="POST" action="{{ route('konselor.artikel.destroy', $artikel) }}" onsubmit="return confirm('Hapus artikel ini?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; color:#9ca3af; padding:60px; background:white; border-radius:18px;">Tidak ada artikel.</div>
    @endforelse
</div>
<div style="margin-top:24px; display:flex; justify-content:center;">{{ $artikels->withQueryString()->links() }}</div>

@endsection
