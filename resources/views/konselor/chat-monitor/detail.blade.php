@extends('layouts.konselor')
@section('title', 'Detail Chat – ' . $user->name)
@section('page-title', 'Riwayat Chat: ' . $user->name)

@section('content')
<div style="max-width:960px; margin:0 auto;">
    <div style="margin-bottom:20px; display:flex; gap:12px; align-items:center;">
        <a href="{{ route('konselor.chat.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        <div style="display:flex; align-items:center; gap:10px; background:white; padding:10px 16px; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,0.06);">
            <img src="{{ $user->avatarUrl() }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" alt="">
            <div>
                <div style="font-weight:700; font-size:0.9rem; color:#111;">{{ $user->name }}</div>
                <div style="font-size:0.75rem; color:#6b7280;">{{ $user->email }} · {{ $chats->count() }} pesan</div>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 360px; gap:24px; align-items:start;">

        {{-- CHAT HISTORY --}}
        <div class="card" style="padding:20px; max-height:75vh; overflow-y:auto;" id="chat-container">

            @forelse($chats as $chat)
            <div style="margin-bottom:20px; {{ $chat->is_flagged ? 'background:#fff8f8; border-radius:12px; padding:12px; border:1.5px solid #fca5a5;' : '' }}" id="chat-{{ $chat->id }}">

                {{-- FLAG INDICATOR --}}
                @if($chat->is_flagged)
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                        <span style="background:#ef4444; color:white; font-size:0.7rem; font-weight:700; padding:3px 8px; border-radius:10px;">🚩 FLAGGED</span>
                        <span style="color:#6b7280; font-size:0.72rem;">{{ $chat->flag_reason }}</span>
                    </div>
                @endif

                {{-- USER MESSAGE --}}
                <div class="chat-row user">
                    <div>
                        <div class="chat-bubble-user">{{ $chat->pesan_user }}</div>
                        <div class="chat-time">{{ $chat->waktu_chat->format('d M Y, H:i') }}</div>
                    </div>
                    <img src="{{ $user->avatarUrl() }}" style="width:30px; height:30px; border-radius:50%; object-fit:cover; flex-shrink:0;" alt="">
                </div>

                {{-- BOT RESPONSE --}}
                <div class="chat-row" style="margin-top:8px;">
                    <div style="width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg, #0f3460, #16213e); display:flex; align-items:center; justify-content:center; font-size:0.9rem; flex-shrink:0;">🤖</div>
                    <div>
                        <div class="chat-bubble-bot">{{ $chat->respon_bot }}</div>
                        <div class="chat-time" style="text-align:left;">HealthSelf AI</div>
                    </div>
                </div>

                {{-- KOMENTAR KONSELOR --}}
                @if($chat->komentar->isNotEmpty())
                    @foreach($chat->komentar as $kom)
                    <div style="background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:10px 14px; margin-top:10px; font-size:0.82rem;">
                        <span style="font-weight:700; color:#92400e;">💬 {{ $kom->konselor->name }}:</span>
                        <span style="color:#374151; margin-left:6px;">{{ $kom->komentar }}</span>
                        <span style="color:#9ca3af; font-size:0.72rem; margin-left:8px;">{{ $kom->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                @endif

                {{-- ACTIONS --}}
                <div style="display:flex; gap:6px; margin-top:10px; flex-wrap:wrap;">
                    @if(!$chat->is_flagged)
                        <button class="btn btn-danger btn-sm" onclick="showFlagForm({{ $chat->id }})">🚩 Tandai Berbahaya</button>
                    @else
                        <form method="POST" action="{{ route('konselor.chat.unflag', $chat) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">🏳 Hapus Flag</button>
                        </form>
                    @endif
                    <button class="btn btn-primary btn-sm" onclick="showCommentForm({{ $chat->id }})">💬 Kirim Komentar ke User</button>
                </div>

                {{-- FLAG FORM (tersembunyi) --}}
                <div id="flag-form-{{ $chat->id }}" style="display:none; margin-top:10px;">
                    <form method="POST" action="{{ route('konselor.chat.flag', $chat) }}">
                        @csrf
                        <div style="display:flex; gap:8px;">
                            <input type="text" name="flag_reason" placeholder="Alasan penandaan..." class="form-control" style="margin:0; flex:1;" required>
                            <button type="submit" class="btn btn-danger btn-sm">Tandai</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideFlagForm({{ $chat->id }})">Batal</button>
                        </div>
                    </form>
                </div>

                {{-- COMMENT FORM (tersembunyi) --}}
                <div id="comment-form-{{ $chat->id }}" style="display:none; margin-top:10px;">
                    <form method="POST" action="{{ route('konselor.chat.comment', $chat) }}">
                        @csrf
                        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:12px;">
                            <div style="font-size:0.78rem; color:#0369a1; margin-bottom:8px;">💬 Komentar ini akan dikirim ke email dan profil <strong>{{ $user->name }}</strong></div>
                            <textarea name="komentar" placeholder="Tulis pesan/saran untuk pengguna..." class="form-control" rows="3" style="margin-bottom:8px; resize:vertical;" required></textarea>
                            <div style="display:flex; gap:8px;">
                                <button type="submit" class="btn btn-primary btn-sm">✉️ Kirim</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="hideCommentForm({{ $chat->id }})">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @empty
                <div style="text-align:center; color:#9ca3af; padding:40px;">Belum ada riwayat chat.</div>
            @endforelse
        </div>

        {{-- SIDEBAR INFO --}}
        <div style="display:flex; flex-direction:column; gap:16px; position:sticky; top:80px;">
            <div class="card">
                <div style="font-weight:700; color:#111; margin-bottom:14px;">👤 Info Pengguna</div>
                <div style="font-size:0.875rem; color:#374151; line-height:2;">
                    <div>📧 {{ $user->email }}</div>
                    <div>🎓 {{ $user->prodi ?: '-' }}</div>
                    <div>🆔 {{ $user->nim_nip ?: '-' }}</div>
                    <div>📅 Bergabung {{ $user->created_at->format('d M Y') }}</div>
                    <div>💬 {{ $chats->count() }} percakapan total</div>
                </div>
            </div>
            <div class="card">
                <div style="font-weight:700; color:#111; margin-bottom:10px;">⚡ Aksi Cepat</div>
                <div style="font-size:0.8rem; color:#6b7280; margin-bottom:12px;">Komentar akan langsung dikirim ke email dan muncul di profil pengguna.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showFlagForm(id)    { document.getElementById('flag-form-'+id).style.display='block'; }
    function hideFlagForm(id)    { document.getElementById('flag-form-'+id).style.display='none'; }
    function showCommentForm(id) { document.getElementById('comment-form-'+id).style.display='block'; }
    function hideCommentForm(id) { document.getElementById('comment-form-'+id).style.display='none'; }

    // Auto-scroll to bottom of chat
    const cc = document.getElementById('chat-container');
    if (cc) cc.scrollTop = cc.scrollHeight;
</script>
@endsection
