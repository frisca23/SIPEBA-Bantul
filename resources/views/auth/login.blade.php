@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 80px auto;">
    <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #003399; margin-bottom: 30px;">SIPEBA - Login</h2>

        @if ($errors->any())
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                <strong>Login Gagal!</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                @error('username')
                    <small style="color: #c33;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                @error('password')
                    <small style="color: #c33;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span style="margin-left: 8px; color: #666;">Ingat saya</span>
                </label>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: #003399; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
                Login
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666; font-size: 14px;">
            <p><strong>Akun Test:</strong></p>
            <p style="margin: 5px 0;">Username: <strong>admin</strong> | Password: <strong>password</strong></p>
            <p style="margin: 5px 0;">Username: <strong>kepala_1</strong> | Password: <strong>password</strong></p>
            <p style="margin: 5px 0;">Username: <strong>pengurus_1</strong> | Password: <strong>password</strong></p>
        </div>
    </div>
</div>
@endsection
