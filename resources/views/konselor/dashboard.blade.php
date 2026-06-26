@extends('layouts.konselor')
@section('title', 'Dashboard Konselor')
@section('page-title', 'Overview Konselor')

@section('content')

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:24px; margin-bottom:40px;">
    
    <div class="stat-card" style="--card-color-1: #f59e0b; --card-color-2: #d97706;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @if($stats['artikel_pending'] > 0)
                <span style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:white;font-size:0.7rem;padding:2px 6px;border-radius:10px;font-family:sans-serif;font-weight:bold;border:2px solid white;">{{ $stats['artikel_pending'] }}</span>
            @endif
        </div>
        <div class="stat-value" style="{{ $stats['artikel_pending'] > 0 ? 'color:#d97706;' : '' }}">{{ $stats['artikel_pending'] }}</div>
        <div class="stat-label">Artikel Perlu Divalidasi</div>
    </div>
    <div class="stat-card" style="--card-color-1: #10b981; --card-color-2: #059669;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
        <div class="stat-value">{{ $stats['artikel_dibuat'] }}</div>
        <div class="stat-label">Artikel Saya</div>
    </div>
    <div class="stat-card" style="--card-color-1: #8b5cf6; --card-color-2: #7c3aed;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div class="stat-value">{{ $stats['artikel_divalidasi'] }}</div>
        <div class="stat-label">Artikel Divalidasi</div>
    </div>
    <div class="stat-card" style="--card-color-1: #ec4899; --card-color-2: #db2777;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></div>
        <div class="stat-value">{{ $stats['total_chat'] }}</div>
        <div class="stat-label">Total Percakapan</div>
    </div>
    <div class="stat-card" style="--card-color-1: #ef4444; --card-color-2: #dc2626;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg></div>
        <div class="stat-value" style="{{ $stats['chat_flagged'] > 0 ? 'color:#ef4444;' : '' }}">{{ $stats['chat_flagged'] }}</div>
        <div class="stat-label">Chat Berbahaya (Flagged)</div>
    </div>
    <div class="stat-card" style="--card-color-1: #14b8a6; --card-color-2: #0d9488;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg></div>
        <div class="stat-value">{{ $stats['avg_rating'] ?: '-' }}</div>
        <div class="stat-label">Rating Konselor</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">

    {{-- ARTIKEL PENDING --}}
    <div class="card">
        <div class="section-header">
            <div class="section-title">
                <div style="width:40px;height:40px;border-radius:12px;background:#fffbeb;color:#d97706;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                Perlu Divalidasi
            </div>
            <a href="{{ route('konselor.artikel.index', ['tab' => 'validasi']) }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        
        <div style="display:flex; flex-direction:column; gap:16px;">
            @forelse($pendingArtikel as $artikel)
                <div style="display:flex; align-items:center; gap:16px; padding:12px; border-radius:16px; border:1px solid #f1f5f9; transition:all 0.3s; background:#f8fafc;">
                    <div style="width:60px; height:60px; border-radius:12px; overflow:hidden; flex-shrink:0; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
                        <img src="{{ $artikel->thumbnailUrl() }}" style="width:100%; height:100%; object-fit:cover;" alt="">
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:700; font-size:0.95rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px;">{{ $artikel->judul }}</div>
                        <div style="display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#64748b;">
                            <img src="{{ $artikel->pembuat->avatarUrl() }}" style="width:16px; height:16px; border-radius:50%;" alt="">
                            <span>{{ $artikel->pembuat->name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('konselor.artikel.validasi', $artikel) }}" class="btn btn-success btn-sm">Validasi</a>
                </div>
            @empty
                <div style="text-align:center; padding:40px 20px; background:#f8fafc; border-radius:16px; border:2px dashed #e2e8f0;">
                    <svg style="width:48px;height:48px;color:#cbd5e1;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div style="color:#64748b; font-weight:600; font-size:0.95rem;">Semua artikel sudah divalidasi</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- RECENT CHAT --}}
    <div class="card">
        <div class="section-header">
            <div class="section-title">
                <div style="width:40px;height:40px;border-radius:12px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                Percakapan Terbaru
            </div>
            <a href="{{ route('konselor.chat.index') }}" class="btn btn-secondary btn-sm">Monitor</a>
        </div>
        
        <div style="display:flex; flex-direction:column; gap:12px;">
            @forelse($recentChats as $chat)
                <div style="display:flex; align-items:flex-start; gap:16px; padding:12px 16px; border-radius:16px; transition:all 0.2s; border:1px solid transparent; {{ $chat->is_flagged ? 'background:#fef2f2; border-color:#fecaca;' : '' }}" onmouseover="if(!{{ $chat->is_flagged ? 'true' : 'false' }}){ this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; }" onmouseout="if(!{{ $chat->is_flagged ? 'true' : 'false' }}){ this.style.background='transparent'; this.style.borderColor='transparent'; }">
                    <img src="{{ $chat->user->avatarUrl() }}" style="width:44px; height:44px; border-radius:14px; object-fit:cover; box-shadow:0 4px 10px rgba(0,0,0,0.05);" alt="">
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <div style="font-weight:700; font-size:0.95rem; color:#0f172a;">{{ $chat->user->name }}</div>
                            <div style="font-size:0.75rem; color:#94a3b8;">{{ $chat->waktu_chat->diffForHumans() }}</div>
                        </div>
                        <div style="font-size:0.85rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $chat->pesan_user }}
                        </div>
                    </div>
                    @if($chat->is_flagged)
                        <span class="badge badge-flagged" style="margin-top:2px;">🚩 Flagged</span>
                    @endif
                </div>
            @empty
                <div style="text-align:center; color:#9ca3af; padding:24px; font-size:0.875rem;">Belum ada percakapan</div>
            @endforelse
        </div>
    </div>

    {{-- FEEDBACK TERBARU --}}
    <div class="card" style="grid-column: 1 / -1;">
        <div class="section-header">
            <div class="section-title">
                <div style="width:40px;height:40px;border-radius:12px;background:#fef2f2;color:#ef4444;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                Feedback Terbaru (Rating Konselor: <strong style="margin-left:6px; color:#ef4444;">{{ $stats['avg_rating'] ?: '-' }}</strong>)
            </div>
            <a href="{{ route('konselor.feedback.index') }}" class="btn btn-secondary btn-sm">Semua Feedback</a>
        </div>
        
        <div style="overflow-x:auto; border-radius:16px; border:1px solid #f1f5f9;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFeedback as $fb)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <img src="{{ $fb->user->avatarUrl() }}" style="width:36px; height:36px; border-radius:10px; object-fit:cover;" alt="">
                                    <span style="font-weight:700; color:#0f172a;">{{ $fb->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; gap:2px; color:#f59e0b; font-size:1.2rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= $fb->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td style="max-width:300px; color:#475569; font-size:0.85rem; line-height:1.5;">
                                <div style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $fb->komentar ?: '-' }}
                                </div>
                            </td>
                            <td style="color:#64748b; font-size:0.85rem; font-weight:500;">
                                {{ $fb->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#94a3b8; padding:40px;">
                                <svg style="width:48px;height:48px;margin:0 auto 12px;opacity:0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <div style="font-weight:600; font-size:0.95rem;">Belum ada feedback yang masuk</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
