@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div style="max-width:640px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card">
        <div style="display:flex; align-items:center; gap:16px; margin-bottom:28px; padding-bottom:24px; border-bottom:1px solid #f3f4f6;">
            <img src="{{ $user->avatarUrl() }}" style="width:60px; height:60px; border-radius:50%; object-fit:cover; border:3px solid #f3f4f6;" alt="">
            <div>
                <h2 style="font-size:1.2rem; font-weight:800; color:#111; margin:0;">{{ $user->name }}</h2>
                <p style="color:#6b7280; font-size:0.875rem; margin:4px 0 0;">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="mahasiswa" {{ $user->role === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="konselor"  {{ $user->role === 'konselor'  ? 'selected' : '' }}>Konselor/Dokter</option>
                        <option value="admin"     {{ $user->role === 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <div style="color:#9ca3af; font-size:0.78rem; margin-top:4px;">Tidak bisa mengubah role akun sendiri.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="password-container">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" style="padding-right: 40px;">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">NIM / NIP</label>
                    <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="prodi" value="{{ old('prodi', $user->prodi) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        if (type === 'text') {
            btn.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>`;
        } else {
            btn.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
        }
    }
</script>
@endsection
