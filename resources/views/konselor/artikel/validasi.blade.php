@extends('layouts.konselor')
@section('title', 'Validasi Artikel')
@section('page-title', 'Validasi Artikel')

@section('content')
<div style="max-width:900px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('konselor.artikel.index', ['tab' => 'validasi']) }}" class="btn btn-secondary btn-sm">← Kembali ke Daftar Validasi</a>
    </div>

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:24px;">

        {{-- ARTIKEL PREVIEW --}}
        <div class="card">
            <div style="display:flex; gap:10px; align-items:center; margin-bottom:16px;">
                <span class="badge badge-pending">Menunggu Validasi</span>
                <span style="color:#9ca3af; font-size:0.8rem;">{{ $artikel->kategori->icon }} {{ $artikel->kategori->nama_kategori }}</span>
            </div>

            @if($artikel->thumbnail)
                <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; max-height:280px; object-fit:cover; border-radius:14px; margin-bottom:20px;" alt="">
            @endif

            <h1 style="font-size:1.6rem; font-weight:800; color:#111; margin:0 0 14px; line-height:1.3;">{{ $artikel->judul }}</h1>

            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #f3f4f6;">
                <img src="{{ $artikel->pembuat->avatarUrl() }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" alt="">
                <div>
                    <div style="font-weight:700; font-size:0.875rem; color:#111;">{{ $artikel->pembuat->name }}</div>
                    <div style="font-size:0.75rem; color:#9ca3af;">Dikirim {{ $artikel->created_at->diffForHumans() }}</div>
                </div>
            </div>

            <div style="font-size:0.9rem; line-height:1.85; color:#374151; white-space:pre-wrap;">{{ $artikel->isi_konten }}</div>
        </div>

        {{-- VALIDASI PANEL --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- APPROVE --}}
            <div class="card" style="border:2px solid #86efac;">
                <div style="font-size:1rem; font-weight:800; color:#065f46; margin-bottom:12px;">✅ Setujui Artikel</div>
                <p style="color:#374151; font-size:0.875rem; line-height:1.6; margin:0 0 16px;">Artikel akan langsung dipublikasikan di landing page dan dapat dibaca oleh semua pengguna.</p>
                <form method="POST" action="{{ route('konselor.artikel.approve', $artikel) }}" id="approve-form">
                    @csrf
                    <button type="button" class="btn btn-success" style="width:100%; justify-content:center;" onclick="confirmApprove()">
                        ✅ Setujui & Publikasikan
                    </button>
                </form>
            </div>

            {{-- REJECT --}}
            <div class="card" style="border:2px solid #fca5a5;">
                <div style="font-size:1rem; font-weight:800; color:#991b1b; margin-bottom:12px;">🚫 Tolak Artikel</div>
                <p style="color:#374151; font-size:0.875rem; line-height:1.6; margin:0 0 16px;">Berikan catatan yang jelas agar admin dapat memperbaiki artikel sesuai standar.</p>
                <form method="POST" action="{{ route('konselor.artikel.reject', $artikel) }}" id="reject-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Catatan Penolakan *</label>
                        <textarea name="catatan_validasi" class="form-control {{ $errors->has('catatan_validasi') ? 'error' : '' }}" rows="5"
                            placeholder="Contoh: Informasi medis pada paragraf 3 belum sesuai dengan bukti ilmiah terkini. Mohon tambahkan referensi dari jurnal..." required style="resize:vertical;">{{ old('catatan_validasi') }}</textarea>
                        @error('catatan_validasi') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <button type="button" class="btn btn-danger" style="width:100%; justify-content:center;" onclick="confirmReject()">
                        🚫 Tolak dengan Catatan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmApprove() {
        Swal.fire({
            title: 'Setujui Artikel?',
            text: "Artikel ini akan dipublikasikan dan bisa dibaca oleh semua pengguna.",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Publikasikan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form').submit();
            }
        });
    }

    function confirmReject() {
        const textarea = document.querySelector('textarea[name="catatan_validasi"]');
        if (!textarea.value.trim()) {
            Swal.fire({
                title: 'Oops...',
                text: 'Catatan penolakan wajib diisi!',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            }).then(() => {
                textarea.focus();
            });
            return;
        }

        Swal.fire({
            title: 'Tolak Artikel?',
            text: "Artikel akan dikembalikan ke penulis dengan catatan Anda.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject-form').submit();
            }
        });
    }
</script>
@endsection
