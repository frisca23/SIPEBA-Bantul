<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEBA - Sistem Informasi Persediaan Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', 'Poppins', sans-serif;
        }
        
        h1, h2, h3, .font-display {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .bg-warehouse {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                              url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><linearGradient id="grad" x1="0%25" y1="0%25" x2="100%25" y2="100%25"><stop offset="0%25" style="stop-color:%235b7cb8;stop-opacity:1" /><stop offset="100%25" style="stop-color:%233a5a8f;stop-opacity:1" /></linearGradient></defs><rect width="1200" height="800" fill="url(%23grad)"/><g opacity="0.1"><rect x="100" y="100" width="200" height="300" fill="white"/><rect x="400" y="150" width="180" height="350" fill="white"/><rect x="700" y="120" width="220" height="320" fill="white"/><path d="M 50 500 L 1150 500 L 1150 600 Q 600 650 50 600 Z" fill="white"/></g></svg>');
            background-size: cover;
            background-position: center;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .glass-effect:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            outline: none;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        }

        .btn-solid {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-solid:hover {
            background: linear-gradient(135deg, #163050 0%, #254a75 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(30, 58, 95, 0.5);
        }

        .btn-solid:active {
            transform: translateY(0);
        }

        .btn-solid::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-solid:hover::before {
            left: 100%;
        }

        .text-shadow {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shake {
            animation: shake 0.3s ease-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.08) inset !important;
            -webkit-text-fill-color: white !important;
        }
    </style>
</head>
<body class="bg-warehouse flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-sm">
        <!-- Main Glass Card -->
        <div class="glass-effect rounded-2xl p-6 md:p-7 shadow-2xl fade-in">
            
            <!-- Logo Section -->
            <div class="mb-5 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-2 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg">
                    <i class="fas fa-cube text-white text-lg"></i>
                </div>
                <h3 class="text-xs font-bold text-white tracking-widest text-shadow uppercase mb-0.5">
                    Sistem Informasi
                </h3>
                <h2 class="text-lg md:text-xl font-bold text-white text-shadow mb-0.5">
                    PERSEDIAAN BARANG
                </h2>
                <p class="text-xs md:text-sm text-blue-100 text-shadow">
                    Sekretariat Daerah Kabupaten Bantul
                </p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="shake mb-4 bg-red-500/20 border border-red-400/40 rounded-lg p-3 backdrop-blur-sm">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-200 mt-0.5 mr-2 text-sm"></i>
                        <div>
                            <h4 class="font-semibold text-red-100 text-xs mb-1">Login Gagal</h4>
                            <ul class="text-red-100 text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center">
                                        <span class="mr-1">•</span>{{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-3">
                @csrf

                <!-- Username Input -->
                <div class="relative">
                    <div class="flex items-center">
                        <i class="fas fa-user absolute left-3 text-white/60 text-sm pointer-events-none"></i>
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="Username"
                            value="{{ old('username') }}" 
                            required 
                            class="input-glass w-full pl-10 pr-3 py-2 rounded-lg text-white font-medium transition duration-300 text-sm"
                            autocomplete="username"
                        >
                    </div>
                    @error('username')
                        <p class="text-red-200 text-xs mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="relative">
                    <div class="flex items-center">
                        <i class="fas fa-lock absolute left-3 text-white/60 text-sm pointer-events-none"></i>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Password"
                            required 
                            class="input-glass w-full pl-10 pr-3 py-2 rounded-lg text-white font-medium transition duration-300 text-sm"
                            autocomplete="current-password"
                        >
                    </div>
                    @error('password')
                        <p class="text-red-200 text-xs mt-1 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-blue-400 cursor-pointer"
                        >
                        <span class="ml-2 text-white/80 text-xs font-medium group-hover:text-white transition">
                            Ingat saya
                        </span>
                    </label>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="btn-solid w-full py-2 px-3 rounded-lg text-white font-bold uppercase tracking-wide text-xs md:text-sm shadow-lg border border-white/20 mt-4 group"
                >
                    <span class="flex items-center justify-center">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                    </span>
                </button>
            </form>

            <!-- Test Accounts Section -->
            <div class="mt-5 pt-4 border-t border-white/10">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-info-circle text-blue-200 mr-1 text-xs"></i>
                    <h4 class="text-xs font-bold text-white/90 uppercase tracking-wide">
                        Akun Demonstrasi
                    </h4>
                </div>
                <div class="space-y-1.5">
                    <div class="glass-effect rounded-lg p-2 hover:bg-white/12 transition">
                        <p class="text-xs text-white/80">
                            <span class="font-semibold text-blue-100">Admin:</span>
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">admin</code> / 
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">password</code>
                        </p>
                    </div>
                    <div class="glass-effect rounded-lg p-2 hover:bg-white/12 transition">
                        <p class="text-xs text-white/80">
                            <span class="font-semibold text-blue-100">Kepala:</span>
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">kepala_1</code> / 
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">password</code>
                        </p>
                    </div>
                    <div class="glass-effect rounded-lg p-2 hover:bg-white/12 transition">
                        <p class="text-xs text-white/80">
                            <span class="font-semibold text-blue-100">Pengurus:</span>
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">pengurus_1</code> / 
                            <code class="bg-black/30 px-1.5 py-0.5 rounded text-blue-100 font-mono text-xs">password</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-4 md:mt-5 text-center">
            <p class="text-white/70 text-xs font-medium text-shadow">
                <i class="fas fa-copyright mr-1"></i> 2026 Setda Kabupaten Bantul - Manajemen Aset
            </p>
        </div>
    </div>
</body>
</html>



