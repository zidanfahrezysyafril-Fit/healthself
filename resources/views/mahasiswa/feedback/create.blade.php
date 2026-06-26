@extends('layouts.app')
@section('content')

<section style="min-height:100vh; background:linear-gradient(135deg,#FFF8F8,#fbe2e2); padding:80px 0 60px;">
    <div class="container mx-auto px-6 max-w-2xl">

        @if($hadFeedback)
        <div style="background:#fffbeb; border:2px solid #fcd34d; border-radius:20px; padding:24px; text-align:center; margin-bottom:28px;">
            <div style="font-size:2rem; margin-bottom:10px;">⭐</div>
            <div style="font-weight:700; color:#92400e; font-size:1.1rem;">Anda sudah memberikan feedback sebelumnya!</div>
            <div style="color:#78350f; font-size:0.875rem; margin-top:6px;">Terima kasih atas masukan Anda yang berharga.</div>
            <a href="{{ route('home') }}" style="display:inline-block; margin-top:16px; background:#800000; color:white; padding:10px 24px; border-radius:10px; font-weight:600; text-decoration:none; font-size:0.875rem;">← Kembali ke Beranda</a>
        </div>
        @endif

        <div style="background:white; border-radius:24px; padding:40px; box-shadow:0 12px 40px rgba(0,0,0,0.06);">
            <div style="text-align:center; margin-bottom:32px;">
                <div style="font-size:2.5rem; margin-bottom:10px;">❤️</div>
                <h1 style="font-size:1.8rem; font-weight:800; color:#111; margin:0 0 8px;">Beri Feedback</h1>
                <p style="color:#6b7280; font-size:0.9rem;">Pendapat Anda sangat berarti untuk meningkatkan layanan HealthSelf</p>
            </div>

            <form method="POST" action="{{ route('mahasiswa.feedback.store') }}">
                @csrf

                {{-- RATING STARS --}}
                <div style="text-align:center; margin-bottom:28px;">
                    <label style="display:block; font-weight:700; color:#374151; margin-bottom:14px; font-size:0.95rem;">Rating Keseluruhan *</label>
                    <div class="star-rating" style="display:flex; justify-content:center; gap:8px;">
                        @for($i = 1; $i <= 5; $i++)
                            <label for="star{{ $i }}" style="font-size:2.8rem; cursor:pointer; transition:transform 0.1s; line-height:1;" class="star-label">
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" style="display:none;" {{ old('rating') == $i ? 'checked' : '' }} required>
                                <span class="star-icon" data-val="{{ $i }}">☆</span>
                            </label>
                        @endfor
                    </div>
                    @error('rating') <div style="color:#ef4444; font-size:0.8rem; margin-top:8px;">{{ $message }}</div> @enderror
                </div>

                {{-- KATEGORI --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-weight:700; color:#374151; font-size:0.875rem; margin-bottom:8px;">Feedback tentang apa?</label>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        @foreach(['umum' => ['icon' => '🌐', 'label' => 'Umum'], 'chatbot' => ['icon' => '💬', 'label' => 'Chatbot AI'], 'artikel' => ['icon' => '📝', 'label' => 'Artikel'], 'konselor' => ['icon' => '👨‍⚕️', 'label' => 'Konselor']] as $val => $item)
                        <label style="cursor:pointer;">
                            <input type="radio" name="kategori_feedback" value="{{ $val }}" style="display:none;" {{ old('kategori_feedback', 'umum') == $val ? 'checked' : '' }} class="cat-radio">
                            <div class="cat-card" data-val="{{ $val }}" style="border:2px solid #e5e7eb; border-radius:12px; padding:12px; text-align:center; transition:all 0.2s; {{ old('kategori_feedback', 'umum') == $val ? 'border-color:#800000; background:#fff8f8;' : '' }}">
                                <div style="font-size:1.5rem;">{{ $item['icon'] }}</div>
                                <div style="font-size:0.8rem; font-weight:600; color:#374151; margin-top:4px;">{{ $item['label'] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('kategori_feedback') <div style="color:#ef4444; font-size:0.8rem; margin-top:8px;">{{ $message }}</div> @enderror
                </div>

                {{-- KOMENTAR --}}
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-weight:700; color:#374151; font-size:0.875rem; margin-bottom:8px;">Komentar (opsional)</label>
                    <textarea name="komentar" rows="4" style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:12px; font-family:inherit; font-size:0.9rem; resize:vertical; outline:none; transition:border 0.2s; box-sizing:border-box; background:#fafafa;" placeholder="Ceritakan pengalaman Anda menggunakan HealthSelf..." onfocus="this.style.borderColor='#800000'" onblur="this.style.borderColor='#e5e7eb'">{{ old('komentar') }}</textarea>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end;">
                    <a href="{{ route('home') }}" style="background:#f3f4f6; color:#374151; padding:12px 24px; border-radius:12px; font-weight:600; text-decoration:none; font-size:0.9rem;">Batal</a>
                    <button type="submit" style="background:linear-gradient(135deg,#800000,#570303); color:white; padding:12px 28px; border-radius:12px; font-weight:700; border:none; cursor:pointer; font-family:inherit; font-size:0.9rem;">
                        ✓ Kirim Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .star-label { color: #d1d5db; }
    .star-label:hover { transform: scale(1.2); }
    .star-icon.filled { color: #f59e0b; }
</style>

<script>
    // STAR RATING
    const stars    = document.querySelectorAll('.star-label');
    const starIcons= document.querySelectorAll('.star-icon');
    const radios   = document.querySelectorAll('input[name="rating"]');

    function updateStars(val) {
        starIcons.forEach(s => {
            const sv = parseInt(s.dataset.val);
            s.textContent = sv <= val ? '★' : '☆';
            sv <= val ? s.classList.add('filled') : s.classList.remove('filled');
        });
    }

    radios.forEach(r => {
        if (r.checked) updateStars(parseInt(r.value));
        r.addEventListener('change', () => updateStars(parseInt(r.value)));
    });

    stars.forEach(label => {
        label.addEventListener('mouseover', () => {
            const val = parseInt(label.querySelector('.star-icon').dataset.val);
            updateStars(val);
        });
        label.addEventListener('mouseleave', () => {
            const checked = document.querySelector('input[name="rating"]:checked');
            updateStars(checked ? parseInt(checked.value) : 0);
        });
        label.addEventListener('click', () => {
            const radio = label.querySelector('input[type="radio"]');
            radio.checked = true;
            updateStars(parseInt(radio.value));
        });
    });

    // KATEGORI CARDS
    document.querySelectorAll('.cat-radio').forEach(r => {
        r.addEventListener('change', () => {
            document.querySelectorAll('.cat-card').forEach(c => {
                c.style.borderColor = '#e5e7eb';
                c.style.background = '';
            });
            document.querySelector(`.cat-card[data-val="${r.value}"]`).style.borderColor = '#800000';
            document.querySelector(`.cat-card[data-val="${r.value}"]`).style.background = '#fff8f8';
        });
    });
</script>

@endsection
