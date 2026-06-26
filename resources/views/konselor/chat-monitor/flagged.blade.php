@extends('layouts.konselor')
@section('title', 'Chat Berbahaya')
@section('page-title', 'Chat Berbahaya / Terflag')

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('konselor.chat.index') }}" class="btn btn-secondary btn-sm">← Kembali ke Monitor Chat</a>
</div>

@if($chats->isEmpty())
    <div style="text-align:center; background:white; border-radius:18px; padding:60px; color:#9ca3af;">
        ✅ Tidak ada chat yang ditandai berbahaya.
    </div>
@else
<div class="card" style="padding:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th style="padding-left:24px;">Pengguna</th>
                <th>Pesan</th>
                <th>Alasan Flag</th>
                <th>Waktu</th>
                <th style="padding-right:24px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chats as $chat)
            <tr style="background:#fff8f8;">
                <td style="padding-left:24px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="{{ $chat->user->avatarUrl() }}" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" alt="">
                        <div>
                            <div style="font-weight:700; font-size:0.875rem;">{{ $chat->user->name }}</div>
                            <div style="color:#9ca3af; font-size:0.73rem;">{{ $chat->user->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:0.875rem; color:#6b7280;">
                    {{ $chat->pesan_user }}
                </td>
                <td>
                    <span style="background:#fef2f2; color:#991b1b; font-size:0.78rem; padding:4px 10px; border-radius:8px;">{{ $chat->flag_reason }}</span>
                </td>
                <td style="color:#9ca3af; font-size:0.8rem;">{{ $chat->waktu_chat->format('d M Y, H:i') }}</td>
                <td style="padding-right:24px;">
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('konselor.chat.detail', $chat->user) }}" class="btn btn-primary btn-sm">👁 Detail</a>
                        <form method="POST" action="{{ route('konselor.chat.unflag', $chat) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">🏳 Unflag</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:20px 24px; border-top:1px solid #f3f4f6;">
        {{ $chats->links() }}
    </div>
</div>
@endif
@endsection
