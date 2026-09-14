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

                <div class="flex-grow sm:w-48 lg:w-72 h-1.5 sm:h-2.5 bg-blue-950/80 rounded-full overflow-hidden border border-blue-500/30 shadow-inner">
                    <div id="progress-bar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-400 transition-all duration-300 w-0"></div>
                </div>

                <button id="next-btn" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-900/80 hover:bg-cyan-400 hover:text-slate-950 text-white flex items-center justify-center transition border border-blue-500/30 shadow-lg cursor-pointer active:scale-95 flex-shrink-0" aria-label="Selanjutnya">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </footer>
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

    document.addEventListener("DOMContentLoaded", function() {
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

                setTimeout(() => {
                    progressBar.style.transition = `width ${slideDuration}ms linear`;
                    progressBar.style.width = "100%";
                }, 50);
            }

            if (timerId) clearTimeout(timerId);

            timerId = setTimeout(() => {
                goNextStep();
            }, slideDuration);
        }

        document.getElementById("prev-btn").addEventListener("click", () => goPrevStep());
        document.getElementById("next-btn").addEventListener("click", () => goNextStep());

        // Keyboard navigasi: tombol panah kiri / kanan
        document.addEventListener("keydown", function(e) {
            if (e.key === "ArrowRight" || e.key === "PageDown") {
                goNextStep();
            } else if (e.key === "ArrowLeft" || e.key === "PageUp") {
                goPrevStep();
            }
        });

        runSignageLoop();
    });
</script>
@endsection