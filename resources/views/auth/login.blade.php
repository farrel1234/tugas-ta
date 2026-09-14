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
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="••••••••" 
                        class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-3 text-xs text-white placeholder-blue-300/40 focus:outline-none focus:border-cyan-400 transition">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-blue-200/80 hover:text-white transition">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-blue-500/40 bg-blue-950/80 text-cyan-400 focus:ring-0">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-cyan-400 to-blue-500 hover:from-cyan-300 hover:to-blue-400 text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg transition duration-200 cursor-pointer">
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

    <div class="text-center mt-6 text-[11px] text-blue-300/50 relative z-10">
        Default: <span class="text-cyan-300">admin@polibatam.ac.id</span> | Password: <span class="text-cyan-300">admin123</span>
    </div>

</div>
@endsection