@extends('layouts.main')

@section('title', 'Layar Digital Signage - UPA Perpustakaan Polibatam')

@section('content')
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

        <!-- Main Content Area -->
        <div class="flex flex-col gap-3 flex-grow overflow-hidden relative z-10 my-2 sm:my-3">
            <main id="spotlight-rotator" class="flex flex-col justify-between rounded-2xl sm:rounded-3xl bg-blue-950/30 backdrop-blur-lg border border-blue-500/25 shadow-2xl p-3 sm:p-5 overflow-hidden transition-all duration-700 flex-grow min-h-0">

                <!-- Title & Description Info -->
                <div class="mb-2 border-b border-blue-800/50 pb-2 sm:pb-2.5 flex-shrink-0">
                    <div class="flex items-start sm:items-center justify-between mb-1 gap-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span id="menu-number" class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-tr from-cyan-400 to-blue-500 text-slate-950 font-black text-base sm:text-lg rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
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
        <div id="scanPopup" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-md transition-all duration-300 p-4">
            <div id="popupCard" class="w-full max-w-md rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 transition-all transform duration-300 scale-95 opacity-0 relative overflow-hidden">
                
                <!-- Glow background ornament -->
                <div id="popupGlow" class="absolute -top-20 -left-20 w-44 h-44 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <!-- Icon Header -->
                <div id="popupIconContainer" class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl transition-all">
                    <!-- Checkmark Icon (Hijau) -->
                    <svg id="popupIconGreen" class="w-10 h-10 sm:w-12 sm:h-12 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
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

                <!-- 3-Second Progress Countdown Bar -->
                <div class="w-full h-1.5 bg-black/50 rounded-full overflow-hidden border border-white/10">
                    <div id="popupCountdown" class="h-full rounded-full transition-all duration-[3000ms] ease-linear w-full"></div>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>

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

                        <img src="${currentStepData.img}" alt="Ilustrasi Langkah ${stepIndex + 1}" class="w-full h-full object-contain drop-shadow-xl rounded-xl">

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
    let countdownInterval = null;

    function handleScan(kodeDariScanner) {
        if (!kodeDariScanner || !kodeDariScanner.trim()) return;

        const cleanCode = kodeDariScanner.trim();

        // Tembak API Backend
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/api/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ kode_scan: cleanCode })
        })
        .then(response => {
            if (!response.ok && response.status !== 400) {
                throw new Error('Gagal menghubungi server');
            }
            return response.json();
        })
        .then(data => {
            showAccessPopup(data, cleanCode);
        })
        .catch(err => {
            console.error('Scan error:', err);
            showAccessPopup({
                color: 'red',
                name: 'Gangguan Jaringan',
                message: 'Gagal Scan'
            }, cleanCode);
        });
    }

    function showAccessPopup(data, rawCode) {
        const popup = document.getElementById('scanPopup');
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
        const iconGray = document.getElementById('popupIconGray');
        const iconRed = document.getElementById('popupIconRed');

        if (!popup || !popupCard) return;

        // Reset icon visibility
        if (iconGreen) iconGreen.classList.add('hidden');
        if (iconGray) iconGray.classList.add('hidden');
        if (iconRed) iconRed.classList.add('hidden');

        // Reset countdown animation
        popupCountdown.style.transition = 'none';
        popupCountdown.style.width = '100%';

        // Set teks
        popupNama.innerText = data.name || 'Tidak Dikenal';
        popupPesan.innerText = data.message || 'Status Tidak Diketahui';
        popupKode.innerText = rawCode ? `ID Kartu: ${rawCode}` : '';

        // Reset base classes
        popupCard.className = 'w-full max-w-md rounded-3xl p-6 sm:p-8 text-center shadow-2xl border-2 transition-all transform duration-300 scale-100 opacity-100 relative overflow-hidden ';
        popupIconContainer.className = 'w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full flex items-center justify-center mb-4 border-2 shadow-xl transition-all ';

        if (data.color === 'green') {
            // SKENARIO HIJAU: Bisa Masuk (Aktif)
            popupCard.classList.add('bg-[#032315]/95', 'border-emerald-400', 'shadow-[0_0_60px_rgba(16,185,129,0.35)]');
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-3xl opacity-40 pointer-events-none bg-emerald-500';
            popupIconContainer.classList.add('bg-emerald-500/20', 'border-emerald-400', 'text-emerald-300');
            if (iconGreen) iconGreen.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
            popupBadge.innerText = 'AKSES DIIZINKAN';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-emerald-300';
            popupSubtext.innerText = 'Pintu gerbang otomatis dibuka. Selamat datang di perpustakaan!';
            popupCountdown.className = 'h-full rounded-full transition-all duration-[3000ms] ease-linear bg-emerald-400';

        } else if (data.color === 'gray') {
            // SKENARIO ABU-ABU: Belum Perpanjang (Kedaluwarsa)
            popupCard.classList.add('bg-[#1a212d]/95', 'border-slate-400', 'shadow-[0_0_60px_rgba(148,163,184,0.3)]');
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-3xl opacity-40 pointer-events-none bg-slate-400';
            popupIconContainer.classList.add('bg-slate-500/20', 'border-slate-400', 'text-slate-300');
            if (iconGray) iconGray.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-slate-500/20 text-slate-300 border-slate-500/40';
            popupBadge.innerText = 'KEANGGOTAAN KEDALUWARSA';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-slate-200';
            popupSubtext.innerText = 'Masa aktif kartu Anda telah habis. Silakan perpanjang masa pinjam di meja sirkulasi.';
            popupCountdown.className = 'h-full rounded-full transition-all duration-[3000ms] ease-linear bg-slate-400';

        } else {
            // SKENARIO MERAH: Belum Daftar (Data tidak ditemukan)
            popupCard.classList.add('bg-[#2d0912]/95', 'border-rose-500', 'shadow-[0_0_60px_rgba(244,63,94,0.35)]');
            popupGlow.className = 'absolute -top-20 -left-20 w-44 h-44 rounded-full blur-3xl opacity-40 pointer-events-none bg-rose-500';
            popupIconContainer.classList.add('bg-rose-500/20', 'border-rose-500', 'text-rose-300');
            if (iconRed) iconRed.classList.remove('hidden');

            popupBadge.className = 'text-[10px] sm:text-xs font-black uppercase tracking-widest px-4 py-1 rounded-full border shadow-sm bg-rose-500/20 text-rose-300 border-rose-500/40';
            popupBadge.innerText = 'AKSES DITOLAK';
            popupPesan.className = 'text-2xl sm:text-3xl font-black tracking-tight mb-2 uppercase text-rose-400';
            popupSubtext.innerText = 'Kartu belum terdaftar dalam sistem perpustakaan. Silakan hubungi petugas di meja depan.';
            popupCountdown.className = 'h-full rounded-full transition-all duration-[3000ms] ease-linear bg-rose-500';
        }

        // Tampilkan popup
        popup.classList.remove('hidden');

        // Mulai animasi bar countdown mengecil ke 0% selama 3000ms
        setTimeout(() => {
            popupCountdown.style.transition = 'width 3000ms linear';
            popupCountdown.style.width = '0%';
        }, 50);

        // Bersihkan timer lama jika ada
        if (popupTimer) clearTimeout(popupTimer);

        // Tutup otomatis setelah tepat 3 detik (3000ms)
        popupTimer = setTimeout(() => {
            popupCard.classList.remove('scale-100', 'opacity-100');
            popupCard.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 250);
        }, 3000);
    }

    // Hardware Scanner Listener: Mendeteksi input beruntun cepat dari alat scanner USB RFID/Barcode
    let hardwareBuffer = '';
    let lastKeyTime = 0;

    window.addEventListener('keydown', (e) => {
        // Jangan tangkap jika pengguna sedang mengetik di dalam form/input
        const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
        if (tag === 'input' || tag === 'textarea') return;

        const currentTime = new Date().getTime();

        if (e.key === 'Enter') {
            if (hardwareBuffer.length >= 3) {
                handleScan(hardwareBuffer);
            }
            hardwareBuffer = '';
            return;
        }

        // Karakter scanner umumnya masuk sangat cepat (< 80ms)
        if (currentTime - lastKeyTime > 250) {
            hardwareBuffer = '';
        }

        if (e.key.length === 1) {
            hardwareBuffer += e.key;
            lastKeyTime = currentTime;
        }
    });

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initSignage);
    } else {
        initSignage();
    }
</script>
@endsection