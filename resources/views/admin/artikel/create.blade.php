@extends('layouts.admin')
@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel Baru')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:20px; display:flex; align-items:center; gap:12px;">
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <div style="background:#e0f2fe; border:1px solid #bae6fd; color:#0369a1; padding:8px 14px; border-radius:8px; font-size:0.8rem;">
            ℹ️ <strong>Informasi Alur:</strong> Setelah Anda mengisi form di bawah dan menekan tombol submit, artikel ini akan dikirim ke konselor untuk diverifikasi sebelum dipublikasikan.
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data" onsubmit="document.getElementById('btn-submit').disabled=true; document.getElementById('btn-submit').innerHTML='Memproses...';">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Artikel *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-control {{ $errors->has('judul') ? 'error' : '' }}" placeholder="Tulis judul yang menarik..." required>
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
                <div id="thumbnail-preview" style="display:none; margin-top:12px;">
                    <img id="preview-img" style="width:100%; max-height:200px; object-fit:cover; border-radius:12px; border:2px solid #e5e7eb;" alt="">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Artikel *</label>
                <textarea name="isi_konten" id="isi_konten" class="form-control {{ $errors->has('isi_konten') ? 'error' : '' }}" rows="16" placeholder="Tulis konten artikel di sini..." required style="resize:vertical; line-height:1.7;">{{ old('isi_konten') }}</textarea>
                @error('isi_konten') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" id="btn-submit" class="btn btn-primary">📩 Kirim ke Konselor</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('thumbnail-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(r) {
                document.getElementById('preview-img').src = r.target.result;
                document.getElementById('thumbnail-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
