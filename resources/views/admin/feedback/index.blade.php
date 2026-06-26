@extends('layouts.admin')
@section('title', 'Feedback Pengguna')
@section('page-title', 'Feedback Pengguna')

@section('content')

{{-- RATING SUMMARY --}}
<div style="display:grid; grid-template-columns:1fr 2fr; gap:24px; margin-bottom:32px;">
    <div class="card" style="text-align:center;">
        <div style="font-size:3.5rem; font-weight:900; color:#111; line-height:1;">{{ $avgRating ?: '-' }}</div>
        <div style="margin:10px 0 4px;">
            @for($i = 1; $i <= 5; $i++)
                <span style="font-size:1.5rem; color: {{ $i <= round($avgRating) ? '#f59e0b' : '#d1d5db' }};">★</span>
            @endfor
        </div>
        <div style="color:#6b7280; font-size:0.875rem;">Rating Rata-rata</div>
        <div style="color:#9ca3af; font-size:0.8rem; margin-top:4px;">dari {{ $feedbacks->total() }} feedback</div>
    </div>
    <div class="card">
        <div style="font-weight:700; color:#111; margin-bottom:16px;">Distribusi Rating</div>
        @foreach([5,4,3,2,1] as $r)
            @php $count = $ratingCounts[$r] ?? 0; $pct = $feedbacks->total() > 0 ? ($count / $feedbacks->total() * 100) : 0; @endphp
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <span style="width:50px; color:#6b7280; font-size:0.8rem; text-align:right;">{{ $r }} ★</span>
                <div style="flex:1; background:#f3f4f6; border-radius:20px; height:10px; overflow:hidden;">
                    <div style="width:{{ $pct }}%; background:#f59e0b; height:100%; border-radius:20px; transition:width 0.4s;"></div>
                </div>
                <span style="width:36px; color:#6b7280; font-size:0.8rem;">{{ $count }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- TABLE --}}
<div class="card" style="padding:0;">
    <div style="padding:20px 24px; border-bottom:1px solid #f3f4f6; font-size:1.1rem; font-weight:800; color:#111;">Semua Feedback</div>
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
                        <img src="{{ $fb->user->avatarUrl() }}" style="width:34px; height:34px; border-radius:50%; object-fit:cover;" alt="">
                        <div>
                            <div style="font-weight:600; font-size:0.875rem;">{{ $fb->user->name }}</div>
                            <div style="color:#9ca3af; font-size:0.73rem;">{{ $fb->user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display:flex;">
                        @for($i = 1; $i <= 5; $i++)
                            <span style="color: {{ $i <= $fb->rating ? '#f59e0b' : '#d1d5db' }}; font-size:0.95rem;">★</span>
                        @endfor
                    </div>
                </td>
                <td><span class="badge badge-mahasiswa">{{ ucfirst($fb->kategori_feedback) }}</span></td>
                <td style="max-width:300px; color:#374151; font-size:0.875rem;">{{ $fb->komentar ?: '-' }}</td>
                <td style="padding-right:24px; color:#9ca3af; font-size:0.8rem;">{{ $fb->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#9ca3af; padding:48px;">Belum ada feedback.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:20px 24px; border-top:1px solid #f3f4f6;">
        {{ $feedbacks->links() }}
    </div>
</div>

@endsection
