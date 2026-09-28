@extends('layouts.konselor')
@section('title', 'Feedback Pengguna')
@section('page-title', 'Feedback Pengguna')

@section('content')

{{-- RATING SUMMARY --}}
<div style="display:grid; grid-template-columns:1fr 2fr; gap:24px; margin-bottom:32px;">
    <div class="card" style="text-align:center;">
        <div style="font-size:3.5rem; font-weight:900; color:#111; line-height:1;">{{ $avgRating ?: '-' }}</div>
        <div style="margin:10px 0 4px; display:flex; justify-content:center; gap:4px;">
            @for($i=1;$i<=5;$i++)
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:1.5rem; height:1.5rem; color:{{ $i<=round($avgRating)?'#f59e0b':'#e5e7eb' }}; transition: transform 0.2s;">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                </svg>
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
                    <div style="display:flex; gap:2px;">
                        @for($i=1;$i<=5;$i++)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:1.2rem; height:1.2rem; color:{{ $i<=$fb->rating?'#f59e0b':'#e5e7eb' }};">
                              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                            </svg>
                        @endfor
                    </div>
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
