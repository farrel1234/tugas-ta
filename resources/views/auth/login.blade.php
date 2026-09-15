@extends('layouts.main')

@section('title', 'Login Administrator - Signage Perpustakaan')

@section('content')
<div class="min-h-screen bg-[#071124] text-white flex flex-col justify-center items-center p-4 relative overflow-hidden font-sans select-none">
    
    <!-- Background Ornaments -->
    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-blue-500/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-cyan-500/15 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-[#0B1E3F]/80 backdrop-blur-xl border border-blue-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl relative z-10">
        
        <!-- Header / Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-900/60 rounded-2xl border border-blue-500/30 flex items-center justify-center p-2.5 mx-auto mb-4 shadow-inner">
                <img src="{{ asset('templating/img/polibatam.png') }}" alt="Logo Polibatam" class="w-full h-full object-contain">
            </div>
            <span class="text-[10px] text-cyan-400 font-extrabold tracking-widest uppercase block mb-1">UPA Perpustakaan Polibatam</span>
            <h1 class="text-2xl font-black tracking-tight text-white uppercase">Login Admin</h1>
            <p class="text-xs text-blue-200/70 mt-1">Masuk untuk mengelola konten dan alur Digital Signage Kiosk.</p>
        </div>

        <!-- Alert Status Logout / Error -->
        @if (session('status'))
            <div class="mb-5 p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Email Admin</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@polibatam.ac.id" 
                        class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-3 text-xs text-white placeholder-blue-300/40 focus:outline-none focus:border-cyan-400 transition">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" required placeholder="••••••••" 
                        class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl pl-4 pr-11 py-3 text-xs text-white placeholder-blue-300/40 focus:outline-none focus:border-cyan-400 transition">
                    
                    <button type="button" id="toggle-password" title="Tampilkan / Sembunyikan Kata Sandi" class="absolute right-3 text-blue-300/60 hover:text-cyan-300 transition p-1 cursor-pointer focus:outline-none">
                        <!-- Ikon Mata Terbuka (saat password bertipe password) -->
                        <svg id="eye-open-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Ikon Mata Tertutup / Dicoret (saat password terlihat sebagai teks) -->
                        <svg id="eye-closed-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-blue-200/80 hover:text-white transition">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-blue-500/40 bg-blue-950/80 text-cyan-400 focus:ring-0">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-cyan-500/20 border border-cyan-400/30 transition duration-200 cursor-pointer"
                style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="mt-6 text-center border-t border-blue-800/40 pt-4">
            <a href="{{ url('/') }}" class="text-xs text-cyan-400 hover:text-cyan-300 transition flex items-center justify-center gap-1.5 font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Layar Kiosk
            </a>
        </div>

    </div>

</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const eyeOpenIcon = document.getElementById('eye-open-icon');
    const eyeClosedIcon = document.getElementById('eye-closed-icon');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            if (eyeOpenIcon && eyeClosedIcon) {
                eyeOpenIcon.classList.toggle('hidden', isPassword);
                eyeClosedIcon.classList.toggle('hidden', !isPassword);
            }
        });
    }
</script>
@endsection