@extends('layouts.admin')
@section('title', 'Edit Artikel')
@section('page-title', 'Edit Artikel')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card">
        @if($artikel->status === 'rejected')
        <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:12px; padding:14px 18px; margin-bottom:24px;">
            <strong style="color:#92400e;">📝 Artikel ini ditolak:</strong>
            <div style="color:#78350f; font-size:0.875rem; margin-top:6px;">{{ $artikel->catatan_validasi }}</div>
            <div style="color:#92400e; font-size:0.8rem; margin-top:8px;">Setelah diedit, artikel akan kembali ke status "Pending" dan dikirim ulang ke konselor.</div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.artikel.update', $artikel) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Judul Artikel *</label>
                <input type="text" name="judul" value="{{ old('judul', $artikel->judul) }}" class="form-control" required>
                @error('judul') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="id_kategori" class="form-control" required>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $artikel->id_kategori == $k->id ? 'selected' : '' }}>{{ $k->icon }} {{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            @if($artikel->thumbnail)
            <div style="margin-bottom:16px;">
                <label class="form-label">Thumbnail Saat Ini</label>
                <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; max-height:180px; object-fit:cover; border-radius:12px; border:2px solid #e5e7eb;" alt="">
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Ganti Thumbnail (opsional)</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-control">
                @error('thumbnail') <div class="form-error" style="color: red; font-size: 0.85em; margin-top: 5px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Isi Artikel *</label>
                <textarea name="isi_konten" class="form-control" rows="16" required style="resize:vertical; line-height:1.7;">{{ old('isi_konten', $artikel->isi_konten) }}</textarea>
                @error('isi_konten') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">📩 Kirim Ulang ke Konselor</button>
            </div>
        </form>
    </div>
</div>
@endsection
