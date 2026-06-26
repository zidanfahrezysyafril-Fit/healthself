@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Overview Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:24px; margin-bottom:40px;">

    <div class="stat-card" style="--card-color-1: #3b82f6; --card-color-2: #2563eb;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card" style="--card-color-1: #10b981; --card-color-2: #059669;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6"></path></svg></div>
        <div class="stat-value">{{ $stats['total_mahasiswa'] }}</div>
        <div class="stat-label">Mahasiswa Aktif</div>
    </div>
    <div class="stat-card" style="--card-color-1: #8b5cf6; --card-color-2: #7c3aed;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg></div>
        <div class="stat-value">{{ $stats['total_konselor'] }}</div>
        <div class="stat-label">Konselor Terdaftar</div>
    </div>
    <div class="stat-card" style="--card-color-1: #ec4899; --card-color-2: #db2777;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div>
        <div class="stat-value">{{ $stats['artikel_published'] }}</div>
        <div class="stat-label">Artikel Terpublikasi</div>
    </div>
    <div class="stat-card" style="--card-color-1: #f59e0b; --card-color-2: #d97706;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @if($stats['artikel_pending'] > 0)
                <span style="position:absolute;top:-6px;right:-6px;background:#ef4444;color:white;font-size:0.7rem;padding:2px 6px;border-radius:10px;font-family:sans-serif;font-weight:bold;border:2px solid white;">{{ $stats['artikel_pending'] }}</span>
            @endif
        </div>
        <div class="stat-value">{{ $stats['artikel_pending'] }}</div>
        <div class="stat-label">Artikel Menunggu Verifikasi</div>
    </div>
    <div class="stat-card" style="--card-color-1: #14b8a6; --card-color-2: #0d9488;">
        <div class="stat-icon"><svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg></div>
        <div class="stat-value">{{ $stats['total_chat'] }}</div>
        <div class="stat-label">Total Percakapan (Sesi)</div>
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
                Menunggu Verifikasi
            </div>
            <a href="{{ route('admin.artikel.index', ['status' => 'pending']) }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
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
                            <span>•</span>
                            <span style="color:#d97706; font-weight:600;">{{ $artikel->kategori->nama_kategori }}</span>
                        </div>
                    </div>
                    <span class="badge badge-pending">Pending</span>
                </div>
            @empty
                <div style="text-align:center; padding:40px 20px; background:#f8fafc; border-radius:16px; border:2px dashed #e2e8f0;">
                    <svg style="width:48px;height:48px;color:#cbd5e1;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div style="color:#64748b; font-weight:600; font-size:0.95rem;">Tidak ada artikel pending</div>
                    <div style="color:#94a3b8; font-size:0.8rem; margin-top:4px;">Semua artikel telah diverifikasi</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- USER TERBARU --}}
    <div class="card">
        <div class="section-header">
            <div class="section-title">
                <div style="width:40px;height:40px;border-radius:12px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> 
                </div>
                Pengguna Terbaru
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Kelola</a>
        </div>
        
        <div style="display:flex; flex-direction:column; gap:12px;">
            @forelse($recentUsers as $user)
                <div style="display:flex; align-items:center; gap:16px; padding:12px 16px; border-radius:16px; transition:all 0.2s; border:1px solid transparent;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
                    <img src="{{ $user->avatarUrl() }}" style="width:48px; height:48px; border-radius:14px; object-fit:cover; box-shadow:0 4px 10px rgba(0,0,0,0.05);" alt="">
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:0.95rem; color:#0f172a; margin-bottom:2px;">{{ $user->name }}</div>
                        <div style="font-size:0.8rem; color:#64748b;">{{ $user->email }}</div>
                    </div>
                    <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </div>
            @empty
                <div style="text-align:center; color:#9ca3af; padding:24px; font-size:0.875rem;">Belum ada pengguna</div>
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
                Feedback Terbaru (Rating Rata-rata: <strong style="margin-left:6px; color:#ef4444;">{{ $stats['avg_rating'] ?: '-' }}</strong>)
            </div>
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary btn-sm">Semua Feedback</a>
        </div>
        
        <div style="overflow-x:auto; border-radius:16px; border:1px solid #f1f5f9;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Rating</th>
                        <th>Topik</th>
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
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-weight:700; color:#0f172a;">{{ $fb->user->name }}</span>
                                        <span style="font-size:0.75rem; color:#64748b;">{{ ucfirst($fb->user->role) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; gap:2px; color:#f59e0b; font-size:1.2rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="color: {{ $i <= $fb->rating ? '#f59e0b' : '#e2e8f0' }};">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td><span class="badge badge-mahasiswa">{{ ucfirst($fb->kategori_feedback) }}</span></td>
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
                            <td colspan="5" style="text-align:center; color:#94a3b8; padding:40px;">
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
