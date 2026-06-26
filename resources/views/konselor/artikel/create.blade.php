@extends('layouts.konselor')
@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:20px; display:flex; align-items:center; gap:12px;">
        <a href="{{ route('konselor.artikel.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <div style="background:#d1fae5; border:1px solid #86efac; color:#065f46; padding:8px 14px; border-radius:8px; font-size:0.8rem;">
            ✅ Artikel yang Anda tulis akan langsung dipublikasikan tanpa perlu verifikasi.
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('konselor.artikel.store') }}" enctype="multipart/form-data" onsubmit="document.getElementById('btn-submit').disabled=true; document.getElementById('btn-submit').innerHTML='Memproses...';">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Judul Artikel *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-control {{ $errors->has('judul') ? 'error' : '' }}" required>
                @error('judul') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="id_kategori" class="form-control {{ $errors->has('id_kategori') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('id_kategori') == $k->id ? 'selected' : '' }}>{{ $k->icon }} {{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Thumbnail / Gambar</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-control" id="thumbnail-input">
                @error('thumbnail') <div class="form-error" style="color: red; font-size: 0.85em; margin-top: 5px;">{{ $message }}</div> @enderror
                <div id="thumbnail-preview" style="display:none; margin-top:10px;">
                    <img id="preview-img" style="max-width:100%; max-height:200px; border-radius:8px; object-fit:cover;" alt="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Artikel *</label>
                <textarea name="isi_konten" id="isi_konten" class="form-control {{ $errors->has('isi_konten') ? 'error' : '' }}" rows="15" required style="resize:vertical;">{{ old('isi_konten') }}</textarea>
                @error('isi_konten') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="{{ route('konselor.artikel.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="btn-submit" class="btn btn-primary">Publikasikan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('thumb-in').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = r => {
                document.getElementById('thumb-img').src = r.target.result;
                document.getElementById('thumb-prev').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
