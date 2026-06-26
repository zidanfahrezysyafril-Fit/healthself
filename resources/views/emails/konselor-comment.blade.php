<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan dari Konselor – HealthSelf</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9fafb; margin: 0; padding: 20px; }
        .wrapper { max-width: 560px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #570303, #800000); padding: 32px 40px; text-align: center; }
        .header h1 { color: white; font-size: 1.6rem; font-weight: 800; margin: 0; }
        .header p { color: rgba(255,255,255,0.8); font-size: 0.9rem; margin: 8px 0 0; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 1rem; color: #111; margin-bottom: 20px; }
        .konselor-box { background: #fffbeb; border: 1.5px solid #fcd34d; border-radius: 12px; padding: 20px 24px; margin: 20px 0; }
        .konselor-name { font-weight: 700; color: #92400e; margin-bottom: 8px; }
        .message-text { color: #374151; font-size: 0.95rem; line-height: 1.7; }
        .chat-preview { background: #f9fafb; border-left: 4px solid #d1d5db; padding: 14px 18px; border-radius: 0 8px 8px 0; margin: 16px 0; font-size: 0.875rem; color: #6b7280; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: linear-gradient(135deg, #800000, #570303); color: white; padding: 13px 32px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 0.9rem; }
        .footer { background: #f3f4f6; padding: 20px 40px; text-align: center; font-size: 0.78rem; color: #9ca3af; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>HealthSelf</h1>
        <p>Platform Kesehatan Digital</p>
    </div>
    <div class="body">
        <p class="greeting">Halo <strong>{{ $user->name }}</strong> 👋</p>
        <p style="color:#374151; font-size:0.9rem; line-height:1.7;">Anda menerima pesan dari konselor HealthSelf mengenai percakapan Anda di platform kami.</p>

        <div class="konselor-box">
            <div class="konselor-name">💬 {{ $konselor->name }} (Konselor HealthSelf):</div>
            <div class="message-text">{{ $komentar }}</div>
        </div>

        @if(isset($chat))
        <div class="chat-preview">
            <strong>Tentang pesan:</strong><br>
            "{{ Str::limit($chat->pesan_user, 120) }}"
        </div>
        @endif

        <div class="cta">
            <a href="{{ url('/profil') }}">Lihat Profil Anda</a>
        </div>

        <p style="color:#9ca3af; font-size:0.8rem; text-align:center; line-height:1.6;">Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menggunakan chatbot HealthSelf AI kapan saja.</p>
    </div>
    <div class="footer">
        © {{ date('Y') }} HealthSelf. Semua hak dilindungi.<br>
        Platform Informasi & Konsultasi Kesehatan Digital
    </div>
</div>
</body>
</html>
