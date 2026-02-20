@extends('layouts.app')

@section('title', 'Edit User')

@push('styles')
<style>
    .um-form-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        max-width: 680px;
    }
    .um-form-header {
        padding: 22px 28px 18px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 12px;
    }
    .um-form-header-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex; align-items: center; justify-content: center;
    }
    .um-form-header-icon svg { width: 20px; height: 20px; color: #fff; }
    .um-form-header h3 { font-size: 16px; font-weight: 700; margin: 0 0 2px; color: var(--text-main); }
    .um-form-header p  { font-size: 12.5px; color: var(--text-muted); margin: 0; }
    .um-form-body { padding: 26px 28px; }
    .um-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    @media (max-width: 580px) { .um-form-row { grid-template-columns: 1fr; } }
    .um-form-footer {
        padding: 16px 28px;
        border-top: 1px solid var(--border);
        display: flex; gap: 10px; align-items: center; justify-content: flex-end;
        background: #fafbff;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    }
    .um-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 5px; }
</style>
@endpush

@section('content')

<div style="margin-bottom: 22px;">
    <a href="{{ route('users.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:14px;">
        ← Kembali ke Daftar User
    </a>
    <h2 style="font-size:22px; font-weight:800; color:var(--text-main); margin:0 0 3px;">Edit User</h2>
    <p style="font-size:13px; color:var(--text-muted); margin:0;">Perbarui informasi akun: <strong>{{ $user->name }}</strong></p>
</div>

<div class="um-form-card">
    <div class="um-form-header">
        <div class="um-form-header-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <div>
            <h3>Edit Data User</h3>
            <p>Biarkan kolom password kosong jika tidak ingin mengubah password</p>
        </div>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="um-form-body">

            <div class="um-form-row">
                <div class="form-group">
                    <label>Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="um-hint" style="color:#ef4444;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username') <p class="um-hint" style="color:#ef4444;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="um-form-row">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">
                    @error('password') <p class="um-hint" style="color:#ef4444;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="um-form-row">
                <div class="form-group">
                    <label>Role <span style="color:#ef4444;">*</span></label>
                    <select name="role" required>
                        @foreach($roles as $val => $label)
                        <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('role') <p class="um-hint" style="color:#ef4444;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Unit Kerja</label>
                    <select name="unit_kerja_id">
                        <option value="">— Tanpa Unit (Super Admin) —</option>
                        @foreach($unitKerja as $uk)
                        <option value="{{ $uk->id }}"
                            {{ old('unit_kerja_id', $user->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                            {{ $uk->nama_unit }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit_kerja_id') <p class="um-hint" style="color:#ef4444;">{{ $message }}</p> @enderror
                </div>
            </div>

        </div>
        <div class="um-form-footer">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
