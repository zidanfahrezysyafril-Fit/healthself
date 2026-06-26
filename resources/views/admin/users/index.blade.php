@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

<div class="section-header">
    <div>
        <div class="section-title">Daftar Pengguna</div>
        <div style="color:#6b7280; font-size:0.875rem; margin-top:4px;">{{ $users->total() }} pengguna terdaftar</div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">➕ Tambah Pengguna</a>
</div>

{{-- FILTER --}}
<div class="card" style="padding:20px; margin-bottom:24px;">
    <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="form-control" style="flex:1; min-width:200px; margin:0;">
        <select name="role" class="form-control" style="width:160px; margin:0;">
            <option value="">Semua Role</option>
            <option value="admin"    {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="konselor" {{ request('role') === 'konselor' ? 'selected' : '' }}>Konselor</option>
            <option value="mahasiswa"{{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
        </select>
        <button type="submit" class="btn btn-primary">🔍 Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
    </form>
</div>

<div class="card" style="padding:0;">
    <table class="data-table">
        <thead>
            <tr>
                <th style="padding-left:24px;">Pengguna</th>
                <th>Role</th>
                <th>NIM/NIP</th>
                <th>Bergabung</th>
                <th>Login</th>
                <th style="padding-right:24px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="padding-left:24px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="{{ $user->avatarUrl() }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #f3f4f6;" alt="">
                        <div>
                            <div style="font-weight:700; color:#111; font-size:0.875rem;">{{ $user->name }}</div>
                            <div style="color:#9ca3af; font-size:0.75rem;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td style="color:#6b7280; font-size:0.8rem;">{{ $user->nim_nip ?: '-' }}</td>
                <td style="color:#6b7280; font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if($user->google_id)
                        <span style="font-size:0.75rem; background:#fef3c7; color:#92400e; padding:3px 8px; border-radius:8px;">Google</span>
                    @else
                        <span style="font-size:0.75rem; background:#f3f4f6; color:#6b7280; padding:3px 8px; border-radius:8px;">Email</span>
                    @endif
                </td>
                <td style="padding-right:24px;">
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#9ca3af; padding:48px;">Tidak ada pengguna ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:20px 24px; border-top:1px solid #f3f4f6;">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

@endsection
