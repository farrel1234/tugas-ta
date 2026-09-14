@extends('layouts.main')

@section('title', 'Dashboard Admin Digital Signage')

@section('content')
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
                        <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 text-blue-200/70 hover:text-white hover:bg-blue-900/40 font-medium px-4 py-2.5 rounded-xl text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 p-5 rounded-2xl flex items-center justify-between shadow-lg">
                    <div>
                        <span class="text-xs text-cyan-300 font-bold uppercase tracking-wider block mb-1">Total Menu Utama</span>
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
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-black text-white tracking-tight">Daftar Menu Signage</h2>
                        <p class="text-xs text-blue-200/70">Atur urutan menu dan gambar slide yang tampil di layar utama Kiosk.</p>
                    </div>
                    <a href="#form-tambah-menu" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-lg flex items-center gap-2 justify-center cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Menu Baru
                    </a>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-blue-100">
                        <thead class="bg-blue-950/80 uppercase text-[10px] tracking-wider text-cyan-300 border-b border-blue-500/30">
                            <tr>
                                <th class="p-3.5 rounded-l-xl">No</th>
                                <th class="p-3.5">Judul Menu Layanan</th>
                                <th class="p-3.5">Deskripsi Singkat</th>
                                <th class="p-3.5 text-center">Jumlah Langkah</th>
                                <th class="p-3.5 text-center rounded-r-xl">Aksi</th>
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

                                            <!-- Tombol Hapus Menu -->
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Menu \'{{ $menu->title }}\' dan semua slidenya?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 px-3 py-1.5 rounded-lg border border-rose-500/30 font-semibold transition cursor-pointer flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-blue-300/50">Belum ada menu yang tersimpan. Silakan tambahkan menu baru melalui form di bawah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

                    <!-- Upload Gambar Slide Langkah Pertama -->
                    <div class="border border-dashed border-blue-500/40 rounded-2xl p-6 text-center bg-blue-950/40 hover:bg-blue-900/20 transition cursor-pointer">
                        <svg class="w-10 h-10 text-cyan-400 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-bold text-white block mb-1">Unggah Gambar Slide / Ilustrasi Pertama (Opsional)</span>
                        <span class="text-[10px] text-blue-300/70 block mb-3">Format JPG/PNG/WEBP (Rekomendasi rasio landscape 16:9, Max 5MB)</span>
                        <input type="file" name="image" accept="image/*" class="text-xs text-blue-200 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-500 file:text-slate-950 hover:file:bg-cyan-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Teks Penjelasan Slide (Opsional)</label>
                        <input type="text" name="text" value="{{ old('text') }}" placeholder="Teks instruksi langkah yang tampil di bawah gambar..." class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-blue-800/50">
                        <button type="reset" class="px-4 py-2.5 rounded-xl border border-blue-500/30 text-xs font-bold text-blue-200 hover:bg-blue-900/50 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-blue-500 text-slate-950 font-bold text-xs hover:from-cyan-300 hover:to-blue-400 transition shadow-lg">Simpan Data Menu</button>
                    </div>
                </form>
            </section>

        </main>
    </div>
</div>
@endsection