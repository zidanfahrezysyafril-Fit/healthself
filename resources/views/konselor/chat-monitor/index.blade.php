@extends('layouts.konselor')
@section('title', 'Monitor Chat')
@section('page-title', 'Monitor Percakapan Pengguna')

@section('content')

<div style="display:flex; gap:12px; margin-bottom:24px; align-items:center;">
    @if($flaggedCount > 0)
    <a href="{{ route('konselor.chat.flagged') }}" class="btn btn-danger">
        🚩 {{ $flaggedCount }} Chat Berbahaya
    </a>
    @endif
    <div style="color:#6b7280; font-size:0.875rem;">Semua percakapan pengguna ditampilkan di sini secara real-time.</div>
</div>

<div class="card" style="padding:16px 20px; margin-bottom:24px;">
    <form method="GET" style="display:flex; gap:12px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email pengguna..." class="form-control" style="flex:1; margin:0;">
        <button type="submit" class="btn btn-primary">🔍</button>
        <a href="{{ route('konselor.chat.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="card" style="padding:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th style="padding-left:24px;">Pengguna</th>
                <th>Pesan Terakhir</th>
                <th>Jumlah Chat</th>
                <th>Waktu</th>
                <th style="padding-right:24px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="padding-left:24px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="{{ $user->avatarUrl() }}" style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #f3f4f6;" alt="">
                        <div>
                            <div style="font-weight:700; font-size:0.875rem; color:#111;">{{ $user->name }}</div>
                            <div style="color:#9ca3af; font-size:0.75rem;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:0.875rem; color:#6b7280;">
                        {{ $user->riwayatChat->first()?->pesan_user ?? '-' }}
                    </div>
                </td>
                <td>
                    <span style="background:#e0e7ff; color:#3730a3; font-size:0.8rem; font-weight:700; padding:4px 10px; border-radius:20px;">
                        {{ $user->riwayat_chat_count }}
                    </span>
                </td>
                <td style="color:#9ca3af; font-size:0.8rem;">
                    {{ $user->riwayatChat->first()?->waktu_chat?->diffForHumans() ?? '-' }}
                </td>
                <td style="padding-right:24px;">
                    <a href="{{ route('konselor.chat.detail', $user) }}" class="btn btn-primary btn-sm">👁 Lihat Chat</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#9ca3af; padding:48px;">Belum ada pengguna yang melakukan percakapan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:20px 24px; border-top:1px solid #f3f4f6;">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

@endsection
