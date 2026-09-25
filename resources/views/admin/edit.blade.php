@extends('layouts.main')

@section('title', 'Edit Menu & Kelola Slide - Signage Perpustakaan')

@section('content')
<div class="min-h-screen bg-[#071124] text-white flex flex-col font-sans select-none">

    <!-- Top Navigation Bar -->
    <header class="bg-[#0B1E3F]/80 backdrop-blur-md border-b border-blue-500/20 px-4 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-900/60 rounded-xl border border-blue-500/30 flex items-center justify-center p-1.5 shadow-inner">
                <img src="{{ asset('templating/img/polibatam.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-black tracking-tight text-white uppercase">Kelola Menu & Slide</h1>
                <span class="text-[10px] text-cyan-400 font-semibold tracking-wider block">Menu: {{ $menu->title }}</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-800/60 hover:bg-blue-700 text-cyan-300 border border-blue-500/40 px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </header>

    <div class="flex-1 p-4 sm:p-8 max-w-6xl mx-auto w-full space-y-8">

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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Form Edit Informasi Menu -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-6 shadow-2xl space-y-4">
                    <h2 class="text-base font-black text-white tracking-tight border-b border-blue-800/50 pb-3">Informasi Menu</h2>

                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Nomor Urut Menu *</label>
                            <input type="text" name="num" value="{{ old('num', $menu->num) }}" required class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Judul Menu Layanan *</label>
                            <input type="text" name="title" value="{{ old('title', $menu->title) }}" required class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Deskripsi Layanan</label>
                            <textarea name="desc" rows="3" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">{{ old('desc', $menu->desc) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg shadow-cyan-500/20 border border-cyan-400/30 cursor-pointer flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #06b6d4, #2563eb); color: #ffffff;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan Menu
                        </button>
                    </form>
                </div>

                <!-- Form Upload Slide Baru untuk Menu Ini -->
                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-6 shadow-2xl space-y-4">
                    <h2 class="text-base font-black text-white tracking-tight border-b border-blue-800/50 pb-3">Tambah Slide / Langkah</h2>

                    <form action="{{ route('admin.steps.store', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Upload Gambar Slide dengan Live Preview -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider">Pilih File Gambar Slide *</label>

                            <label for="step-image-input" class="group flex flex-col items-center justify-center border-2 border-dashed border-cyan-500/40 hover:border-cyan-400 rounded-2xl p-4 text-center bg-blue-950/40 hover:bg-blue-900/20 transition cursor-pointer">
                                <div id="step-empty-prompt" class="flex flex-col items-center py-2">
                                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-white block">Klik di sini untuk memilih gambar slide</span>
                                    <span class="text-[10px] text-blue-300/70 block mt-0.5">Format JPG, PNG, WEBP (Max 5MB)</span>
                                </div>

                                <!-- Live Image Preview Box -->
                                <div id="step-preview-wrapper" class="hidden w-full space-y-2">
                                    <div class="relative w-full h-44 bg-blue-950/80 rounded-xl overflow-hidden border border-cyan-500/50 flex items-center justify-center shadow-inner">
                                        <img id="step-image-preview" src="" alt="Pratinjau Slide" class="max-w-full max-h-full object-contain">
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] bg-blue-900/60 px-3 py-1.5 rounded-lg border border-blue-500/30">
                                        <span id="step-file-name" class="font-medium text-cyan-200 truncate max-w-[180px]">nama_file.png</span>
                                        <span id="step-file-size" class="text-blue-300/70 font-mono text-[10px]">0 KB</span>
                                    </div>
                                    <span class="text-[10px] text-cyan-400 font-semibold block text-center">✓ Gambar berhasil dipilih! Klik kembali jika ingin mengganti.</span>
                                </div>

                                <input type="file" id="step-image-input" name="image" required accept="image/*" class="hidden">
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Teks Penjelasan Langkah</label>
                            <textarea name="text" rows="2" placeholder="Jelaskan langkah instruksi ini..." class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Urutan Slide (Opsional)</label>
                            <input type="number" name="step_order" value="{{ ($menu->steps->max('step_order') ?? 0) + 1 }}" min="1" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg shadow-emerald-500/20 border border-emerald-400/30 cursor-pointer flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #10b981, #0d9488); color: #ffffff;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Unggah Slide Baru
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Daftar Slide / Langkah Terdaftar -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-[#0B1E3F]/50 backdrop-blur-lg border border-blue-500/30 rounded-2xl p-6 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between border-b border-blue-800/50 pb-3">
                        <div>
                            <h2 class="text-base font-black text-white tracking-tight">Daftar Slide dalam Menu Ini</h2>
                            <p class="text-xs text-blue-200/70">Total {{ $menu->steps->count() }} slide yang akan tampil bergantian.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($menu->steps as $index => $step)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-blue-950/60 border border-blue-500/20 p-4 rounded-2xl hover:border-blue-500/40 transition">
                                <!-- Nomor urut -->
                                <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-300 font-bold flex items-center justify-center text-xs flex-shrink-0 border border-cyan-500/30">
                                    {{ $step->step_order ?? ($index + 1) }}
                                </div>

                                <!-- Thumbnail Gambar -->
                                <div class="w-28 h-20 bg-blue-900/50 rounded-xl overflow-hidden border border-blue-500/30 flex-shrink-0 flex items-center justify-center relative group">
                                    <img src="{{ $step->image_url }}" alt="Slide" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('templating/img/polibatam.png') }}';">
                                    <button type="button" onclick="document.getElementById('edit-step-modal-{{ $step->id }}').classList.remove('hidden')" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition text-cyan-300 text-[10px] font-bold gap-1 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Ganti
                                    </button>
                                </div>

                                <!-- Teks Penjelasan -->
                                <div class="flex-1 min-w-0">
                                    <span class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider block mb-1">Langkah {{ $step->step_order ?? ($index + 1) }}</span>
                                    <p class="text-xs text-blue-100 leading-relaxed line-clamp-3">
                                        {{ $step->text ?: '(Tidak ada teks penjelasan)' }}
                                    </p>
                                </div>

                                <!-- Tombol Aksi Slide -->
                                <div class="flex-shrink-0 flex items-center gap-2">
                                    <!-- Tombol Edit / Ganti Gambar -->
                                    <button type="button" onclick="document.getElementById('edit-step-modal-{{ $step->id }}').classList.remove('hidden')" class="bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/40 p-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1" title="Ganti Gambar / Edit Slide">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    <!-- Tombol Hapus Slide -->
                                    <form action="{{ route('admin.steps.destroy', $step) }}" method="POST" onsubmit="return confirm('Hapus slide langkah ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 p-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1" title="Hapus Slide">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal Edit / Ganti Gambar Slide -->
                            <div id="edit-step-modal-{{ $step->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                                <div class="bg-[#0B1E3F] border border-blue-500/40 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                                    <div class="flex items-center justify-between border-b border-blue-800/50 pb-3">
                                        <h3 class="text-sm font-black text-white uppercase tracking-wider">Edit / Ganti Gambar Slide Langkah {{ $step->step_order }}</h3>
                                        <button type="button" onclick="document.getElementById('edit-step-modal-{{ $step->id }}').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg font-bold">&times;</button>
                                    </div>

                                    <form action="{{ route('admin.steps.update', $step) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Unggah Gambar Baru (Ganti Gambar)</label>
                                            <input type="file" name="image" accept="image/*" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                                            <span class="text-[10px] text-blue-300/70 block mt-1">Kosongkan jika hanya ingin mengubah teks penjelasan.</span>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Teks Penjelasan Langkah</label>
                                            <textarea name="text" rows="3" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">{{ old('text', $step->text) }}</textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-cyan-300 uppercase tracking-wider mb-2">Nomor Urutan Langkah</label>
                                            <input type="number" name="step_order" value="{{ $step->step_order }}" min="1" class="w-full bg-blue-950/80 border border-blue-500/30 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400">
                                        </div>

                                        <div class="flex items-center justify-end gap-3 pt-2">
                                            <button type="button" onclick="document.getElementById('edit-step-modal-{{ $step->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-blue-900/60 hover:bg-blue-800 text-slate-300 text-xs font-bold transition">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-black transition shadow-lg shadow-cyan-500/20">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-blue-300/50">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                Belum ada slide untuk menu ini. Silakan unggah gambar pada form di samping.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<script>
    const stepInput = document.getElementById('step-image-input');
    const stepEmptyPrompt = document.getElementById('step-empty-prompt');
    const stepPreviewWrapper = document.getElementById('step-preview-wrapper');
    const stepImagePreview = document.getElementById('step-image-preview');
    const stepFileName = document.getElementById('step-file-name');
    const stepFileSize = document.getElementById('step-file-size');

    if (stepInput) {
        stepInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    stepImagePreview.src = evt.target.result;
                    stepFileName.innerText = file.name;
                    stepFileSize.innerText = (file.size / 1024).toFixed(1) + ' KB';
                    stepEmptyPrompt.classList.add('hidden');
                    stepPreviewWrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection