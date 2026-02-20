@extends('layouts.app')

@section('title', 'Kelola User')

@push('styles')
<style>
    .um-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .um-header h2 { font-size: 22px; font-weight: 800; color: var(--text-main); margin: 0 0 3px; }
    .um-header p  { font-size: 13px; color: var(--text-muted); margin: 0; }

    .um-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .um-table { width: 100%; border-collapse: collapse; }
    .um-table th {
        background: #f8faff;
        padding: 12px 18px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--text-muted);
        text-align: left;
        border-bottom: 1.5px solid var(--border);
    }
    .um-table td {
        padding: 14px 18px;
        color: var(--text-main);
        border-bottom: 1px solid #f3f4f6;
        font-size: 13.5px;
        vertical-align: middle;
    }
    .um-table tbody tr:last-child td { border-bottom: none; }
    .um-table tbody tr:hover td { background: #fafbff; }

    .um-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f7ef8, #7c3aed);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; color: #fff;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .um-user-cell { display: flex; align-items: center; }
    .um-user-name { font-weight: 600; color: var(--text-main); }
    .um-user-sub  { font-size: 11.5px; color: var(--text-muted); }

    .um-role-badge {
        display: inline-flex; align-items: center;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 11.5px; font-weight: 700;
    }
    .role-super  { background: #ede9fe; color: #5b21b6; }
    .role-kepala { background: #dbeafe; color: #1d4ed8; }
    .role-pengurus { background: #dcfce7; color: #166534; }

    .um-actions { display: flex; gap: 8px; }

    .um-empty {
        padding: 50px 20px;
        text-align: center;
        color: var(--text-muted);
        font-size: 14px;
    }
    .um-empty svg { width: 44px; height: 44px; margin: 0 auto 12px; display: block; opacity: .35; }
</style>
@endpush

@section('content')

<div class="um-header">
    <div>
        <h2>Kelola User</h2>
        <p>Daftar semua pengguna yang terdaftar dalam sistem SIPEBA</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary" style="gap: 6px; display:inline-flex; align-items:center;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </a>
</div>

@if (session('success'))
<div class="alert alert-success" style="margin-bottom:18px;">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger" style="margin-bottom:18px;">{{ session('error') }}</div>
@endif

<div class="um-card">
    <table class="um-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Username</th>
                <th>Role</th>
                <th>Unit Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>
                    <div class="um-user-cell">
                        <div class="um-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div>
                            <div class="um-user-name">{{ $user->name }}</div>
                            @if($user->id === auth()->id())
                            <div class="um-user-sub" style="color:#4f7ef8;">(Akun Anda)</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="font-family: monospace; font-size: 13px;">{{ $user->username }}</td>
                <td>
                    @php
                        $roleClass = match($user->role) {
                            'super_admin'     => 'role-super',
                            'kepala_bagian'   => 'role-kepala',
                            'pengurus_barang' => 'role-pengurus',
                            default           => '',
                        };
                        $roleLabel = match($user->role) {
                            'super_admin'     => 'Super Admin',
                            'kepala_bagian'   => 'Kepala Bagian',
                            'pengurus_barang' => 'Pengurus Barang',
                            default           => $user->role,
                        };
                    @endphp
                    <span class="um-role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                </td>
                <td>{{ $user->unitKerja?->nama_unit ?? '—' }}</td>
                <td>
                    <div class="um-actions">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Hapus user {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="um-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Belum ada user yang terdaftar
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($users->hasPages())
<div style="margin-top: 16px;">
    {{ $users->links() }}
</div>
@endif

@endsection
