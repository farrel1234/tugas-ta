@extends('layouts.main')

@section('title', 'Dashboard Admin Digital Signage')

@section('content')
<style>
    .custom-table-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(6, 182, 212, 0.7) rgba(11, 30, 63, 0.8);
        scroll-behavior: smooth;
    }
    .custom-table-scrollbar::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .custom-table-scrollbar::-webkit-scrollbar-track {
        background: rgba(9, 23, 49, 0.9);
        border-radius: 8px;
    }
    .custom-table-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #2563eb);
        border-radius: 8px;
        border: 1px solid rgba(6, 182, 212, 0.4);
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
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 bg-gradient-to-r from-cyan-500/20 to-blue-600/20 text-cyan-300 font-semibold px-4 py-2.5 rounded-xl border border-cyan-500/30 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Kelola Signage
                        </a>
                        <a href="{{ url('/') }}" class="flex items-center gap-3 text-blue-200/70 hover:text-white hover:bg-blue-900/40 font-medium px-4 py-2.5 rounded-xl text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            Lihat Tampilan Signage
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

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs shadow-lg space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Ada kesalahan pada input:
                    </div>
                    <ul class="list-disc list-inside pl-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Summary Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-lg">
                    <div>
                        <span class="text-xs text-cyan-300 font-bold uppercase tracking-wider block mb-1">Total Menu Aktif</span>
                        <h3 class="text-2xl font-black text-white">{{ sprintf('%02d', $totalMenus ?? count($menus)) }} Menu</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-900/50 rounded-xl border border-blue-500/30 flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                </div>

                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-lg">
                    <div>
                        <span class="text-xs text-cyan-300 font-bold uppercase tracking-wider block mb-1">Total Slide / Langkah</span>
                        <h3 class="text-2xl font-black text-white">{{ sprintf('%02d', $totalSteps ?? 0) }} Slide</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-900/50 rounded-xl border border-blue-500/30 flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                </div>

                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-lg">
                    <div>
                        <span class="text-xs text-amber-300 font-bold uppercase tracking-wider block mb-1">Tempat Sampah</span>
                        <h3 class="text-2xl font-black text-white">{{ sprintf('%02d', $trashedMenus->count()) }} Terhapus</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-950/50 rounded-xl border border-amber-500/30 flex items-center justify-center text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                </div>

                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-lg">
                    <div>
                        <span class="text-xs text-cyan-300 font-bold uppercase tracking-wider block mb-1">Durasi per Slide</span>
                        <h3 class="text-2xl font-black text-white">8 Detik</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-900/50 rounded-xl border border-blue-500/30 flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Menu Signage -->
            <section class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-5 sm:p-6 shadow-2xl">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-black text-white tracking-tight">Daftar Menu Signage Aktif</h2>
                            <span class="bg-blue-900/60 text-cyan-300 border border-blue-500/40 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ count($menus) }} Menu</span>
                        </div>
                        <p class="text-xs text-blue-200/70">Atur urutan menu dan gambar slide yang tampil di layar utama Kiosk.</p>
                    </div>
                    <a href="#form-tambah-menu" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-lg shadow-cyan-500/20 flex items-center gap-2 justify-center cursor-pointer border border-cyan-400/30" style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Menu Baru
                    </a>
                </div>

                <!-- Data Table with Max Height 520px (10 items) & Smooth Scroll -->
                <div id="menu-table-scroll" class="max-h-[520px] overflow-y-auto overflow-x-auto rounded-xl border border-blue-500/30 custom-table-scrollbar">
                    <table class="w-full text-left text-xs text-blue-100 border-collapse">
                        <thead class="bg-[#091731] uppercase text-[10px] tracking-wider text-cyan-300 border-b border-blue-500/40 sticky top-0 z-10 shadow-md">
                            <tr>
                                <th class="p-3.5 bg-[#091731] rounded-tl-xl">No</th>
                                <th class="p-3.5 bg-[#091731]">Judul Menu Layanan</th>
                                <th class="p-3.5 bg-[#091731]">Deskripsi Singkat</th>
                                <th class="p-3.5 text-center bg-[#091731]">Jumlah Langkah</th>
                                <th class="p-3.5 text-center bg-[#091731] rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-800/40">
                            @forelse($menus as $menu)
                                <tr class="hover:bg-blue-900/30 transition">
                                    <td class="p-3.5 font-extrabold text-cyan-400">{{ $menu->num }}</td>
                                    <td class="p-3.5 font-bold text-white">{{ $menu->title }}</td>
                                    <td class="p-3.5 text-blue-200/80 max-w-xs truncate">{{ $menu->desc ?? '-' }}</td>
                                    <td class="p-3.5 text-center">
                                        <span class="bg-blue-900/80 border border-blue-500/40 px-2.5 py-1 rounded-md text-[10px] font-bold text-cyan-300">
                                            {{ $menu->steps->count() }} Slide
                                        </span>
                                    </td>
                                    <td class="p-3.5">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Tombol Edit & Kelola Slide -->
                                            <a href="{{ route('admin.menus.edit', $menu) }}" class="bg-blue-800/60 hover:bg-blue-700 text-cyan-300 px-3 py-1.5 rounded-lg border border-blue-500/30 font-semibold transition cursor-pointer flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit & Slide
                                            </a>

                                            <!-- Tombol Hapus Menu (Pindah ke Sampah) -->
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Pindahkan Menu \'{{ $menu->title }}\' ke Tempat Sampah?\n\nCatatan: Menu yang dipindahkan ke Tempat Sampah tidak akan tampil di Kiosk, namun dapat Anda pulihkan kembali sewaktu-waktu.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Pindahkan ke Tempat Sampah" class="bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 px-3 py-1.5 rounded-lg border border-rose-500/30 font-semibold transition cursor-pointer flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-blue-300/50">Belum ada menu aktif. Silakan pulihkan menu dari Tempat Sampah di bawah atau tambahkan menu baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Bagian Tempat Sampah / Menu Terhapus Sementara -->
            <section class="bg-[#121B35]/60 backdrop-blur-lg border border-amber-500/30 rounded-2xl p-5 sm:p-6 shadow-2xl">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-300 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base sm:text-lg font-black text-white tracking-tight">Tempat Sampah Menu (Trash)</h2>
                                <span class="bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $trashedMenus->count() }} Menu</span>
                            </div>
                            <p class="text-xs text-blue-200/70">Menu yang dihapus tersimpan di sini secara aman (Soft Delete) dan tidak tampil di Kiosk. Anda dapat memulihkannya kapan saja atau menghapusnya secara permanen.</p>
                        </div>
                    </div>

                </div>

                @if($trashedMenus->count() > 0)
                    <div id="trash-table-scroll" class="max-h-[520px] overflow-y-auto overflow-x-auto rounded-xl border border-amber-500/30 custom-table-scrollbar mt-4">
                        <table class="w-full text-left text-xs text-blue-100 border-collapse">
                            <thead class="bg-[#121B35] uppercase text-[10px] tracking-wider text-amber-300 border-b border-amber-500/40 sticky top-0 z-10 shadow-md">
                                <tr>
                                    <th class="p-3.5 bg-[#121B35] rounded-tl-xl">No</th>
                                    <th class="p-3.5 bg-[#121B35]">Judul Menu Layanan</th>
                                    <th class="p-3.5 bg-[#121B35]">Waktu Dihapus</th>
                                    <th class="p-3.5 text-center bg-[#121B35]">Jumlah Slide</th>
                                    <th class="p-3.5 text-center bg-[#121B35] rounded-tr-xl">Aksi Pemulihan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-900/30">
                                @foreach($trashedMenus as $trashed)
                                    <tr class="hover:bg-amber-900/20 transition">
                                        <td class="p-3.5 font-extrabold text-amber-400 line-through">{{ $trashed->num }}</td>
                                        <td class="p-3.5 font-bold text-white/90">
                                            <div class="line-through text-slate-300">{{ $trashed->title }}</div>
                                            <div class="text-[10px] text-blue-300/60 mt-0.5 max-w-xs truncate">{{ $trashed->desc ?? '-' }}</div>
                                        </td>
                                        <td class="p-3.5 text-amber-200/80 text-[11px]">
                                            {{ $trashed->deleted_at ? $trashed->deleted_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                            <span class="block text-[10px] text-amber-400/70">({{ $trashed->deleted_at ? $trashed->deleted_at->diffForHumans() : '' }})</span>
                                        </td>
                                        <td class="p-3.5 text-center">
                                            <span class="bg-blue-950/80 border border-blue-500/30 px-2.5 py-1 rounded-md text-[10px] text-blue-300">
                                                {{ $trashed->steps->count() }} Slide
                                            </span>
                                        </td>
                                        <td class="p-3.5">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Tombol Pulihkan -->
                                                <form action="{{ route('admin.menus.restore', $trashed->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        Pulihkan
                                                    </button>
                                                </form>

                                                <!-- Tombol Hapus Permanen -->
                                                <form action="{{ route('admin.menus.force-delete', $trashed->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menu \'{{ $trashed->title }}\' dan seluruh gambar slidenya akan DIHAPUS PERMANEN dari database dan server.\n\nTindakan ini TIDAK DAPAT DIBATALKAN. Yakin ingin melanjutkan?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-rose-600/20 hover:bg-rose-600/40 text-rose-300 border border-rose-600/40 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Hapus Permanen
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-4 p-4 rounded-xl bg-blue-950/40 border border-blue-500/20 text-center text-xs text-blue-300/60">
                        Tempat sampah kosong. Tidak ada menu yang sedang dihapus.
                    </div>
                @endif
            </section>

            <!-- Bagian Riwayat Kunjungan Pengunjung (Visitor Logs) -->
            <section class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-5 sm:p-6 shadow-2xl space-y-4">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-blue-800/50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-cyan-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base sm:text-lg font-black text-white tracking-tight">Riwayat Scan Kunjungan Gerbang</h2>
                                <span class="bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $totalVisitorsToday ?? 0 }} Kunjungan Hari Ini</span>
                            </div>
                            <p class="text-xs text-blue-200/70">Catatan log pengunjung yang memindai kartu/NIM pada layar Kiosk secara real-time.</p>
                        </div>
                    </div>

                    <div class="self-start sm:self-auto">
                        <!-- Tombol Buka Modal Uji Scanner -->
                        <button type="button" id="open-scanner-modal-btn" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-lg shadow-cyan-500/20 flex items-center gap-2 justify-center cursor-pointer border border-cyan-400/30" style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                            <svg class="w-4 h-4 text-white animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>Uji Scanner</span>
                        </button>
                    </div>
                </div>

                @if(isset($recentLogs) && $recentLogs->count() > 0)
                    <!-- Data Table Container with Max Height 520px (10 items) & Smooth Scroll -->
                    <div id="visitor-logs-scroll" class="max-h-[520px] overflow-y-auto overflow-x-auto rounded-xl border border-blue-500/30 custom-table-scrollbar">
                        <table class="w-full text-left text-xs text-blue-100 border-collapse">
                            <thead class="bg-[#091731] uppercase text-[10px] tracking-wider text-cyan-300 border-b border-blue-500/40 sticky top-0 z-10 shadow-md">
                                <tr>
                                    <th class="p-3.5 bg-[#091731] rounded-tl-xl">Waktu Scan</th>
                                    <th class="p-3.5 bg-[#091731]">Nama Pengunjung</th>
                                    <th class="p-3.5 bg-[#091731]">Kode Kartu / NIM</th>
                                    <th class="p-3.5 text-center bg-[#091731] rounded-tr-xl">Status Akses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-blue-800/40">
                                @foreach($recentLogs as $log)
                                    <tr class="hover:bg-blue-900/30 transition">
                                        <td class="p-3.5 text-blue-200/90 font-mono text-[11px]">
                                            {{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') . ' WIB' : '-' }}
                                            <span class="block text-[10px] text-blue-300/50">({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})</span>
                                        </td>
                                        <td class="p-3.5 font-bold text-white">
                                            {{ $log->user->name ?? 'Tidak Dikenal' }}
                                        </td>
                                        <td class="p-3.5 font-mono text-cyan-300 text-[11px]">
                                            {{ $log->scanned_code ?? '-' }}
                                        </td>
                                        <td class="p-3.5 text-center">
                                            @if($log->status === 'Bisa Masuk')
                                                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-3 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                    Bisa Masuk
                                                </span>
                                            @elseif($log->status === 'Belum Perpanjang')
                                                <span class="bg-slate-500/20 text-slate-300 border border-slate-500/40 px-3 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Belum Perpanjang
                                                </span>
                                            @else
                                                <span class="bg-rose-500/20 text-rose-300 border border-rose-500/40 px-3 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                    Belum Terdaftar
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 rounded-xl bg-blue-950/40 border border-blue-500/20 text-center text-xs text-blue-300/60">
                        Belum ada data scan kunjungan hari ini. Silakan uji coba tap kartu pada halaman Kiosk.
                    </div>
                @endif
            </section>

            <!-- Form Tambah Menu & Upload Slide Pertama -->
            <section id="form-tambah-menu" class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-5 sm:p-6 shadow-2xl space-y-6">
                <div class="border-b border-blue-800/50 pb-4">
                    <h2 class="text-lg font-black text-white tracking-tight">Form Tambah Menu Signage Baru</h2>
                    <p class="text-xs text-blue-200/70">Isi data di bawah ini untuk menambahkan menu baru dan opsional slide gambar pertamanya.</p>
                </div>

                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Nomor Urut Menu *</label>
                            <input type="text" name="num" value="{{ old('num') }}" required placeholder="Contoh: 10 atau 01" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Judul Menu Layanan *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul menu layanan" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Deskripsi Layanan</label>
                        <textarea name="desc" rows="2" placeholder="Tuliskan penjelasan singkat mengenai layanan ini..." class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">{{ old('desc') }}</textarea>
                    </div>

                    <!-- Upload Gambar Slide Langkah Pertama dengan Live Preview -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider">Unggah Gambar Slide / Ilustrasi Pertama (Opsional)</label>
                        
                        <label for="menu-image-input" class="group flex flex-col items-center justify-center border-2 border-dashed border-cyan-500/40 hover:border-cyan-400 rounded-2xl p-5 text-center bg-blue-950/40 hover:bg-blue-900/20 transition cursor-pointer">
                            <div id="menu-empty-prompt" class="flex flex-col items-center py-2">
                                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-white block">Klik di sini untuk memilih gambar slide pertama</span>
                                <span class="text-[10px] text-blue-300/70 block mt-0.5">Format JPG/PNG/WEBP (Rasio landscape 16:9, Max 5MB)</span>
                            </div>

                            <!-- Live Image Preview Box -->
                            <div id="menu-preview-wrapper" class="hidden w-full space-y-2">
                                <div class="relative w-full h-48 bg-blue-950/80 rounded-xl overflow-hidden border border-cyan-500/50 flex items-center justify-center shadow-inner">
                                    <img id="menu-image-preview" src="" alt="Pratinjau Gambar Pertama" class="max-w-full max-h-full object-contain">
                                </div>
                                <div class="flex items-center justify-between text-[11px] bg-blue-900/60 px-3 py-1.5 rounded-lg border border-blue-500/30">
                                    <span id="menu-file-name" class="font-medium text-cyan-200 truncate max-w-[200px]">nama_file.png</span>
                                    <span id="menu-file-size" class="text-blue-300/70 font-mono text-[10px]">0 KB</span>
                                </div>
                                <span class="text-[10px] text-cyan-400 font-semibold block text-center">✓ Gambar berhasil dipilih! Klik kembali jika ingin mengganti gambar.</span>
                            </div>

                            <input type="file" id="menu-image-input" name="image" accept="image/*" class="hidden">
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Teks Penjelasan Slide (Opsional)</label>
                        <input type="text" name="text" value="{{ old('text') }}" placeholder="Teks instruksi langkah yang tampil di bawah gambar..." class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-blue-800/50">
                        <button type="reset" onclick="resetMenuPreview()" class="px-4 py-2.5 rounded-xl border border-blue-500/30 text-xs font-bold text-blue-200 hover:bg-blue-900/50 transition cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-xs transition shadow-lg shadow-cyan-500/20 border border-cyan-400/30 cursor-pointer flex items-center gap-2" style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Data Menu
                        </button>
                    </div>
                </form>
            </section>

        </main>
    </div>
</div>

<!-- Modal Uji Coba Scanner Gerbang -->
<div id="scannerTestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4 hidden">
    <div class="bg-[#091731] border border-cyan-500/40 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl shadow-cyan-950/50 flex flex-col transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-900/80 to-[#0B1E3F] p-4 sm:p-5 border-b border-blue-500/30 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">Uji Coba Scanner Gerbang</h3>
                    <p class="text-[11px] text-cyan-300/80 font-normal">Uji coba pemindaian kartu gerbang & pencatatan riwayat log</p>
                </div>
            </div>
            <button type="button" id="close-scanner-modal-btn" class="w-8 h-8 rounded-xl bg-blue-950/70 hover:bg-rose-500/20 hover:text-rose-300 text-blue-300 border border-blue-500/30 flex items-center justify-center transition cursor-pointer text-lg font-bold">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            <!-- Alert Hasil Scan Live -->
            <div id="adminScanAlert" class="hidden p-4 rounded-2xl border transition-all text-xs">
                <div class="flex items-start gap-3">
                    <div id="adminScanAlertIcon" class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <h4 id="adminScanAlertTitle" class="font-black text-sm uppercase"></h4>
                            <span id="adminScanAlertBadge" class="text-[10px] font-bold px-2 py-0.5 rounded-full border flex-shrink-0"></span>
                        </div>
                        <p id="adminScanAlertDesc" class="text-xs mt-1 opacity-90"></p>
                        
                        <div class="mt-2.5 pt-2 border-t border-white/10 flex items-center justify-between">
                            <span class="text-[10px] opacity-75">Tersimpan di tabel `visitor_logs`</span>
                            <button type="button" onclick="window.location.reload();" class="text-[10px] font-bold text-cyan-300 hover:text-cyan-200 underline flex items-center gap-1 cursor-pointer">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Segarkan tabel log sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preset Skenario Uji Cepat -->
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 block mb-2.5">Pilih Skenario Uji Cepat:</span>
                <div class="space-y-2">
                    <!-- Skenario 1: Aktif (Hijau) -->
                    <button type="button" onclick="adminQuickScan('3312001001')" class="w-full text-left p-3 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 transition flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 flex-shrink-0 animate-pulse"></span>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-emerald-300 transition">Ahmad Farhan (NIM: 3312001001)</div>
                                <div class="text-[10px] text-emerald-400/80">Status: Aktif (Bisa Masuk)</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30 group-hover:bg-emerald-500 group-hover:text-slate-950 transition">
                            Uji Hijau
                        </span>
                    </button>

                    <!-- Skenario 2: Expired (Abu-abu) -->
                    <button type="button" onclick="adminQuickScan('3312001002')" class="w-full text-left p-3 rounded-xl bg-slate-500/10 hover:bg-slate-500/20 border border-slate-500/30 transition flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-slate-400 flex-shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-slate-300 transition">Budi Santoso (NIM: 3312001002)</div>
                                <div class="text-[10px] text-slate-400/80">Status: Kedaluwarsa (Belum Perpanjang)</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-500/20 text-slate-300 text-[10px] font-bold border border-slate-500/30 group-hover:bg-slate-400 group-hover:text-slate-950 transition">
                            Uji Abu-abu
                        </span>
                    </button>

                    <!-- Skenario 3: Belum Daftar (Merah) -->
                    <button type="button" onclick="adminQuickScan('9999999999')" class="w-full text-left p-3 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 transition flex items-center justify-between cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-rose-500 flex-shrink-0"></span>
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-rose-300 transition">Kode Tidak Dikenal (NIM: 9999999999)</div>
                                <div class="text-[10px] text-rose-400/80">Status: Belum Terdaftar di Sistem</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 text-[10px] font-bold border border-rose-500/30 group-hover:bg-rose-500 group-hover:text-white transition">
                            Uji Merah
                        </span>
                    </button>
                </div>
            </div>

            <!-- Garis Pembatas -->
            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-blue-800/60"></div>
                <span class="flex-shrink mx-3 text-[10px] text-blue-300/60 uppercase font-bold tracking-wider">Atau Masukkan Manual / Tempel Barcode</span>
                <div class="flex-grow border-t border-blue-800/60"></div>
            </div>

            <!-- Manual Input Form -->
            <form id="adminManualScanForm" onsubmit="event.preventDefault(); adminExecuteScan();" class="space-y-3">
                <div>
                    <label for="adminInputScanner" class="block text-[11px] font-bold text-cyan-300 uppercase tracking-wider mb-1.5">Kode Kartu / NIM / RFID</label>
                    <div class="flex gap-2">
                        <input type="text" id="adminInputScanner" placeholder="Contoh: 3312001001 atau scan kartu..." class="flex-1 bg-blue-950/90 border border-blue-500/40 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-blue-300/40 focus:outline-none focus:border-cyan-400 font-mono">
                        <button type="submit" id="adminScanSubmitBtn" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-lg shadow-cyan-500/20 border border-cyan-400/30 cursor-pointer flex items-center gap-1.5 flex-shrink-0" style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Kirim Scan</span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="p-3 rounded-xl bg-blue-950/60 border border-blue-500/20 text-[11px] text-blue-200/70 flex items-start gap-2">
                <svg class="w-4 h-4 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span><strong>Info Hardware:</strong> Pada layar Kiosk publik, pengunjung dapat langsung menempelkan kartu KTP / RFID fisik ke scanner tanpa membuka form ini.</span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-blue-950/50 p-4 border-t border-blue-500/20 flex items-center justify-between">
            <span id="adminScanLoadingText" class="text-[11px] text-cyan-300 hidden flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                Memproses scan ke server...
            </span>
            <button type="button" id="close-scanner-modal-footer-btn" class="ml-auto px-4 py-2 rounded-xl border border-blue-500/30 text-xs font-bold text-blue-200 hover:bg-blue-900/50 transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // 1. Image Preview untuk Upload Slide Menu Baru
    const menuImageInput = document.getElementById('menu-image-input');
    const menuEmptyPrompt = document.getElementById('menu-empty-prompt');
    const menuPreviewWrapper = document.getElementById('menu-preview-wrapper');
    const menuImagePreview = document.getElementById('menu-image-preview');
    const menuFileName = document.getElementById('menu-file-name');
    const menuFileSize = document.getElementById('menu-file-size');

    if (menuImageInput) {
        menuImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    menuImagePreview.src = evt.target.result;
                    menuFileName.innerText = file.name;
                    menuFileSize.innerText = (file.size / 1024).toFixed(1) + ' KB';
                    menuEmptyPrompt.classList.add('hidden');
                    menuPreviewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function resetMenuPreview() {
        if (menuImageInput) menuImageInput.value = '';
        if (menuImagePreview) menuImagePreview.src = '';
        if (menuEmptyPrompt) menuEmptyPrompt.classList.remove('hidden');
        if (menuPreviewWrapper) menuPreviewWrapper.classList.add('hidden');
    }

    // 2. Modal Uji Coba Scanner Gerbang (Admin)
    const scannerModal = document.getElementById('scannerTestModal');
    const openScannerModalBtn = document.getElementById('open-scanner-modal-btn');
    const closeScannerModalBtn = document.getElementById('close-scanner-modal-btn');
    const closeScannerModalFooterBtn = document.getElementById('close-scanner-modal-footer-btn');
    const adminInputScanner = document.getElementById('adminInputScanner');
    const adminScanAlert = document.getElementById('adminScanAlert');
    const adminScanAlertIcon = document.getElementById('adminScanAlertIcon');
    const adminScanAlertTitle = document.getElementById('adminScanAlertTitle');
    const adminScanAlertBadge = document.getElementById('adminScanAlertBadge');
    const adminScanAlertDesc = document.getElementById('adminScanAlertDesc');
    const adminScanLoadingText = document.getElementById('adminScanLoadingText');

    let needReloadOnClose = false;

    function openScannerModal() {
        if (!scannerModal) return;
        scannerModal.classList.remove('hidden');
        if (adminInputScanner) {
            setTimeout(() => adminInputScanner.focus(), 100);
        }
    }

    function closeScannerModal() {
        if (!scannerModal) return;
        scannerModal.classList.add('hidden');
        if (needReloadOnClose) {
            window.location.reload();
        }
    }

    if (openScannerModalBtn) openScannerModalBtn.addEventListener('click', openScannerModal);
    if (closeScannerModalBtn) closeScannerModalBtn.addEventListener('click', closeScannerModal);
    if (closeScannerModalFooterBtn) closeScannerModalFooterBtn.addEventListener('click', closeScannerModal);

    if (scannerModal) {
        scannerModal.addEventListener('click', function(e) {
            if (e.target === scannerModal) {
                closeScannerModal();
            }
        });
    }

    function adminQuickScan(code) {
        if (adminInputScanner) adminInputScanner.value = code;
        adminExecuteScan(code);
    }

    function adminExecuteScan(customCode) {
        const code = (customCode || (adminInputScanner ? adminInputScanner.value : '')).trim();
        if (!code) {
            alert('Silakan masukkan atau pilih kode kartu / NIM terlebih dahulu.');
            return;
        }

        if (adminScanLoadingText) adminScanLoadingText.classList.remove('hidden');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/api/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ kode_scan: code })
        })
        .then(res => res.json())
        .then(data => {
            if (adminScanLoadingText) adminScanLoadingText.classList.add('hidden');
            needReloadOnClose = true;
            showAdminScanResult(data, code);
        })
        .catch(err => {
            console.error('Scan error:', err);
            if (adminScanLoadingText) adminScanLoadingText.classList.add('hidden');
            showAdminScanResult({
                color: 'red',
                name: 'Gangguan Server',
                message: 'Gagal memproses pemindaian'
            }, code);
        });
    }

    function showAdminScanResult(data, rawCode) {
        if (!adminScanAlert) return;
        adminScanAlert.classList.remove('hidden');

        // Reset styling dasar
        adminScanAlert.className = 'p-4 rounded-2xl border transition-all text-xs ';

        if (data.color === 'green') {
            adminScanAlert.classList.add('bg-emerald-500/15', 'border-emerald-500/40', 'text-emerald-200');
            adminScanAlertIcon.className = 'w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-500/20 text-emerald-400';
            adminScanAlertIcon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>';
            adminScanAlertTitle.innerText = data.name || 'Pengunjung Aktif';
            adminScanAlertBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
            adminScanAlertBadge.innerText = 'BISA MASUK (HIJAU)';
            adminScanAlertDesc.innerText = `Kode [${rawCode}]: ${data.message}. Log kunjungan berhasil dicatat ke sistem.`;
        } else if (data.color === 'gray') {
            adminScanAlert.classList.add('bg-slate-500/15', 'border-slate-500/40', 'text-slate-200');
            adminScanAlertIcon.className = 'w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-slate-500/20 text-slate-400';
            adminScanAlertIcon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
            adminScanAlertTitle.innerText = data.name || 'Anggota Kedaluwarsa';
            adminScanAlertBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border bg-slate-500/20 text-slate-300 border-slate-500/40';
            adminScanAlertBadge.innerText = 'BELUM PERPANJANG (ABU-ABU)';
            adminScanAlertDesc.innerText = `Kode [${rawCode}]: ${data.message}. Log kunjungan berhasil dicatat ke sistem.`;
        } else {
            adminScanAlert.classList.add('bg-rose-500/15', 'border-rose-500/40', 'text-rose-200');
            adminScanAlertIcon.className = 'w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 bg-rose-500/20 text-rose-400';
            adminScanAlertIcon.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
            adminScanAlertTitle.innerText = data.name || 'Tidak Dikenal';
            adminScanAlertBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full border bg-rose-500/20 text-rose-300 border-rose-500/40';
            adminScanAlertBadge.innerText = 'BELUM TERDAFTAR (MERAH)';
            adminScanAlertDesc.innerText = `Kode [${rawCode}]: ${data.message}. Log kunjungan berhasil dicatat ke sistem.`;
        }
    }
</script>
@endsection