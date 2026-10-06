@extends('layouts.main')

@section('title', 'Layar Digital Signage - UPA Perpustakaan Polibatam')

@section('content')
<style>
    @keyframes marquee {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-200vw);
        }
    }
    .animate-marquee {
        animation: marquee 20s linear infinite;
    }
    @keyframes popupEntrance {
        0% {
            opacity: 0;
            transform: scale3d(0.88, 0.88, 1) translateY(16px);
        }
        100% {
            opacity: 1;
            transform: scale3d(1, 1, 1) translateY(0);
        }
    }
    @keyframes popupDismiss {
        0% {
            opacity: 1;
            transform: scale3d(1, 1, 1) translateY(0);
        }
        100% {
            opacity: 0;
            transform: scale3d(0.93, 0.93, 1) translateY(10px);
        }
    }
    .popup-card-enter {
        animation: popupEntrance 260ms cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        will-change: transform, opacity;
    }
    .popup-card-exit {
        animation: popupDismiss 200ms cubic-bezier(0.4, 0, 1, 1) forwards !important;
        will-change: transform, opacity;
    }
    #scanPopup {
        will-change: opacity;
        transition: opacity 220ms ease-out;
    }
    #popupCard {
        transform: translateZ(0);
        backface-visibility: hidden;
        contain: paint;
    }
    #popupCountdown {
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
    }
    @keyframes popupCountdownTranslate {
        0% {
            transform: translate3d(0, 0, 0);
        }
        100% {
            transform: translate3d(-100%, 0, 0);
        }
    }
    .popup-countdown-running {
        animation: popupCountdownTranslate 3000ms linear forwards !important;
    }

    /* Database Cylinder Styles & Animations */
    .db-cylinder-container {
        perspective: 900px;
    }
    .db-cylinder-body {
        position: relative;
        background: linear-gradient(180deg, rgba(6, 182, 212, 0.08) 0%, rgba(11, 30, 63, 0.6) 100%);
        border-left: 2px solid rgba(6, 182, 212, 0.5);
        border-right: 2px solid rgba(6, 182, 212, 0.5);
        border-bottom: 2px solid rgba(6, 182, 212, 0.5);
        border-radius: 0 0 36px 36px;
        box-shadow: inset 0 0 25px rgba(6, 182, 212, 0.15), 0 10px 30px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }
    .db-cylinder-cap {
        position: absolute;
        top: -14px;
        left: 0;
        right: 0;
        height: 28px;
        border-radius: 50%;
        background: radial-gradient(ellipse at center, rgba(34, 211, 238, 0.35) 0%, rgba(6, 182, 212, 0.15) 60%, rgba(11, 30, 63, 0.95) 100%);
        border: 2px solid rgba(6, 182, 212, 0.7);
        box-shadow: 0 2px 12px rgba(6, 182, 212, 0.35), inset 0 0 8px rgba(34, 211, 238, 0.5);
        z-index: 10;
    }
    .db-cylinder-rib {
        position: absolute;
        left: 0;
        right: 0;
        height: 18px;
        border-radius: 50%;
        border-bottom: 2px solid rgba(6, 182, 212, 0.3);
        pointer-events: none;
        z-index: 8;
    }
    .db-cylinder-rib-1 { top: 32%; }
    .db-cylinder-rib-2 { top: 64%; }

    /* Animated Wave */
    .db-wave-wrapper {
        position: absolute;
        top: -8px;
        left: 0;
        width: 200%;
        height: 12px;
        overflow: hidden;
        animation: waveHorizontal 6s linear infinite;
        z-index: 6;
        pointer-events: none;
    }
    @keyframes waveHorizontal {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* Floating Bubbles */
    .db-bubble {
        position: absolute;
        bottom: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: bubbleRise 3.5s infinite ease-in;
    }
    @keyframes bubbleRise {
        0% { transform: translateY(0) scale(0.4); opacity: 0; }
        20% { opacity: 0.7; }
        80% { opacity: 0.6; }
        100% { transform: translateY(-300px) scale(1.1); opacity: 0; }
    }

    /* Full Capacity Aura */
    .tank-full-aura {
        animation: pulseFullAura 2s infinite ease-in-out;
    }
    @keyframes pulseFullAura {
        0%, 100% {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.4), inset 0 0 15px rgba(6, 182, 212, 0.2);
            border-color: rgba(6, 182, 212, 0.8);
        }
        50% {
            box-shadow: 0 0 50px rgba(16, 185, 129, 0.7), 0 0 80px rgba(6, 182, 212, 0.5), inset 0 0 30px rgba(16, 185, 129, 0.4);
            border-color: rgba(52, 211, 153, 1);
        }
    }

    /* Dynamic Kiosk Carousel Widgets (Traffic Tank & Leaderboard) */
    .dynamic-kiosk-widget {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 1.5s ease-in-out;
        pointer-events: none;
    }
    .dynamic-kiosk-widget.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
</style>
<div class="bg-[#071124] text-white antialiased select-none h-screen w-screen flex flex-col overflow-hidden">

    <div class="w-full h-full flex flex-col justify-between p-3 sm:p-6 relative bg-gradient-to-br from-[#071124] via-[#0B1E3F] to-[#0D234A] overflow-hidden">

        <!-- Background Ornaments -->
        <div class="absolute top-0 left-1/4 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-blue-500/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-cyan-500/15 rounded-full blur-[120px] pointer-events-none"></div>

        <!-- Header -->
        <header class="flex flex-col lg:flex-row justify-between items-center bg-blue-950/50 backdrop-blur-lg border border-blue-500/30 px-3 sm:px-8 py-2 sm:py-3 rounded-2xl sm:rounded-3xl shadow-2xl relative z-10 flex-shrink-0 gap-3 sm:gap-4">
            <div class="flex items-center gap-3 text-center lg:text-left flex-col sm:flex-row w-full lg:w-auto justify-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-900/60 rounded-xl sm:rounded-2xl border border-blue-500/30 flex items-center justify-center p-1.5 sm:p-2 shadow-inner flex-shrink-0">
                    <img src="{{ asset('templating/img/polibatam.png') }}" alt="Logo Polibatam" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center justify-center sm:justify-start gap-1.5 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-ping"></span>
                        <span class="text-[9px] sm:text-[10px] text-cyan-300 font-extrabold tracking-widest uppercase">Selamat Datang di</span>
                    </div>
                    <h1 class="text-sm sm:text-xl font-black tracking-tight text-white uppercase">UPA Perpustakaan Polibatam</h1>
                    <span class="text-[8px] sm:text-[9px] text-cyan-400 font-semibold tracking-wider block">Digital Service Kiosk</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-4 w-full lg:w-auto">
                <div class="flex items-center gap-2 sm:gap-3 bg-blue-900/60 px-2.5 sm:px-3 py-1.5 rounded-xl sm:rounded-2xl border border-blue-500/30 shadow-inner">
                    <div class="w-6 h-6 sm:w-7 sm:h-7 bg-blue-900 rounded-lg sm:rounded-xl flex items-center justify-center text-cyan-300 border border-blue-500/30">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="text-left">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest text-cyan-300">Jam Operasional</span>
                            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[7px] sm:text-[8px] font-extrabold px-1.5 py-0.5 rounded uppercase">BUKA</span>
                        </div>
                        <span class="text-[9px] sm:text-[11px] font-bold text-white">Senin - Jumat | 08:00 WIB</span>
                    </div>
                </div>

                <div id="live-clock" class="text-[10px] sm:text-xs font-bold text-cyan-200 bg-blue-900/70 px-3 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl border border-blue-500/30 shadow-inner flex-shrink-0">
                    08:00 WIB
                </div>

                <!-- Akses Cepat ke Admin Panel -->
                <a href="{{ route('admin.dashboard') }}" title="Masuk ke Panel Admin" class="text-[10px] sm:text-xs text-blue-200 hover:text-cyan-300 bg-blue-900/70 hover:bg-blue-900 px-3 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl border border-blue-500/30 shadow-inner flex items-center gap-1.5 transition flex-shrink-0 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="hidden sm:inline font-bold">Admin</span>
                </a>
            </div>
                </header>

        <!-- FITUR 1: Animasi Running Text (Ticker) -->
        <div class="w-full bg-[#060B19]/80 border-y border-slate-700/50 flex items-center relative z-10 my-2 overflow-hidden shadow-lg h-10 sm:h-12 flex-shrink-0">
            <!-- Ikon Toa Kiri (Statis) -->
            <div class="h-full px-4 flex items-center justify-center bg-[#060B19] border-r border-slate-700/50 z-20 shadow-[10px_0_15px_-3px_rgba(0,0,0,0.5)]">
                <span class="text-cyan-400 text-lg sm:text-xl">📢</span>
            </div>
            <!-- Area Teks Berjalan -->
            <div class="flex-1 overflow-hidden relative h-full flex items-center">
                <div class="whitespace-nowrap absolute left-full text-cyan-200 font-semibold tracking-wide text-xs sm:text-sm animate-marquee">
                    Selamat datang di UPA Perpustakaan Politeknik Negeri Batam. 📢 Silakan tap KTP/KTM Anda pada Gate Scanner sebelum memasuki area perpustakaan. 🕒 Jam operasional layanan: Senin - Jumat, pukul 08.00 - 17.00 WIB. 📚 Gunakan fasilitas Layanan Sirkulasi Mandiri untuk kemudahan peminjaman. 🤫 Mohon jaga ketenangan ruang baca demi kenyamanan bersama.
                </div>
            </div>
        </div>

        <!-- Main Content Area: Bersebelahan Panduan Layanan & Traffic Tank Silinder -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 flex-grow min-h-0 overflow-hidden relative z-10 my-1 sm:my-2">

            <!-- Kolom Kiri: Panduan Layanan (Rotator Signage) -->
            <main id="spotlight-rotator" class="lg:col-span-7 xl:col-span-7 flex flex-col justify-between rounded-2xl sm:rounded-3xl bg-blue-950/30 backdrop-blur-lg border border-blue-500/25 shadow-2xl p-3 sm:p-5 overflow-hidden transition-all duration-700 h-full min-h-0">

                <!-- Title & Description Info -->
                <div class="mb-2 border-b border-blue-800/50 pb-2 sm:pb-2.5 flex-shrink-0">
                    <div class="flex items-start sm:items-center justify-between mb-1 gap-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span id="menu-number" class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-tr from-cyan-400 to-blue-500 text-white font-black text-base sm:text-lg rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="color: #ffffff !important;">
                                01
                            </span>
                            <div>
                                <span id="menu-category" class="text-[8px] sm:text-[9px] font-bold uppercase tracking-widest text-cyan-300 block">Panduan Layanan</span>
                                <h2 id="menu-title" class="text-xs sm:text-lg font-black text-white tracking-tight capitalize leading-tight">
                                    Memuat Data Signage...
                                </h2>
                            </div>
                        </div>
                        <span id="menu-badge" class="text-[8px] sm:text-[9px] font-semibold bg-blue-900/60 text-cyan-300 border border-blue-500/30 px-2 py-1 rounded-full uppercase tracking-wider hidden sm:inline-block flex-shrink-0">
                            Menu Utama & Alur
                        </span>
                    </div>
                    <p id="menu-desc" class="text-[10px] sm:text-xs text-blue-100/90 font-light leading-relaxed hidden sm:block">
                        Silakan tunggu sejenak, informasi perpustakaan sedang dimuat.
                    </p>
                </div>

                <!-- Kontainer Single Slide -->
                <div id="steps-container" class="flex-grow min-h-0 flex flex-col items-center justify-center w-full h-full">
                    <!-- Konten akan dirender oleh JavaScript -->
                </div>

            </main>

            <!-- Kolom Kanan: Container Carousel Dinamis (Traffic Tank Silinder & Leaderboard Gamifikasi) -->
            <section id="kiosk-traffic-tank" class="lg:col-span-5 xl:col-span-5 rounded-2xl sm:rounded-3xl bg-blue-950/40 backdrop-blur-lg border border-blue-500/30 shadow-2xl overflow-hidden h-full min-h-0 relative">

                @php
                    $kioskTopList = collect($leaderboardData['top_visitors'] ?? []);
                    $top1 = $kioskTopList->firstWhere('rank', 1);
                    $top2 = $kioskTopList->firstWhere('rank', 2);
                    $top3 = $kioskTopList->firstWhere('rank', 3);
                @endphp

                <!-- WIDGET 1: Traffic Tank Silinder (Aktif Default) -->
                <div id="widgetTank" class="dynamic-kiosk-widget active p-3.5 sm:p-5 flex flex-col justify-between">
                    <!-- Header Box Card Tangki (Atas) -->
                    <div class="w-full flex items-center justify-between border-b border-blue-800/40 pb-2.5 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-cyan-200">Traffic Tank Silinder</h3>
                            <span id="tank-mode-badge" class="text-[9px] font-mono font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Live Hari Ini
                            </span>
                        </div>
                        <span id="tank-capacity-badge" class="text-[10px] sm:text-xs font-mono font-bold px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                            {{ $trafficData['capacity_percentage'] ?? 0 }}% Kapasitas
                        </span>
                    </div>

                    <!-- Area Tengah: Silinder 3D & HUD Total Kunjungan (Tepat di Tengah / Centered Vertikal) -->
                    <div class="flex-1 flex flex-col justify-center items-center my-auto py-2 sm:py-4 min-h-0 w-full">
                        <!-- 3D Database Cylinder Graphic -->
                        <div class="relative w-[210px] sm:w-[240px] xl:w-[270px] h-[230px] sm:h-[260px] xl:h-[300px] db-cylinder-container mx-auto">
                            <!-- Cylinder Top Oval Cap -->
                            <div class="db-cylinder-cap"></div>

                            <!-- Cylinder Body -->
                            <div id="tank-cylinder-element" class="db-cylinder-body w-full h-full relative transition-all duration-500">

                                <!-- Database Horizontal Segment Ribs -->
                                <div class="db-cylinder-rib db-cylinder-rib-1"></div>
                                <div class="db-cylinder-rib db-cylinder-rib-2"></div>

                                <!-- Floating Rising Bubbles -->
                                <div class="db-bubble" style="left: 20%; width: 6px; height: 6px; animation-delay: 0s; animation-duration: 3.2s;"></div>
                                <div class="db-bubble" style="left: 45%; width: 8px; height: 8px; animation-delay: 1.2s; animation-duration: 4s;"></div>
                                <div class="db-bubble" style="left: 70%; width: 5px; height: 5px; animation-delay: 2.1s; animation-duration: 2.8s;"></div>
                                <div class="db-bubble" style="left: 85%; width: 7px; height: 7px; animation-delay: 0.7s; animation-duration: 3.6s;"></div>

                                <!-- The Liquid Level Container (anchored to bottom) -->
                                <div id="traffic-tank-liquid" class="absolute bottom-0 left-0 right-0 transition-all duration-1000 ease-out flex flex-col-reverse overflow-visible" style="height: {{ ($trafficData['total'] ?? 0) > 0 ? min(100, round(14 + (($trafficData['capacity_percentage'] ?? 0) * 0.72))) : 0 }}%;">

                                    <!-- Stacked Colored Fluid Layers: Bottom is Red, Middle is Gray, Top is Green -->
                                    <!-- Red Layer (Belum Daftar) -->
                                    <div id="tank-layer-red" class="w-full bg-gradient-to-t from-rose-700 via-rose-600 to-rose-500 transition-all duration-700 relative border-t border-rose-400/30" style="flex: {{ $trafficData['status_percentages']['belum_daftar'] ?? 0 }} 1 0%; min-height: {{ ($trafficData['status_percentages']['belum_daftar'] ?? 0) > 0 ? '4px' : '0px' }}; display: {{ ($trafficData['status_percentages']['belum_daftar'] ?? 0) > 0 ? 'block' : 'none' }};"></div>
                                    <!-- Gray Layer (Belum Perpanjang) -->
                                    <div id="tank-layer-gray" class="w-full bg-gradient-to-t from-slate-700 via-slate-600 to-slate-500 transition-all duration-700 relative border-t border-slate-300/30" style="flex: {{ $trafficData['status_percentages']['belum_perpanjang'] ?? 0 }} 1 0%; min-height: {{ ($trafficData['status_percentages']['belum_perpanjang'] ?? 0) > 0 ? '4px' : '0px' }}; display: {{ ($trafficData['status_percentages']['belum_perpanjang'] ?? 0) > 0 ? 'block' : 'none' }};"></div>
                                    <!-- Green Layer (Bisa Masuk) -->
                                    <div id="tank-layer-green" class="w-full bg-gradient-to-t from-emerald-700 via-emerald-600 to-emerald-400 transition-all duration-700 relative border-t border-emerald-300/30" style="flex: {{ $trafficData['status_percentages']['bisa_masuk'] ?? 0 }} 1 0%; min-height: {{ ($trafficData['status_percentages']['bisa_masuk'] ?? 0) > 0 ? '4px' : '0px' }}; display: {{ ($trafficData['status_percentages']['bisa_masuk'] ?? 0) > 0 ? 'block' : 'none' }};"></div>

                                    <!-- Animated Wave on top of fluid -->
                                    <div class="db-wave-wrapper" style="opacity: {{ ($trafficData['total'] ?? 0) > 0 ? '1' : '0' }};">
                                        <svg class="w-full h-full text-emerald-400 fill-current opacity-80" viewBox="0 0 1200 120" preserveAspectRatio="none">
                                            <path d="M0,0 C150,90 350,-40 500,45 C650,130 900,10 1200,50 L1200,120 L0,120 Z"></path>
                                        </svg>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Total Kunjungan Display HUD (Di bawah silinder) -->
                        <div class="flex items-center justify-center mt-3 sm:mt-4 flex-shrink-0">
                            <div class="bg-[#091731]/85 backdrop-blur-md border border-cyan-500/40 px-4 sm:px-5 py-1.5 rounded-xl shadow-xl flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                <span id="traffic-tank-count-hud" class="text-xs sm:text-sm font-bold text-white font-mono tracking-wide">
                                    {{ $trafficData['total'] ?? 0 }} Kunjungan
                                </span>
                                <span class="text-cyan-400/40 font-mono">|</span>
                                <span id="traffic-tank-percent-hud" class="text-[11px] sm:text-xs font-bold text-cyan-300 font-mono">
                                    {{ $trafficData['capacity_percentage'] ?? 0 }}% Kapasitas
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Breakdown Status Pills Below Cylinder (Bawah) -->
                    <div class="w-full grid grid-cols-3 gap-2 border-t border-blue-800/40 pt-2.5 sm:pt-3 flex-shrink-0">
                        <!-- Green Stat -->
                        <div class="bg-emerald-950/40 border border-emerald-500/30 rounded-xl p-2 sm:p-2.5 text-center">
                            <div class="flex items-center justify-center gap-1 mb-0.5 sm:mb-1">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-400"></span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-emerald-300">Bisa Masuk</span>
                            </div>
                            <div id="stat-bisa-masuk-count" class="text-sm sm:text-base font-black text-white font-mono leading-none">
                                {{ $trafficData['status_counts']['bisa_masuk'] ?? 0 }}
                            </div>
                            <span id="stat-bisa-masuk-pct" class="text-[8px] sm:text-[9px] text-emerald-300/70 font-mono">
                                {{ $trafficData['status_percentages']['bisa_masuk'] ?? 0 }}%
                            </span>
                        </div>

                        <!-- Gray Stat -->
                        <div class="bg-slate-900/50 border border-slate-500/30 rounded-xl p-2 sm:p-2.5 text-center">
                            <div class="flex items-center justify-center gap-1 mb-0.5 sm:mb-1">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-slate-400"></span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-slate-300">Expired</span>
                            </div>
                            <div id="stat-belum-perpanjang-count" class="text-sm sm:text-base font-black text-white font-mono leading-none">
                                {{ $trafficData['status_counts']['belum_perpanjang'] ?? 0 }}
                            </div>
                            <span id="stat-belum-perpanjang-pct" class="text-[8px] sm:text-[9px] text-slate-300/70 font-mono">
                                {{ $trafficData['status_percentages']['belum_perpanjang'] ?? 0 }}%
                            </span>
                        </div>

                        <!-- Red Stat -->
                        <div class="bg-rose-950/40 border border-rose-500/30 rounded-xl p-2 sm:p-2.5 text-center">
                            <div class="flex items-center justify-center gap-1 mb-0.5 sm:mb-1">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-rose-400"></span>
                                <span class="text-[9px] sm:text-[10px] font-bold text-rose-300">Belum Daftar</span>
                            </div>
                            <div id="stat-belum-daftar-count" class="text-sm sm:text-base font-black text-white font-mono leading-none">
                                {{ $trafficData['status_counts']['belum_daftar'] ?? 0 }}
                            </div>
                            <span id="stat-belum-daftar-pct" class="text-[8px] sm:text-[9px] text-rose-300/70 font-mono">
                                {{ $trafficData['status_percentages']['belum_daftar'] ?? 0 }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: Statistik Pemustaka Bulan Ini -->
                <div id="widgetLeaderboard" class="dynamic-kiosk-widget p-3.5 sm:p-5 flex flex-col justify-between">
                    <!-- Header -->
                    <div class="w-full flex items-center justify-between border-b border-indigo-500/30 pb-2.5 flex-shrink-0">
                        <div class="flex items-center gap-2 sm:gap-2.5">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-gradient-to-tr from-indigo-600 via-blue-500 to-cyan-400 flex items-center justify-center text-white text-sm sm:text-base shadow-[0_0_15px_rgba(99,102,241,0.4)] flex-shrink-0">
                                📊
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-indigo-300 flex items-center gap-1.5">
                                    Statistik Pemustaka
                                </h3>
                                <span class="text-[8px] sm:text-[9px] text-indigo-200/70 font-medium block">Analitik Demografi Pengunjung</span>
                            </div>
                        </div>
                        <span id="demographic-month-badge" class="text-[9px] sm:text-xs font-mono font-bold px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">
                            Memuat...
                        </span>
                    </div>

                    <!-- Area Tengah: Konten Demografi -->
                    <div class="flex-1 w-full flex flex-col gap-3 mt-3 relative z-10 overflow-hidden">
                        
                        <!-- Rasio Gender (Split Block Raksasa) -->
                        <div class="grid grid-cols-2 gap-3 h-[90px] sm:h-[110px]">
                            <!-- Laki-laki -->
                            <div class="bg-blue-950/40 border border-blue-500/30 rounded-xl p-3 flex flex-col justify-center items-center relative overflow-hidden shadow-inner group">
                                <div class="absolute -right-2 -bottom-2 sm:-right-4 sm:-bottom-4 text-blue-500/10 text-5xl sm:text-7xl font-black group-hover:scale-110 transition-transform duration-500">👨</div>
                                <div class="flex flex-col items-center z-10 w-full">
                                    <div class="text-6xl sm:text-7xl font-black text-blue-400 font-mono tracking-tighter leading-none" id="gender-split-l-count">0</div>
                                    <div class="text-[8px] sm:text-[10px] text-blue-200/70 font-bold uppercase tracking-widest mt-2">Pengunjung Laki-laki</div>
                                </div>
                            </div>
                            <!-- Perempuan -->
                            <div class="bg-rose-950/40 border border-rose-500/30 rounded-xl p-3 flex flex-col justify-center items-center relative overflow-hidden shadow-inner group">
                                <div class="absolute -right-2 -bottom-2 sm:-right-4 sm:-bottom-4 text-rose-500/10 text-5xl sm:text-7xl font-black group-hover:scale-110 transition-transform duration-500">👩</div>
                                <div class="flex flex-col items-center z-10 w-full">
                                    <div class="text-6xl sm:text-7xl font-black text-rose-400 font-mono tracking-tighter leading-none" id="gender-split-p-count">0</div>
                                    <div class="text-[8px] sm:text-[10px] text-rose-200/70 font-bold uppercase tracking-widest mt-2">Pengunjung Perempuan</div>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Bawah: Umur & Wilayah -->
                        <div class="grid grid-cols-2 gap-3 flex-1 h-full min-h-0">
                            <!-- Top Umur (Horizontal Stat Bars) -->
                            <div class="bg-[#091731]/80 border border-indigo-500/30 rounded-xl p-3 sm:p-4 shadow-inner flex flex-col overflow-hidden">
                                <h4 class="text-[9px] sm:text-[10px] font-bold text-indigo-200 uppercase tracking-widest w-full text-center mb-4 border-b border-indigo-500/20 pb-2">
                                    🎂 Top Umur
                                </h4>
                                <div id="box-age-groups" class="flex flex-col gap-5 sm:gap-6 w-full flex-1 justify-center">
                                    <!-- dynamic content -->
                                </div>
                            </div>

                            <!-- Top Wilayah (Tipografi Style) -->
                            <div class="bg-[#091731]/80 border border-indigo-500/30 rounded-xl p-2.5 sm:p-3 shadow-inner flex flex-col overflow-hidden">
                                <h4 class="text-[9px] sm:text-[10px] font-bold text-indigo-200 uppercase tracking-widest w-full text-center mb-2">
                                    📍 Top Wilayah
                                </h4>
                                <div id="box-regions" class="flex-1 flex flex-col justify-center gap-1.5 sm:gap-2 w-full">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="w-full border-t border-indigo-500/30 pt-2 sm:pt-2.5 flex items-center justify-between gap-2 flex-shrink-0 mt-3">
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[9px] sm:text-[10px] text-indigo-200/80">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse flex-shrink-0"></span>
                            <span class="font-medium truncate">Data diperbarui secara real-time berdasarkan kunjungan.</span>
                        </div>
                    </div>
                </div>

                <!-- Floating Carousel Mini Navigator Dots -->
                <div class="absolute bottom-2 right-3 sm:right-4 z-20 flex items-center gap-1.5 bg-blue-950/85 px-2 py-1 rounded-full border border-blue-500/30 backdrop-blur-md shadow-lg">
                    <button id="dot-widget-tank" type="button" class="w-2 h-2 rounded-full transition-all duration-300 bg-cyan-400 ring-2 ring-cyan-400/40 cursor-pointer" title="Traffic Tank Silinder"></button>
                    <button id="dot-widget-leaderboard" type="button" class="w-2 h-2 rounded-full transition-all duration-300 bg-slate-500 hover:bg-amber-400 cursor-pointer" title="Leaderboard Kunjungan"></button>
                </div>

            </section>
        </div>

        <!-- Footer & Controls -->
        <footer class="flex items-center justify-between bg-blue-950/50 backdrop-blur-lg border border-blue-500/30 px-3 sm:px-8 py-2 sm:py-2.5 rounded-2xl sm:rounded-3xl shadow-lg relative z-10 flex-shrink-0 gap-2">
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-left">
                    <span class="text-[8px] sm:text-[9px] font-bold text-cyan-300 uppercase tracking-widest block">Polibatam</span>
                    <span class="text-[7px] sm:text-[8px] text-blue-200/70 block">Digital Signage System</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto justify-between sm:justify-end">
                <button id="prev-btn" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-900/80 hover:bg-cyan-400 hover:text-slate-950 text-white flex items-center justify-center transition border border-blue-500/30 shadow-lg cursor-pointer active:scale-95 flex-shrink-0" aria-label="Sebelumnya">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <div class="flex-grow sm:w-48 lg:w-72 h-2 sm:h-2.5 bg-blue-950/90 rounded-full overflow-hidden border border-blue-400/40 shadow-inner p-[1px]">
                    <div id="progress-bar" class="h-full bg-white rounded-full shadow-[0_0_10px_#ffffff,0_0_18px_rgba(255,255,255,0.95)]" style="width: 0%;"></div>
                </div>

                <button id="next-btn" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-900/80 hover:bg-cyan-400 hover:text-slate-950 text-white flex items-center justify-center transition border border-blue-500/30 shadow-lg cursor-pointer active:scale-95 flex-shrink-0" aria-label="Selanjutnya">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </footer>

        <!-- ============================================================== -->
        <!-- POPUP SCANNER AKSES PENGUNJUNG (HIJAU / ABU-ABU / MERAH)         -->
        <!-- ============================================================== -->
        <div id="scanPopup" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/85 backdrop-blur-[2px] p-4 transition-opacity duration-200" style="opacity: 0;">
            <div id="popupCardAnimWrapper" class="w-full max-w-md flex items-center justify-center">
                <div id="popupCard" class="w-full rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 relative overflow-hidden">

                    <!-- Glow background ornament -->
                    <div id="popupGlow" class="absolute -top-20 -left-20 w-44 h-44 rounded-full blur-2xl opacity-40 pointer-events-none"></div>

                    <!-- Icon Header -->
                    <div id="popupIconContainer" class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl">
                        <!-- Instant Scan Radar Icon (Reflex State) -->
                        <svg id="popupIconScan" class="w-10 h-10 sm:w-12 sm:h-12 hidden animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <!-- Checkmark Icon (Hijau) -->
                        <svg id="popupIconGreen" class="w-10 h-10 sm:w-12 sm:h-12 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <!-- Warning Exclamation Icon (Orange / Cooldown) -->
                        <svg id="popupIconOrange" class="w-10 h-10 sm:w-12 sm:h-12 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <!-- Hourglass/Warning Icon (Abu-abu) -->
                        <svg id="popupIconGray" class="w-10 h-10 sm:w-12 sm:h-12 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <!-- Cross/X Icon (Merah) -->
                        <svg id="popupIconRed" class="w-10 h-10 sm:w-12 sm:h-12 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>

                    <!-- Status Badge -->
                    <div class="inline-block mb-3">
                        <span id="popupBadge" class="text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm">
                            STATUS AKSES
                        </span>
                    </div>

                    <!-- Pesan Utama -->
                    <h2 id="popupPesan" class="text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase">
                        Silakan Masuk
                    </h2>

                    <!-- Nama Pengunjung -->
                    <div class="bg-black/40 border border-white/10 rounded-2xl py-3 px-4 my-4 backdrop-blur-sm">
                        <span class="text-[9px] sm:text-[10px] uppercase font-bold tracking-widest text-slate-300 block mb-0.5">Nama Pengunjung</span>
                        <p id="popupNama" class="text-lg sm:text-2xl font-black text-white truncate">
                            Ahmad Farhan
                        </p>
                        <span id="popupKode" class="text-[10px] text-slate-400 font-mono block mt-0.5"></span>
                    </div>

                    <p id="popupSubtext" class="text-xs sm:text-sm font-medium text-slate-200/90 leading-relaxed mb-4">
                        Pintu gerbang otomatis dibuka. Selamat datang di perpustakaan.
                    </p>

                    <!-- 3-Second Progress Countdown Bar (Hardware-Accelerated via GPU Translate3d) -->
                    <div class="w-full h-1.5 bg-black/60 rounded-full overflow-hidden border border-white/10 relative p-0">
                        <div id="popupCountdown" class="h-full w-full rounded-full" style="transform: translate3d(0, 0, 0);"></div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const clockEl = document.getElementById('live-clock');
        if (clockEl) {
            clockEl.innerText = `${hours}:${minutes} WIB`;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    function initSignage() {
        // Data diambil secara dinamis dari database melalui Controller
        const libraryData = @json($libraryData ?? []);

        if (!libraryData || libraryData.length === 0) {
            document.getElementById("menu-title").innerText = "Belum Ada Konten Menu";
            document.getElementById("menu-desc").innerText = "Silakan login ke Panel Admin untuk menambahkan menu dan slide panduan.";
            document.getElementById("steps-container").innerHTML = `
                <div class="text-center py-12 text-blue-200/60">
                    <p class="text-sm">Belum ada slide menu yang aktif di database.</p>
                    <a href="{{ route('admin.dashboard') }}" class="inline-block mt-4 px-4 py-2 rounded-xl bg-cyan-400 text-slate-950 text-xs font-bold">Buka Panel Admin</a>
                </div>
            `;
            return;
        }

        let currentIndex = 0;
        let stepIndex = 0;

        const slideDuration = 8000;
        const progressBar = document.getElementById("progress-bar");
        let timerId = null;

        function renderSlide() {
            const item = libraryData[currentIndex];
            if (!item) return;

            document.getElementById("menu-number").innerText = item.num || (currentIndex + 1);
            document.getElementById("menu-title").innerText = item.title || '';
            document.getElementById("menu-desc").innerText = item.desc || '';
            document.getElementById("menu-badge").innerText = "Menu Utama & Alur";

            const stepsToRender = (item.steps && item.steps.length > 0) ? item.steps : [
                { text: item.desc || item.title, img: '{{ asset("templating/img/polibatam.png") }}' }
            ];

            if (stepIndex >= stepsToRender.length) {
                stepIndex = 0;
            }

            const currentStepData = stepsToRender[stepIndex];
            const stepsContainer = document.getElementById("steps-container");
            const showStepBadge = stepsToRender.length > 1;

            stepsContainer.innerHTML = `
                <div class="w-full h-full flex flex-col justify-center items-center py-1 sm:py-2 animate-fade-in gap-3 sm:gap-4">

                    <!-- Gambar Presentasi -->
                    <div class="relative w-full max-w-5xl flex-1 min-h-[150px] bg-blue-900/40 rounded-2xl sm:rounded-3xl overflow-hidden border border-blue-500/30 flex items-center justify-center p-2 sm:p-4 shadow-[0_0_40px_rgba(59,130,246,0.15)] backdrop-blur-sm">

                        <img src="${currentStepData.img}" alt="Ilustrasi Langkah ${stepIndex + 1}" class="w-full h-full object-contain drop-shadow-xl rounded-xl" onerror="this.onerror=null; this.src='{{ asset('templating/img/polibatam.png') }}';">

                        <!-- Indikator Langkah -->
                        ${showStepBadge ? `
                        <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-blue-950/80 border border-blue-500/50 text-cyan-300 px-3 py-1 sm:px-4 sm:py-1.5 rounded-full text-[10px] sm:text-sm font-bold shadow-lg backdrop-blur-md">
                            Langkah ${stepIndex + 1} / ${stepsToRender.length}
                        </div>
                        ` : ''}
                    </div>

                    <!-- Teks Penjelasan -->
                    <div class="w-full max-w-5xl bg-blue-950/60 border border-blue-500/20 p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-lg backdrop-blur-md animate-slide-up text-center flex-shrink-0">
                        <p class="text-[11px] sm:text-base md:text-lg lg:text-xl text-blue-50 font-medium leading-relaxed">
                            ${currentStepData.text || ''}
                        </p>
                    </div>

                </div>
            `;
        }

        function goNextStep() {
            const item = libraryData[currentIndex];
            const stepsToRender = (item && item.steps && item.steps.length > 0) ? item.steps : [1];

            stepIndex++;

            if (stepIndex >= stepsToRender.length) {
                stepIndex = 0;
                currentIndex = (currentIndex + 1) % libraryData.length;
            }
            runSignageLoop();
        }

        function goPrevStep() {
            stepIndex--;

            if (stepIndex < 0) {
                currentIndex = (currentIndex - 1 + libraryData.length) % libraryData.length;
                const prevItem = libraryData[currentIndex];
                const prevSteps = (prevItem && prevItem.steps && prevItem.steps.length > 0) ? prevItem.steps : [1];
                stepIndex = prevSteps.length - 1;
            }
            runSignageLoop();
        }

        function runSignageLoop() {
            renderSlide();

            if (progressBar) {
                progressBar.style.transition = "none";
                progressBar.style.width = "0%";
                void progressBar.offsetWidth; // Force synchronous browser reflow
                progressBar.style.transition = `width ${slideDuration}ms linear`;
                progressBar.style.width = "100%";
            }

            if (timerId) clearTimeout(timerId);

            timerId = setTimeout(() => {
                goNextStep();
            }, slideDuration);
        }

        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");

        if (prevBtn) prevBtn.addEventListener("click", () => goPrevStep());
        if (nextBtn) nextBtn.addEventListener("click", () => goNextStep());

        // Keyboard navigasi: tombol panah kiri / kanan
        document.addEventListener("keydown", function(e) {
            if (e.key === "ArrowRight" || e.key === "PageDown") {
                goNextStep();
            } else if (e.key === "ArrowLeft" || e.key === "PageUp") {
                goPrevStep();
            }
        });

        runSignageLoop();
    }

    // =========================================================================
    // LOGIKA SCANNER AKSES PENGUNJUNG GERBANG (GATE ACCESS SCANNER)
    // =========================================================================
    let popupTimer = null;
    let scanAbortController = null;

    // Tampilkan Hasil Akses (Hijau / Abu-abu / Merah) dengan Garis Waktu Mulus Tepat 3 Detik
    function showAccessPopup(data, rawCode) {
        const popup = document.getElementById('scanPopup');
        const popupCardAnimWrapper = document.getElementById('popupCardAnimWrapper');
        const popupCard = document.getElementById('popupCard');
        const popupNama = document.getElementById('popupNama');
        const popupPesan = document.getElementById('popupPesan');
        const popupKode = document.getElementById('popupKode');
        const popupBadge = document.getElementById('popupBadge');
        const popupSubtext = document.getElementById('popupSubtext');
        const popupCountdown = document.getElementById('popupCountdown');
        const popupIconContainer = document.getElementById('popupIconContainer');
        const popupGlow = document.getElementById('popupGlow');

        const iconGreen = document.getElementById('popupIconGreen');
        const iconOrange = document.getElementById('popupIconOrange');
        const iconGray = document.getElementById('popupIconGray');
        const iconRed = document.getElementById('popupIconRed');
        const iconScan = document.getElementById('popupIconScan');

        if (!popup || !popupCard || !popupCountdown) return;

        // Bersihkan timer dismiss lama jika masih aktif
        if (popupTimer) {
            clearTimeout(popupTimer);
            popupTimer = null;
        }

        // Sembunyikan semua icon terlebih dahulu
        if (iconScan) iconScan.classList.add('hidden');
        if (iconGreen) iconGreen.classList.add('hidden');
        if (iconOrange) iconOrange.classList.add('hidden');
        if (iconGray) iconGray.classList.add('hidden');
        if (iconRed) iconRed.classList.add('hidden');

        // Buka modal jika belum terbuka atau restart animasi entrance
        if (popup.classList.contains('hidden')) {
            popup.classList.remove('hidden');
            popup.style.opacity = '0';
            requestAnimationFrame(() => {
                popup.style.opacity = '1';
            });
            if (popupCardAnimWrapper) {
                popupCardAnimWrapper.classList.remove('popup-card-exit');
                popupCardAnimWrapper.classList.add('popup-card-enter');
            }
        } else {
            popup.style.opacity = '1';
            if (popupCardAnimWrapper) {
                popupCardAnimWrapper.classList.remove('popup-card-exit');
                popupCardAnimWrapper.classList.remove('popup-card-enter');
                void popupCardAnimWrapper.offsetWidth; // Reflow untuk memicu animasi masuk kembali
                popupCardAnimWrapper.classList.add('popup-card-enter');
            }
        }

        // Update konten teks
        popupNama.innerText = data.name || 'Tidak Dikenal';
        popupPesan.innerText = data.message || 'Status Tidak Diketahui';
        popupKode.innerText = rawCode ? `ID Kartu: ${rawCode}` : '';

        if (data.color === 'green') {
            // SKENARIO HIJAU: Bisa Masuk (Aktif)
            popupCard.className = 'w-full rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 relative overflow-hidden bg-[#032315]/95 border-emerald-400 shadow-[0_0_40px_rgba(16,185,129,0.3)]';
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-2xl opacity-40 pointer-events-none bg-emerald-500';
            popupIconContainer.className = 'w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl bg-emerald-500/20 border-emerald-400 text-emerald-300';
            if (iconGreen) iconGreen.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
            popupBadge.innerText = 'AKSES DIIZINKAN';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-emerald-300';
            popupSubtext.innerText = 'Pintu gerbang otomatis dibuka. Selamat datang di perpustakaan!';
            popupCountdown.className = 'h-full w-full rounded-full bg-emerald-400';

        } else if (data.color === 'orange') {
            // SKENARIO ORANGE: Jeda Akses / Cooldown 30 Detik
            popupCard.className = 'w-full rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 relative overflow-hidden bg-[#241705]/95 border-amber-400 shadow-[0_0_40px_rgba(245,158,11,0.3)]';
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-2xl opacity-40 pointer-events-none bg-amber-500';
            popupIconContainer.className = 'w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl bg-amber-500/20 border-amber-400 text-amber-300';
            if (iconOrange) iconOrange.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-amber-500/20 text-amber-300 border-amber-500/40';
            popupBadge.innerText = 'JEDA AKSES (COOLDOWN)';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-amber-300';
            popupSubtext.innerText = data.message || 'Kartu baru saja digunakan. Tunggu 30 detik sebelum scan kembali.';
            popupCountdown.className = 'h-full w-full rounded-full bg-amber-400';

        } else if (data.color === 'gray') {
            // SKENARIO ABU-ABU: Belum Perpanjang (Kedaluwarsa)
            popupCard.className = 'w-full rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 relative overflow-hidden bg-[#1a212d]/95 border-slate-400 shadow-[0_0_40px_rgba(148,163,184,0.25)]';
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-2xl opacity-40 pointer-events-none bg-slate-400';
            popupIconContainer.className = 'w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl bg-slate-500/20 border-slate-400 text-slate-300';
            if (iconGray) iconGray.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-slate-500/20 text-slate-300 border-slate-500/40';
            popupBadge.innerText = 'KEANGGOTAAN KEDALUWARSA';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-slate-200';
            popupSubtext.innerText = 'Masa aktif kartu Anda telah habis. Silakan perpanjang masa pinjam di meja sirkulasi.';
            popupCountdown.className = 'h-full w-full rounded-full bg-slate-300';

        } else {
            // SKENARIO MERAH: Belum Daftar (Data tidak ditemukan)
            popupCard.className = 'w-full rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 relative overflow-hidden bg-[#2d0912]/95 border-rose-500 shadow-[0_0_40px_rgba(244,63,94,0.3)]';
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-2xl opacity-40 pointer-events-none bg-rose-500';
            popupIconContainer.className = 'w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl bg-rose-500/20 border-rose-500 text-rose-300';
            if (iconRed) iconRed.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-rose-500/20 text-rose-300 border-rose-500/40';
            popupBadge.innerText = 'AKSES DITOLAK';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-rose-400';
            popupSubtext.innerText = 'Kartu belum terdaftar dalam sistem perpustakaan. Silakan hubungi petugas di meja depan.';
            popupCountdown.className = 'h-full w-full rounded-full bg-rose-500';
        }

        // Jalankan animasi garis countdown super mulus tepat 3 detik (3000ms) murni via GPU compositor
        popupCountdown.classList.remove('popup-countdown-running');
        popupCountdown.style.transform = 'translate3d(0, 0, 0)';
        void popupCountdown.offsetWidth; // Force synchronous browser reflow
        popupCountdown.classList.add('popup-countdown-running');

        // Tutup otomatis tepat setelah 3 detik (3000ms)
        popupTimer = setTimeout(() => {
            if (popupCardAnimWrapper) {
                popupCardAnimWrapper.classList.remove('popup-card-enter');
                popupCardAnimWrapper.classList.add('popup-card-exit');
            }
            popup.style.opacity = '0';
            setTimeout(() => {
                popup.classList.add('hidden');
                if (popupCardAnimWrapper) {
                    popupCardAnimWrapper.classList.remove('popup-card-exit');
                }
                popupCountdown.classList.remove('popup-countdown-running');
                popupCountdown.style.transform = 'translate3d(0, 0, 0)';
            }, 200);
        }, 3000);
    }

    // Eksekusi Scan ke Backend
    function handleScan(kodeDariScanner) {
        if (!kodeDariScanner || !kodeDariScanner.trim()) return;

        const cleanCode = kodeDariScanner.trim();

        // Batalkan request fetch sebelumnya jika masih berjalan
        if (scanAbortController) {
            scanAbortController.abort();
        }
        scanAbortController = new AbortController();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/api/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ kode_scan: cleanCode }),
            signal: scanAbortController.signal
        })
        .then(response => {
            if (!response.ok && response.status !== 400) {
                throw new Error('Gagal menghubungi server');
            }
            return response.json();
        })
        .then(data => {
            showAccessPopup(data, cleanCode);
            // Refresh data tangki segera setelah scan berhasil tercatat
            setTimeout(fetchKioskLiveTraffic, 400);
        })
        .catch(err => {
            if (err.name === 'AbortError') return;
            console.error('Scan error:', err);
            showAccessPopup({
                color: 'red',
                name: 'Gangguan Jaringan',
                message: 'Gagal Scan'
            }, cleanCode);
        });
    }

    // Hardware Scanner Listener: Mendeteksi input beruntun cepat dari alat scanner USB RFID/Barcode
    let hardwareBuffer = '';
    let lastKeyTime = 0;

    window.addEventListener('keydown', (e) => {
        // Jangan tangkap jika pengguna sedang mengetik di dalam form/input
        const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
        if (tag === 'input' || tag === 'textarea') return;

        const currentTime = new Date().getTime();

        if (e.key === 'Enter' || e.code === 'Enter' || e.code === 'NumpadEnter') {
            if (hardwareBuffer.length >= 3) {
                e.preventDefault();
                handleScan(hardwareBuffer);
            }
            hardwareBuffer = '';
            return;
        }

        // Karakter scanner umumnya masuk sangat cepat (< 80ms antar karakter)
        if (currentTime - lastKeyTime > 300) {
            hardwareBuffer = '';
        }

        if (e.key.length === 1) {
            hardwareBuffer += e.key;
            lastKeyTime = currentTime;
        }
    });

    // 4. Engine Live Traffic Tank Silinder di Kiosk
    const tankModeBadge = document.getElementById('tank-mode-badge');
    const tankCapacityBadge = document.getElementById('tank-capacity-badge');
    const trafficTankLiquid = document.getElementById('traffic-tank-liquid');
    const tankCylinderElement = document.getElementById('tank-cylinder-element');
    const tankLayerGreen = document.getElementById('tank-layer-green');
    const tankLayerGray = document.getElementById('tank-layer-gray');
    const tankLayerRed = document.getElementById('tank-layer-red');
    const trafficTankPercentHud = document.getElementById('traffic-tank-percent-hud');
    const trafficTankCountHud = document.getElementById('traffic-tank-count-hud');

    const statBisaMasukCount = document.getElementById('stat-bisa-masuk-count');
    const statBisaMasukPct = document.getElementById('stat-bisa-masuk-pct');
    const statBelumPerpanjangCount = document.getElementById('stat-belum-perpanjang-count');
    const statBelumPerpanjangPct = document.getElementById('stat-belum-perpanjang-pct');
    const statBelumDaftarCount = document.getElementById('stat-belum-daftar-count');
    const statBelumDaftarPct = document.getElementById('stat-belum-daftar-pct');

    function updateTrafficTank(data) {
        if (!data) return;
        const total = Number(data.total || 0);
        const capacityPct = Number(data.capacity_percentage || 0);

        if (tankCapacityBadge) tankCapacityBadge.innerText = capacityPct + '% Kapasitas';
        if (trafficTankPercentHud) trafficTankPercentHud.innerText = capacityPct + '% Kapasitas';
        if (trafficTankCountHud) trafficTankCountHud.innerText = `${total} Kunjungan`;

        const pctGreen = Number(data.status_percentages?.bisa_masuk || 0);
        const pctGray = Number(data.status_percentages?.belum_perpanjang || 0);
        const pctRed = Number(data.status_percentages?.belum_daftar || 0);

        if (trafficTankLiquid) {
            const waveWrapper = trafficTankLiquid.querySelector('.db-wave-wrapper');
            if (total === 0) {
                trafficTankLiquid.style.height = '0%';
                if (waveWrapper) waveWrapper.style.opacity = '0';
            } else {
                const visualHeight = Math.min(100, Math.round(14 + (capacityPct * 0.72)));
                trafficTankLiquid.style.height = visualHeight + '%';
                if (waveWrapper) waveWrapper.style.opacity = '1';
            }
        }

        if (tankLayerGreen) {
            if (pctGreen > 0) {
                tankLayerGreen.style.display = 'block';
                tankLayerGreen.style.flex = `${pctGreen} 1 0%`;
                tankLayerGreen.style.minHeight = '4px';
            } else {
                tankLayerGreen.style.display = 'none';
                tankLayerGreen.style.flex = '0 0 0%';
                tankLayerGreen.style.minHeight = '0px';
            }
        }

        if (tankLayerGray) {
            if (pctGray > 0) {
                tankLayerGray.style.display = 'block';
                tankLayerGray.style.flex = `${pctGray} 1 0%`;
                tankLayerGray.style.minHeight = '4px';
            } else {
                tankLayerGray.style.display = 'none';
                tankLayerGray.style.flex = '0 0 0%';
                tankLayerGray.style.minHeight = '0px';
            }
        }

        if (tankLayerRed) {
            if (pctRed > 0) {
                tankLayerRed.style.display = 'block';
                tankLayerRed.style.flex = `${pctRed} 1 0%`;
                tankLayerRed.style.minHeight = '4px';
            } else {
                tankLayerRed.style.display = 'none';
                tankLayerRed.style.flex = '0 0 0%';
                tankLayerRed.style.minHeight = '0px';
            }
        }

        const waveSvg = trafficTankLiquid ? trafficTankLiquid.querySelector('.db-wave-wrapper svg') : null;
        if (waveSvg) {
            if (pctGreen > 0) {
                waveSvg.setAttribute('class', 'w-full h-full text-emerald-400 fill-current opacity-85');
            } else if (pctGray > 0) {
                waveSvg.setAttribute('class', 'w-full h-full text-slate-400 fill-current opacity-85');
            } else if (pctRed > 0) {
                waveSvg.setAttribute('class', 'w-full h-full text-rose-500 fill-current opacity-85');
            }
        }

        if (tankCylinderElement) {
            if (capacityPct >= 100) {
                tankCylinderElement.classList.add('tank-full-aura');
            } else {
                tankCylinderElement.classList.remove('tank-full-aura');
            }
        }

        if (statBisaMasukCount) statBisaMasukCount.innerText = data.status_counts?.bisa_masuk ?? 0;
        if (statBisaMasukPct) statBisaMasukPct.innerText = pctGreen + '%';

        if (statBelumPerpanjangCount) statBelumPerpanjangCount.innerText = data.status_counts?.belum_perpanjang ?? 0;
        if (statBelumPerpanjangPct) statBelumPerpanjangPct.innerText = pctGray + '%';

        if (statBelumDaftarCount) statBelumDaftarCount.innerText = data.status_counts?.belum_daftar ?? 0;
        if (statBelumDaftarPct) statBelumDaftarPct.innerText = pctRed + '%';
    }

    function fetchKioskLiveTraffic() {
        fetch('/api/live-traffic', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) throw new Error('Status ' + res.status);
            return res.json();
        })
        .then(data => {
            updateTrafficTank(data);
            
        })
        .catch(err => {
            console.warn('Gagal sinkronisasi live traffic kiosk:', err);
        });
    }

    // 5. Engine Leaderboard Gamifikasi ("Leaderboard Kunjungan") & Carousel Panel Kanan
    let kioskCarouselTimer = null;
    const KIOSK_CAROUSEL_INTERVAL = 180000; // 3 Menit (180.000 ms)

    function fetchKioskDemographics() {
        fetch('/api/demographics', { headers: { 'Accept': 'application/json' } })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => updateDemographicsUI(data))
        .catch(err => console.warn('Gagal fetch demografi:', err));
    }

    function updateDemographicsUI(data) {
        if (data.month_name) {
            const badge = document.getElementById('demographic-month-badge');
            if (badge) badge.innerText = data.month_name;
        }

        function animateCounter(el, endVal) {
            if (!el) return;
            const startVal = parseInt(el.innerText) || 0;
            const duration = 1500; // ms
            const startTime = performance.now();
            
            if (startVal === endVal) {
                el.innerText = endVal;
                return;
            }

            function step(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // easeOutQuad
                const ease = progress * (2 - progress);
                el.innerText = Math.floor(startVal + (endVal - startVal) * ease);
                
                if (progress < 1) {
                    requestAnimationFrame(step);
                } else {
                    el.innerText = endVal;
                }
            }
            requestAnimationFrame(step);
        }

        if (data.gender) {
            const countL = data.gender.L || 0;
            const countP = data.gender.P || 0;

            const splitLcount = document.getElementById('gender-split-l-count');
            const splitPcount = document.getElementById('gender-split-p-count');
            
            animateCounter(splitLcount, countL);
            animateCounter(splitPcount, countP);
        }

        if (data.age_groups) {
            const boxAge = document.getElementById('box-age-groups');
            if (boxAge) {
                let maxVal = 0;
                data.age_groups.forEach(ag => {
                    const t = Number(ag.total);
                    if (t > maxVal) maxVal = t;
                });
                
                const colorClasses = ['bg-cyan-500', 'bg-purple-500', 'bg-yellow-500'];

                boxAge.innerHTML = data.age_groups.map((ag, i) => {
                    const pct = maxVal > 0 ? (Number(ag.total) / maxVal) * 100 : 0;
                    const c = colorClasses[i] || 'bg-slate-500';
                    return `
                        <div class="flex items-center justify-between w-full">
                            <div class="w-[30%] flex-shrink-0 text-left">
                                <span class="text-xs sm:text-sm text-gray-300 font-medium whitespace-nowrap">${ag.group}</span>
                            </div>
                            <div class="flex-1 mx-2 sm:mx-3 bg-slate-700/50 rounded-full h-2 overflow-hidden flex items-center">
                                <div class="${c} rounded-full h-2 transition-all duration-1000 ease-out shadow-[0_0_8px_rgba(255,255,255,0.1)]" style="width: ${pct}%;"></div>
                            </div>
                            <div class="w-[20%] flex-shrink-0 text-right flex flex-col items-end leading-none">
                                <span class="text-2xl sm:text-3xl font-bold text-white font-mono">${ag.total}</span>
                                <span class="text-[9px] sm:text-xs text-gray-500 mt-1 block">Orang</span>
                            </div>
                        </div>
                    `;
                }).join('') || '<div class="text-xs text-slate-400 text-center">Belum ada data</div>';
            }
        }

        if (data.regions) {
            const boxReg = document.getElementById('box-regions');
            if (boxReg) {
                const colorClasses = ['text-emerald-400', 'text-amber-400', 'text-rose-400'];
                
                boxReg.innerHTML = data.regions.map((r, i) => {
                    const badge = (i === 0) 
                        ? `<span class="bg-gradient-to-r from-red-500 to-orange-500 text-white text-[7px] px-1.5 py-0.5 rounded-full flex items-center gap-0.5 whitespace-nowrap">🔥 Terbanyak</span>` 
                        : '';
                    return `
                        <div class="flex items-center justify-between bg-slate-900/50 rounded-lg p-1.5 sm:p-2 border border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                            <span class="text-[9px] sm:text-[10px] font-bold text-slate-200 truncate w-[45%]" title="${r.kecamatan_domisili}">
                                ${i === 0 ? '🥇' : (i === 1 ? '🥈' : '🥉')} ${r.kecamatan_domisili}
                            </span>
                            <div class="text-right flex items-center gap-1.5 sm:gap-2 justify-end w-[55%]">
                                ${badge}
                                <div class="flex flex-col items-end leading-none">
                                    <span class="font-black text-sm sm:text-base ${colorClasses[i]} font-mono">${r.total}</span>
                                    <span class="text-[7px] sm:text-[8px] text-slate-400 opacity-70 mt-0.5">Kunjungan</span>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('') || '<div class="text-xs text-slate-400 text-center">Belum ada data</div>';
            }
        }
    }

    function switchKioskWidget(targetName) {
        const widgetTank = document.getElementById('widgetTank');
        const widgetLeaderboard = document.getElementById('widgetLeaderboard');
        const dotTank = document.getElementById('dot-widget-tank');
        const dotLeaderboard = document.getElementById('dot-widget-leaderboard');

        if (!widgetTank || !widgetLeaderboard) return;

        if (targetName === 'leaderboard') {
            widgetTank.classList.remove('active');
            widgetLeaderboard.classList.add('active');
            if (dotTank) {
                dotTank.className = 'w-2 h-2 rounded-full transition-all duration-300 bg-slate-500 hover:bg-cyan-400 cursor-pointer';
            }
            if (dotLeaderboard) {
                dotLeaderboard.className = 'w-2 h-2 rounded-full transition-all duration-300 bg-amber-400 ring-2 ring-amber-400/40 cursor-pointer';
            }
        } else {
            widgetLeaderboard.classList.remove('active');
            widgetTank.classList.add('active');
            if (dotTank) {
                dotTank.className = 'w-2 h-2 rounded-full transition-all duration-300 bg-cyan-400 ring-2 ring-cyan-400/40 cursor-pointer';
            }
            if (dotLeaderboard) {
                dotLeaderboard.className = 'w-2 h-2 rounded-full transition-all duration-300 bg-slate-500 hover:bg-amber-400 cursor-pointer';
            }
        }
    }

    function startKioskCarousel() {
        if (kioskCarouselTimer) clearInterval(kioskCarouselTimer);
        kioskCarouselTimer = setInterval(() => {
            const widgetTank = document.getElementById('widgetTank');
            if (widgetTank && widgetTank.classList.contains('active')) {
                switchKioskWidget('leaderboard');
            } else {
                switchKioskWidget('tank');
            }
        }, KIOSK_CAROUSEL_INTERVAL);
    }

    const dotWidgetTank = document.getElementById('dot-widget-tank');
    const dotWidgetLeaderboard = document.getElementById('dot-widget-leaderboard');

    if (dotWidgetTank) {
        dotWidgetTank.addEventListener('click', () => {
            switchKioskWidget('tank');
            startKioskCarousel();
        });
    }

    if (dotWidgetLeaderboard) {
        dotWidgetLeaderboard.addEventListener('click', () => {
            switchKioskWidget('leaderboard');
            startKioskCarousel();
        });
    }

    // Mulai timer rotasi carousel otomatis (setiap 3 menit / 180.000 ms)
    startKioskCarousel();

    // Polling background setiap 3 detik di Kiosk
    fetchKioskLiveTraffic();
    setInterval(fetchKioskLiveTraffic, 3000);
    fetchKioskDemographics();
    setInterval(fetchKioskDemographics, 10000);

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initSignage);
    } else {
        initSignage();
    }
</script>
@endsection
