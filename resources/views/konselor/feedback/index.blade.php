@extends('layouts.konselor')
@section('title', 'Feedback Pengguna')
@section('page-title', 'Feedback Pengguna')

@section('content')

<div class="card" style="margin-bottom:24px; display:flex; align-items:center; gap:20px;">
    <div style="text-align:center; padding:20px 28px; border-right:1px solid #f3f4f6;">
        <div style="font-size:3rem; font-weight:900; color:#111; line-height:1;">{{ $avgRating ?: '-' }}</div>
        <div style="margin:8px 0 4px;">
            @for($i=1;$i<=5;$i++)
                <span style="font-size:1.3rem; color:{{ $i<=round($avgRating)?'#f59e0b':'#d1d5db' }};">★</span>
            @endfor
        </div>
        <div style="color:#6b7280; font-size:0.8rem;">{{ $feedbacks->total() }} feedback</div>
    </div>
    <div style="font-size:0.9rem; color:#374151; line-height:1.8;">
        <p>Feedback dari pengguna membantu meningkatkan kualitas layanan HealthSelf. Perhatikan komentar kritis untuk perbaikan berkelanjutan.</p>
    </div>
</div>

<div class="card" style="padding:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th style="padding-left:24px;">Pengguna</th>
                <th>Rating</th>
                <th>Kategori</th>
                <th>Komentar</th>
                <th style="padding-right:24px;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $fb)
            <tr>
                <td style="padding-left:24px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="{{ $fb->user->avatarUrl() }}" style="width:34px; height:34px; border-radius:50%;" alt="">
                        <div>
                            <div style="font-weight:600; font-size:0.875rem;">{{ $fb->user->name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @for($i=1;$i<=5;$i++)
                        <span style="color:{{ $i<=$fb->rating?'#f59e0b':'#d1d5db' }}; font-size:0.95rem;">★</span>
                    @endfor
                </td>
                <td><span class="badge badge-konselor">{{ ucfirst($fb->kategori_feedback) }}</span></td>
                <td style="max-width:320px; color:#374151; font-size:0.875rem;">{{ $fb->komentar ?: '-' }}</td>
                <td style="padding-right:24px; color:#9ca3af; font-size:0.8rem;">{{ $fb->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#9ca3af; padding:48px;">Belum ada feedback.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:20px 24px; border-top:1px solid #f3f4f6;">{{ $feedbacks->links() }}</div>
</div>

@endsection
