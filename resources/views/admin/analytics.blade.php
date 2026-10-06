@extends('layouts.main')

@section('title', 'Dashboard Admin Digital Signage')

@section('content')
<style>
    .custom-table-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.8) rgba(9, 23, 49, 0.95);
    }
    .custom-table-scrollbar::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    .custom-table-scrollbar::-webkit-scrollbar-track {
        background: rgba(9, 23, 49, 0.95);
        border-radius: 8px;
        border-left: 1px solid rgba(6, 182, 212, 0.2);
    }
    .custom-table-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #2563eb);
        border-radius: 8px;
        border: 1px solid rgba(6, 182, 212, 0.5);
        cursor: grab;
    }
    .custom-table-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #22d3ee, #3b82f6);
    }
    .custom-table-scrollbar::-webkit-scrollbar-thumb:active {
        background: #38bdf8;
        cursor: grabbing;
    }
    .custom-table-scrollbar::-webkit-scrollbar-button:single-button:vertical:decrement {
        height: 14px;
        width: 10px;
        background-color: rgba(11, 30, 63, 0.95);
        background-position: center 3px;
        background-repeat: no-repeat;
        background-size: 8px;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2306b6d4' viewBox='0 0 16 16'><path d='M8 4l6 8H2z'/></svg>");
        border-radius: 4px 4px 0 0;
        cursor: pointer;
    }
    .custom-table-scrollbar::-webkit-scrollbar-button:single-button:vertical:decrement:hover {
        background-color: rgba(6, 182, 212, 0.3);
    }
    .custom-table-scrollbar::-webkit-scrollbar-button:single-button:vertical:increment {
        height: 14px;
        width: 10px;
        background-color: rgba(11, 30, 63, 0.95);
        background-position: center 3px;
        background-repeat: no-repeat;
        background-size: 8px;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2306b6d4' viewBox='0 0 16 16'><path d='M8 12L2 4h12z'/></svg>");
        border-radius: 0 0 4px 4px;
        cursor: pointer;
    }
    .custom-table-scrollbar::-webkit-scrollbar-button:single-button:vertical:increment:hover {
        background-color: rgba(6, 182, 212, 0.3);
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
        100% { transform: translateY(-240px) scale(1.1); opacity: 0; }
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

    /* Terminal Styling */
    .terminal-scanlines {
        background-image: repeating-linear-gradient(
            0deg,
            rgba(0, 0, 0, 0.3) 0px,
            rgba(0, 0, 0, 0.3) 1px,
            transparent 1px,
            transparent 2px
        );
    }

    /* Traffic Date Picker Styling */
    #traffic-date-picker {
        color-scheme: dark;
    }
    #traffic-date-picker::-webkit-calendar-picker-indicator {
        cursor: pointer;
        filter: invert(0.8) sepia(100%) saturate(300%) hue-rotate(150deg);
        opacity: 0.85;
        transition: opacity 0.2s, transform 0.2s;
    }
    #traffic-date-picker:hover::-webkit-calendar-picker-indicator {
        opacity: 1;
        transform: scale(1.1);
    }
</style>
<div class="min-h-screen bg-[#071124] text-white flex flex-col font-sans select-none">

    <!-- Top Navigation Bar -->
    <header class="bg-[#0B1E3F]/80 backdrop-blur-md border-b border-blue-500/20 px-4 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-900/60 rounded-xl border border-blue-500/30 flex items-center justify-center p-1.5 shadow-inner">
                <img src="{{ asset('templating/img/polibatam.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-black tracking-tight text-white uppercase">Panel Admin Signage</h1>
                <span class="text-[10px] text-cyan-400 font-semibold tracking-wider block">UPA Perpustakaan Polibatam</span>
            </div>
        </div>

        <div class="flex items-center gap-4">

            <div class="hidden sm:flex items-center gap-2 bg-blue-950/60 px-3 py-1.5 rounded-xl border border-blue-500/30 text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-blue-200">{{ Auth::user()->name ?? 'Administrator' }}</span>
            </div>

            <!-- Tombol Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Content Layout -->
    <div class="flex flex-1 overflow-hidden">

        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#091731] border-r border-blue-500/20 p-5 hidden lg:flex flex-col justify-between flex-shrink-0">
            <div class="space-y-6">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-cyan-400 block mb-3">Navigasi Utama</span>
                                                            <nav class="space-y-1.5">
                        <a href="{{ url('/') }}" class="flex items-center gap-3 text-blue-200/70 hover:text-white hover:bg-blue-900/40 font-medium px-4 py-2.5 rounded-xl text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            Lihat Tampilan Signage
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-cyan-500/20 to-blue-600/20 text-cyan-300 border border-cyan-500/30 font-semibold' : 'text-blue-200/70 hover:text-white hover:bg-blue-900/40 font-medium' }} px-4 py-2.5 rounded-xl text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Kelola Signage
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 font-medium px-4 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.analytics') ? 'bg-gradient-to-r from-cyan-500/20 to-blue-600/20 text-cyan-300 border border-cyan-500/30' : 'text-blue-200/70 hover:text-white hover:bg-blue-900/40' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            Analitik Pengunjung
                        </a>
                    </nav>
                </div>
            </div>

            <div class="bg-blue-950/60 border border-blue-500/20 p-3.5 rounded-xl text-xs text-blue-200/60">
                <span class="block font-bold text-white mb-1">Status Kiosk</span>
                <div class="flex items-center gap-2 text-emerald-400 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Live Display Active
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto space-y-6">
    <!-- Header Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-blue-950/40 p-5 rounded-2xl border border-blue-500/20 backdrop-blur-md shadow-xl gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Analitik Pengunjung</h1>
            <p class="text-sm text-blue-200/70 mt-1">Data statistik riwayat kunjungan perpustakaan berdasarkan bulan.</p>
        </div>
        <form method="GET" action="{{ route('admin.analytics') }}" class="flex items-end gap-3">
            <div>
                <label class="block text-xs font-semibold text-blue-300 uppercase tracking-widest mb-1">Bulan</label>
                <select name="month" class="bg-blue-900/50 border border-blue-500/30 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-32 p-2.5">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-blue-300 uppercase tracking-widest mb-1">Tahun</label>
                <select name="year" class="bg-blue-900/50 border border-blue-500/30 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-28 p-2.5">
                    @for($y=date('Y')-2; $y<=date('Y'); $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold px-5 py-2.5 rounded-lg shadow-lg hover:from-cyan-400 hover:to-blue-500 transition-all border border-cyan-400/50">
                Terapkan
            </button>
        </form>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Daily Trend -->
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg backdrop-blur-sm">
            <div class="px-5 py-4 border-b border-slate-700/70 bg-slate-900/30">
                <h3 class="text-lg font-bold text-white tracking-wide">
                    Grafik Kunjungan Harian
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Fluktuasi jumlah kedatangan pemustaka per tanggal dalam bulan ini.
                </p>
            </div>
            <div class="p-5">
                <div class="relative h-64 w-full">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gender Ratio -->
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg backdrop-blur-sm">
            <div class="px-5 py-4 border-b border-slate-700/70 bg-slate-900/30">
                <h3 class="text-lg font-bold text-white tracking-wide">
                    Rasio Gender
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Perbandingan rasio pemustaka laki-laki dan perempuan.
                </p>
            </div>
            <div class="p-5">
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg backdrop-blur-sm">
            <div class="px-5 py-4 border-b border-slate-700/70 bg-slate-900/30">
                <h3 class="text-lg font-bold text-white tracking-wide">
                    Range Usia
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Segmentasi pengunjung perpustakaan berdasarkan rentang umur.
                </p>
            </div>
            <div class="p-5">
                <div class="relative h-64 w-full">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Region Mapping -->
        <div class="bg-slate-800/40 border border-slate-700/50 rounded-xl overflow-hidden shadow-lg backdrop-blur-sm">
            <div class="px-5 py-4 border-b border-slate-700/70 bg-slate-900/30">
                <h3 class="text-lg font-bold text-white tracking-wide">
                    Pemetaan Wilayah
                </h3>
                <p class="text-xs text-slate-400 mt-1">
                    Pemetaan area tempat tinggal pemustaka yang paling sering berkunjung.
                </p>
            </div>
            <div class="p-5">
                <div class="relative h-64 w-full">
                    <canvas id="regionChart"></canvas>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Data Table -->
    <div class="bg-blue-950/40 p-5 rounded-2xl border border-blue-500/20 backdrop-blur-md shadow-xl overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
            <h3 class="text-sm font-bold text-cyan-300 uppercase tracking-widest">📋 Log Kunjungan Detail</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.analytics.export', ['type' => 'excel', 'month' => $selectedMonth, 'year' => $selectedYear]) }}" class="flex items-center gap-2 bg-emerald-600/90 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors border border-emerald-400/50 shadow-lg backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg>
                    <span>Export Excel</span>
                </a>
                <a href="{{ route('admin.analytics.export', ['type' => 'pdf', 'month' => $selectedMonth, 'year' => $selectedYear]) }}" class="flex items-center gap-2 bg-rose-600/90 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors border border-rose-400/50 shadow-lg backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v9M15 16l-3 3-3-3M8 4h8a2 2 0 012 2v2H6V6a2 2 0 012-2z"/></svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-blue-100">
                <thead class="text-xs text-cyan-300 uppercase bg-blue-900/50 border-b border-blue-500/30">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">No</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Nama Pengunjung</th>
                        <th class="px-4 py-3">Gender</th>
                        <th class="px-4 py-3">Umur</th>
                        <th class="px-4 py-3 rounded-tr-lg">Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedLogs as $index => $log)
                        @php
                            $age = $log->user && $log->user->tanggal_lahir ? \Carbon\Carbon::parse($log->user->tanggal_lahir)->age : '-';
                        @endphp
                        <tr class="border-b border-blue-500/10 hover:bg-blue-800/30 transition-colors">
                            <td class="px-4 py-3">{{ $paginatedLogs->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-mono text-cyan-200">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $log->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3">{{ $log->user ? ($log->user->gender == 'L' ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
                            <td class="px-4 py-3">{{ $age }} Thn</td>
                            <td class="px-4 py-3">{{ $log->user->kecamatan_domisili ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">Tidak ada data kunjungan di bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $paginatedLogs->links() }}
        </div>
    </div>
</main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = 'Inter, sans-serif';

    const glowPlugin = {
        id: 'glowPlugin',
        beforeDatasetsDraw(chart) {
            const ctx = chart.ctx;
            ctx.save();
            ctx.shadowColor = chart.config.options.plugins.glowPlugin?.color || 'transparent';
            ctx.shadowBlur = chart.config.options.plugins.glowPlugin?.blur || 0;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
        },
        afterDatasetsDraw(chart) {
            chart.ctx.restore();
        }
    };
    Chart.register(glowPlugin);

    // Premium Tooltip Config
    const tooltipConfig = {
        backgroundColor: 'rgba(0, 0, 0, 0.75)',
        padding: 12,
        cornerRadius: 8,
        titleFont: { size: 14, family: 'Inter, sans-serif', weight: 'bold' },
        bodyFont: { size: 13, family: 'Inter, sans-serif' },
        displayColors: false,
    };

    // --- 1. TREN KUNJUNGAN HARIAN ---
    const dailyCtx = document.getElementById('dailyChart');
    const dailyCtx2D = dailyCtx.getContext('2d');
    
    const dailyGradient = dailyCtx2D.createLinearGradient(0, 0, 0, 300);
    dailyGradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)');
    dailyGradient.addColorStop(1, 'rgba(6, 182, 212, 0.01)');

    const dailyData = @json($dailyTrend);
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: Object.keys(dailyData),
            datasets: [{
                label: 'Kunjungan',
                data: Object.values(dailyData),
                borderColor: '#06b6d4',
                backgroundColor: dailyGradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            },
            plugins: { 
                legend: { display: false },
                tooltip: tooltipConfig,
                glowPlugin: { color: 'rgba(6, 182, 212, 0.8)', blur: 15 }
            }
        }
    });

    // --- 2. GENDER ---
    const genderCtx = document.getElementById('genderChart');
    const genderCtx2D = genderCtx.getContext('2d');
    
    const gradL = genderCtx2D.createLinearGradient(0, 0, 0, 200);
    gradL.addColorStop(0, '#3b82f6');
    gradL.addColorStop(1, '#1e3a8a');
    
    const gradP = genderCtx2D.createLinearGradient(0, 0, 0, 200);
    gradP.addColorStop(0, '#f43f5e');
    gradP.addColorStop(1, '#881337');

    const genderData = @json($genderData);
    
    const centerTextPlugin = {
        id: 'centerTextPlugin',
        beforeDraw(chart) {
            const { ctx, width, height } = chart;
            ctx.save();
            
            // Get exact center of the doughnut ring
            const meta = chart.getDatasetMeta(0);
            const centerX = meta.data[0]?.x || width / 2;
            const centerY = meta.data[0]?.y || height / 2;
            
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Text 1: "TOTAL"
            ctx.font = 'bold 12px "Inter", Arial, sans-serif';
            ctx.fillStyle = '#94a3b8';
            ctx.fillText('TOTAL', centerX, centerY - 15);
            
            // Text 2: Angka total
            ctx.font = 'bold 36px "Inter", Arial, sans-serif';
            ctx.fillStyle = '#ffffff';
            ctx.fillText('{{ $kpiTotal }}', centerX, centerY + 15);
            
            ctx.restore();
        }
    };

    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [genderData['L'] || 0, genderData['P'] || 0],
                backgroundColor: [gradL, gradP],
                borderWidth: 8,
                borderColor: '#1e293b', 
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%', 
            plugins: {
                legend: { position: 'bottom', labels: { color: '#cbd5e1', padding: 20 } },
                tooltip: tooltipConfig,
                glowPlugin: { color: 'rgba(255, 255, 255, 0.05)', blur: 10 }
            }
        },
        plugins: [centerTextPlugin]
    });

    // --- 3. UMUR ---
    const ageCtx = document.getElementById('ageChart');
    const ageCtx2D = ageCtx.getContext('2d');
    
    const ageColors = [
        { top: '#06b6d4', bottom: 'rgba(6, 182, 212, 0.05)' },
        { top: '#8b5cf6', bottom: 'rgba(139, 92, 246, 0.05)' },
        { top: '#eab308', bottom: 'rgba(234, 179, 8, 0.05)' },
        { top: '#f97316', bottom: 'rgba(249, 115, 22, 0.05)' }
    ];
    
    const ageGradients = ageColors.map(c => {
        const g = ageCtx2D.createLinearGradient(0, 0, 0, 250);
        g.addColorStop(0, c.top);
        g.addColorStop(1, c.bottom);
        return g;
    });

    const ageData = @json($ageGroups);
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(ageData),
            datasets: [{
                label: 'Pengunjung',
                data: Object.values(ageData),
                backgroundColor: ageGradients,
                borderRadius: 50,
                borderSkipped: false,
                barThickness: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            },
            plugins: { 
                legend: { display: false },
                tooltip: tooltipConfig,
                glowPlugin: { color: 'rgba(139, 92, 246, 0.3)', blur: 10 }
            }
        }
    });

    // --- 4. WILAYAH ---
    const regionCtx = document.getElementById('regionChart');
    const regionCtx2D = regionCtx.getContext('2d');
    
    const regionGradient = regionCtx2D.createLinearGradient(0, 0, 400, 0);
    regionGradient.addColorStop(1, '#10b981');
    regionGradient.addColorStop(0, 'rgba(16, 185, 129, 0.05)');

    const regionData = @json($regionData);
    new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(regionData),
            datasets: [{
                label: 'Kunjungan',
                data: Object.values(regionData),
                backgroundColor: regionGradient,
                borderRadius: 50,
                borderSkipped: false,
                barThickness: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true, grid: { display: false }, ticks: { precision: 0 } },
                y: { grid: { display: false } }
            },
            plugins: { 
                legend: { display: false },
                tooltip: tooltipConfig,
                glowPlugin: { color: 'rgba(16, 185, 129, 0.4)', blur: 10 }
            }
        }
    });
</script>
@endsection