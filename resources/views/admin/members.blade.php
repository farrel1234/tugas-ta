@extends('layouts.main')

@section('title', 'Data Master Anggota - Digital Signage UPA Perpustakaan')

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
                        <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 font-medium px-4 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.analytics') ? 'bg-gradient-to-r from-cyan-500/20 to-blue-600/20 text-cyan-300 border border-cyan-500/30 font-semibold' : 'text-blue-200/70 hover:text-white hover:bg-blue-900/40' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            Analitik Pengunjung
                        </a>
                        <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 font-medium px-4 py-2.5 rounded-xl text-sm transition {{ request()->routeIs('admin.members.*') ? 'bg-gradient-to-r from-cyan-500/20 to-blue-600/20 text-cyan-300 border border-cyan-500/30 font-semibold' : 'text-blue-200/70 hover:text-white hover:bg-blue-900/40' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Data Anggota
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-6">

            <!-- Page Title & Search Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Data Master Anggota</h1>
                    <p class="text-xs text-blue-200/60 mt-1">Direktori seluruh anggota dan mahasiswa perpustakaan terdaftar</p>
                </div>

                <form method="GET" action="{{ route('admin.members.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIM, wilayah..." class="w-full bg-blue-950/60 border border-blue-500/30 text-white placeholder-blue-300/40 text-xs rounded-xl px-3 py-2 pl-9 focus:outline-none focus:border-cyan-400 transition">
                        <svg class="w-4 h-4 text-blue-300/50 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold px-4 py-2 rounded-xl transition cursor-pointer">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.members.index') }}" class="text-xs text-slate-400 hover:text-white px-2 py-1">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-blue-950/40 border border-blue-500/20 p-4 rounded-2xl backdrop-blur-md shadow-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-300 font-bold text-xl">
                        👥
                    </div>
                    <div>
                        <span class="text-xs text-blue-300/70 font-medium block">Total Anggota</span>
                        <span class="text-2xl font-black text-white tracking-tight">{{ $totalMembers }}</span>
                    </div>
                </div>

                <div class="bg-blue-950/40 border border-blue-500/20 p-4 rounded-2xl backdrop-blur-md shadow-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-300 font-bold text-xl">
                        ✓
                    </div>
                    <div>
                        <span class="text-xs text-blue-300/70 font-medium block">Anggota Aktif</span>
                        <span class="text-2xl font-black text-emerald-400 tracking-tight">{{ $activeMembers }}</span>
                    </div>
                </div>

                <div class="bg-blue-950/40 border border-blue-500/20 p-4 rounded-2xl backdrop-blur-md shadow-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-300 font-bold text-xl">
                        ✕
                    </div>
                    <div>
                        <span class="text-xs text-blue-300/70 font-medium block">Masa Berlaku Habis</span>
                        <span class="text-2xl font-black text-rose-400 tracking-tight">{{ $expiredMembers }}</span>
                    </div>
                </div>
            </div>

            <!-- Table Rekap Data Anggota Terdaftar -->
            <div class="bg-blue-950/40 p-5 rounded-2xl border border-blue-500/20 backdrop-blur-md shadow-xl overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-3">
                    <h3 class="text-sm font-bold text-cyan-300 uppercase tracking-widest flex items-center gap-2">
                        <span>📊</span> Rekap Data Anggota Terdaftar
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.members.export', ['type' => 'excel']) }}" class="flex items-center gap-2 bg-emerald-600/90 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors border border-emerald-400/50 shadow-lg backdrop-blur-sm cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg>
                            <span>Export Excel</span>
                        </a>
                        <a href="{{ route('admin.members.export', ['type' => 'pdf']) }}" class="flex items-center gap-2 bg-rose-600/90 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors border border-rose-400/50 shadow-lg backdrop-blur-sm cursor-pointer">
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
                                <th class="px-4 py-3">NIM</th>
                                <th class="px-4 py-3">Nama Anggota</th>
                                <th class="px-4 py-3">Gender</th>
                                <th class="px-4 py-3">Umur</th>
                                <th class="px-4 py-3">Wilayah</th>
                                <th class="px-4 py-3 rounded-tr-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $index => $member)
                                @php
                                    $age = $member->tanggal_lahir ? \Carbon\Carbon::parse($member->tanggal_lahir)->age : '-';
                                    $gender = $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '-');
                                    $isExpired = $member->expired_at ? \Carbon\Carbon::parse($member->expired_at)->isPast() : null;
                                @endphp
                                <tr class="border-b border-blue-500/10 hover:bg-blue-800/30 transition-colors">
                                    <td class="px-4 py-3">{{ $members->firstItem() + $index }}</td>
                                    <td class="px-4 py-3 font-mono text-cyan-200 font-semibold">{{ $member->nim ?? '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-white">{{ $member->name }}</td>
                                    <td class="px-4 py-3">{{ $gender }}</td>
                                    <td class="px-4 py-3">{{ $age }} Thn</td>
                                    <td class="px-4 py-3">{{ $member->kecamatan_domisili ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($member->expired_at)
                                            @if($isExpired)
                                                <div class="inline-flex flex-col items-start gap-1">
                                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-300 border border-rose-500/40 inline-flex items-center gap-1.5 shadow-sm">
                                                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                                        Kadaluarsa
                                                    </span>
                                                    <span class="text-[10px] text-rose-400/80 font-mono tracking-tight pl-0.5">
                                                        exp: {{ \Carbon\Carbon::parse($member->expired_at)->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="inline-flex flex-col items-start gap-1">
                                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-300 border border-emerald-500/40 inline-flex items-center gap-1.5 shadow-sm">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                                        Aktif
                                                    </span>
                                                    <span class="text-[10px] text-cyan-300/80 font-mono tracking-tight pl-0.5">
                                                        s.d. {{ \Carbon\Carbon::parse($member->expired_at)->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-slate-400">
                                        @if($search)
                                            Tidak ditemukan data anggota dengan kata kunci "{{ $search }}".
                                        @else
                                            Tidak ada data anggota terdaftar.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>
            </div>

        </main>
    </div>
</div>
@endsection
