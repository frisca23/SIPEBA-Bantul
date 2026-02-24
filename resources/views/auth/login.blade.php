<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEBA - Sistem Informasi Persediaan Barang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-start: #003399;
            --bg-mid: #1a3f8a;
            --bg-end: #4f7ef8;
            --card: #ffffff;
            --ink: #1a2b4b;
            --muted: #6b7280;
            --accent: #4f7ef8;
            --accent-dark: #003399;
            --border: #e5e9f2;
            --shadow: 0 30px 70px rgba(0, 34, 102, 0.2);
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(1200px 600px at 10% 10%, rgba(79, 126, 248, 0.18), transparent 60%),
                        radial-gradient(900px 500px at 90% 80%, rgba(0, 51, 153, 0.2), transparent 55%),
                        linear-gradient(135deg, var(--bg-start), var(--bg-mid) 45%, var(--bg-end));
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            color: var(--ink);
        }

        .auth-shell {
            width: min(1000px, 100%);
            position: relative;
        }

        .auth-card {
            background: var(--card);
            border-radius: 24px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: rise 0.7s ease;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-left {
            padding: 36px 34px;
            background: linear-gradient(145deg, #f4f7fb, #ffffff);
            position: relative;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            inset: 18px;
            border-radius: 18px;
            border: 1px dashed #dbe5f1;
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f7ef8, #003399);
            display: grid;
            place-items: center;
            box-shadow: 0 12px 22px rgba(79, 126, 248, 0.3);
        }

        .brand-title {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .brand-title strong {
            font-size: 18px;
            letter-spacing: 2px;
        }

        .brand-title span {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.5px;
        }

        .hero-illust {
            margin-top: 22px;
            background: #f5f8fc;
            border-radius: 18px;
            padding: 18px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .hero-illust svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .auth-right {
            padding: 40px 38px;
            background: #ffffff;
        }

        .auth-top {
            display: flex;
            justify-content: flex-end;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 26px;
        }

        .form-title {
            font-size: 24px;
            margin: 0 0 6px;
        }

        .form-subtitle {
            margin: 0 0 20px;
            color: var(--muted);
            font-size: 13px;
        }

        .alert {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .alert ul {
            margin: 6px 0 0 0;
            padding-left: 16px;
        }

        .form-group {
            display: grid;
            gap: 6px;
            margin-bottom: 14px;
        }

        label {
            font-size: 12px;
            color: #3b4a5f;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            font-size: 14px;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(29, 154, 160, 0.12);
        }

        .form-hint {
            font-size: 12px;
            color: #b42318;
            margin: 0;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 10px 0 18px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #2d3d52;
            font-weight: 600;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
        }

        .btn-primary {
            width: 100%;
            border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            box-shadow: 0 12px 24px rgba(0, 51, 153, 0.28);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(0, 51, 153, 0.32);
        }

        .login-meta {
            margin-top: 22px;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        .login-meta span {
            font-weight: 700;
            color: #1f3c58;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 900px) {
            .auth-card {
                grid-template-columns: 1fr;
            }

            .auth-left {
                order: 2;
            }

            .auth-right {
                order: 1;
            }

            .auth-top {
                justify-content: flex-start;
            }
        }

        @media (max-width: 520px) {
            .auth-left,
            .auth-right {
                padding: 28px 22px;
            }

            .hero-copy h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <section class="auth-left">
                <div class="brand">
                    <div class="brand-mark" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2.5L3.5 7v10l8.5 4.5L20.5 17V7L12 2.5Z" stroke="#ffffff" stroke-width="1.5" />
                            <path d="M3.8 7.2L12 11.5l8.2-4.3" stroke="#ffffff" stroke-width="1.5" />
                            <path d="M12 11.5V22" stroke="#ffffff" stroke-width="1.5" />
                        </svg>
                    </div>
                    <div class="brand-title">
                        <strong>SIPEBA</strong>
                        <span>Persediaan Barang</span>
                    </div>
                </div>

                <div class="hero-illust" aria-hidden="true">
                    <svg viewBox="0 0 520 300" fill="none">
                        <rect x="24" y="66" width="190" height="150" rx="16" fill="#e6f0f6" />
                        <rect x="56" y="92" width="120" height="24" rx="10" fill="#cde1ec" />
                        <rect x="56" y="130" width="120" height="24" rx="10" fill="#cde1ec" />
                        <rect x="56" y="168" width="120" height="24" rx="10" fill="#cde1ec" />
                        <rect x="250" y="40" width="220" height="200" rx="18" fill="#f1f6fb" />
                        <rect x="274" y="70" width="172" height="40" rx="12" fill="#d9e9f3" />
                        <rect x="274" y="126" width="172" height="40" rx="12" fill="#d9e9f3" />
                        <rect x="274" y="182" width="120" height="40" rx="12" fill="#4f7ef8" opacity="0.15" />
                        <path d="M70 238h390" stroke="#d7e3ee" stroke-width="8" stroke-linecap="round" />
                        <circle cx="150" cy="238" r="18" fill="#4f7ef8" />
                        <path d="M144 238l4 4 8-10" stroke="#ffffff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                        <rect x="370" y="220" width="64" height="54" rx="10" fill="#003399" opacity="0.2" />
                        <rect x="386" y="233" width="32" height="30" rx="6" fill="#003399" opacity="0.35" />
                    </svg>
                </div>
            </section>

            <section class="auth-right">
                <div class="auth-top">Butuh bantuan? Hubungi admin unit kerja.</div>

                <h2 class="form-title">Selamat datang di SIPEBA</h2>
                <p class="form-subtitle">Masukkan akun Anda untuk melanjutkan pengelolaan persediaan.</p>

                @if ($errors->any())
                    <div class="alert">
                        <strong>Login gagal.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            required
                            autocomplete="username"
                        >
                        @error('username')
                            <p class="form-hint">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <p class="form-hint">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="remember">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn-primary">Masuk</button>
                </form>

                <div class="login-meta">
                    <span>Tip:</span> Gunakan akun unit kerja resmi untuk mencatat penerimaan dan pengurangan barang.
                </div>
            </section>
        </div>

        <div class="footer">
            2026 Setda Kabupaten Bantul - Manajemen Persediaan Barang
        </div>
    </div>
</body>
</html>



