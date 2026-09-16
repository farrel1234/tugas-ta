<!DOCTYPE html>
<html lang="id" class="scroll-smooth h-full w-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signage UPA Perpustakaan Polibatam - Modern Slideshow</title>
    
    <!-- Favicon / Icon Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .animate-slide-up {
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#071124] text-white antialiased select-none h-screen w-screen flex flex-col overflow-hidden">
    
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
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="flex flex-col gap-3 flex-grow overflow-hidden relative z-10 my-2 sm:my-3">
            
            <main id="spotlight-rotator" class="flex flex-col justify-between rounded-2xl sm:rounded-3xl bg-blue-950/30 backdrop-blur-lg border border-blue-500/25 shadow-2xl p-3 sm:p-5 overflow-hidden transition-all duration-700 flex-grow min-h-0">
                
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
                                    Layanan Sirkulasi Mandiri
                                </h2>
                            </div>
                        </div>
                        <span id="menu-badge" class="text-[8px] sm:text-[9px] font-semibold bg-blue-900/60 text-cyan-300 border border-blue-500/30 px-2 py-1 rounded-full uppercase tracking-wider hidden sm:inline-block flex-shrink-0">
                            Menu Utama & Alur
                        </span>
                    </div>
                    <p id="menu-desc" class="text-[10px] sm:text-xs text-blue-100/90 font-light leading-relaxed hidden sm:block">
                        Panduan alur layanan sirkulasi mandiri untuk peminjaman, pengembalian, dan perpanjangan masa pinjam koleksi perpustakaan.
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
                <button id="prev-btn" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-900/80 hover:bg-cyan-400 hover:text-slate-950 text-white flex items-center justify-center transition border border-blue-500/30 shadow-lg cursor-pointer active:scale-95 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <div class="flex-grow sm:w-48 lg:w-72 h-1.5 sm:h-2.5 bg-blue-950/80 rounded-full overflow-hidden border border-blue-500/30 shadow-inner">
                    <div id="progress-bar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-400 transition-all duration-300 w-0"></div>
                </div>

                <button id="next-btn" class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-blue-900/80 hover:bg-cyan-400 hover:text-slate-950 text-white flex items-center justify-center transition border border-blue-500/30 shadow-lg cursor-pointer active:scale-95 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </footer>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('live-clock').innerText = `${hours}:${minutes} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener("DOMContentLoaded", function() {
            const libraryData = [
                {
                    num: "01",
                    title: "Layanan Sirkulasi Mandiri",
                    desc: "Layanan sirkulasi mencakup peminjaman, pengembalian, dan perpanjangan masa pinjam koleksi perpustakaan.",
                    isSirkulasiSequence: true,
                    subSections: [
                        {
                            subTitle: "A. Layanan Peminjaman",
                            badge: "Sirkulasi: Peminjaman",
                            desc: "Peminjaman mandiri mencakup data peminjam, buku yang dipinjam, lama waktu, dan waktu pengembalian.",
                            steps: [
                                { text: "Masuk ke Perpustakaan Polibatam.", img: "{{ asset('templating/img/0.png') }}" },
                                { text: "Cari data bibliografi melalui katalog komputer (OPAC).", img: "{{ asset('templating/img/1.png') }}" },
                                { text: "Cari bahan pustaka tersebut pada ruang koleksi.", img: "{{ asset('templating/img/2.png') }}" },
                                { text: "Meminjam buku di meja layanan mandiri dengan kartu anggota.", img: "{{ asset('templating/img/4.png') }}" },
                                { text: "Panduan peminjaman lengkap dapat dilihat pada link: https://lib.polibatam.ac.id/panduan/panduan-peminjaman/. atau scan QR Code di atas menggunakan kamera HP Anda", img: "{{ asset('templating/img/qrpeminjaman.png') }}" }
                            ]
                        },
                        {
                            subTitle: "B. Layanan Pengembalian",
                            badge: "Sirkulasi: Pengembalian",
                            desc: "Pengembalian koleksi mencakup data pengembalian, waktu, serta keterangan denda keterlambatan buku.",
                            steps: [
                                { text: "Masuk ke Perpustakaan Polibatam.", img: "{{ asset('templating/img/0.png') }}" },
                                { text: "Kembalikan buku di meja layanan mandiri dengan kartu anggota masing-masing.", img: "{{ asset('templating/img/5.png') }}" },
                                { text: "Tempatkan buku ke keranjang pengembalian buku yang tersedia.", img: "{{ asset('templating/img/6.png') }}" },
                                { text: "Bila terdapat denda, hubungi staf perpustakaan dan bayar sesuai jumlah denda.", img: "{{ asset('templating/img/7.png') }}" },
                                { text: "Panduan pengembalian lengkap dapat dilihat pada link: https://lib.polibatam.ac.id/panduan/panduan-pengembalian/. atau scan QR Code di atas menggunakan kamera HP Anda", img: "{{ asset('templating/img/qrpengembalian.png') }}" }
                            ]
                        },
                        {
                            subTitle: "C. Layanan Perpanjangan",
                            badge: "Sirkulasi: Perpanjangan",
                            desc: "Kegiatan memperpanjang masa pinjam koleksi maksimal 1x perpanjangan (*jika belum jatuh tempo).",
                            steps: [
                                { text: "Peminjam dapat memperpanjang buku di halaman member aplikasi perpustakaan (online).", img: "{{ asset('templating/img/8.png') }}" },
                                { text: "Atau memperpanjang buku di meja layanan mandiri dengan kartu anggota masing-masing.", img: "{{ asset('templating/img/9.png') }}" }
                            ]
                        }
                    ]
                },
                {
                    num: "02",
                    title: "Dropbox",
                    desc: "Fasilitas kotak drop pengembalian buku praktis di luar jam layanan utama.",
                    steps: [
                        { text: "Masukkan buku yang ingin dikembalikan ke dalam fasilitas dropbox.", img: "{{ asset('templating/img/dropbox-1.png') }}" },
                        { text: "Pastikan buku masuk dengan aman ke dalam kotak drop.", img: "{{ asset('templating/img/dropbox-2.png') }}" },
                        { text: "Petugas akan memproses pengembalian secara berkala.", img: "{{ asset('templating/img/dropbox-3.png') }}" }
                    ]
                },
                {
                    num: "03",
                    title: "Stock Opname",
                    desc: "Proses audit dan pendataan ulang inventaris koleksi buku perpustakaan tahunan.",
                    steps: [
                        { text: "Perhatikan pengumuman resmi jadwal pelaksanaan stock opname di website perpustakaan polibatam.", img: "{{ asset('templating/img/15.png') }}" },
                        { text: "Layanan peminjaman fisik ditutup sementara selama audit.", img: "{{ asset('templating/img/16.png') }}" },
                        { text: "Petugas melakukan pemindaian barcode seluruh koleksi.", img: "{{ asset('templating/img/17.png') }}" }
                    ]
                },
                {
                    num: "04",
                    title: "Layanan Referensi",
                    desc: "Koleksi digital yang sudah kita sediakan termasuk e-journal dan e-majalah.",
                    steps: [
                        { text: "Buku Digital (E-Book): Menyediakan koleksi buku digital akademik lengkap dari berbagai disiplin ilmu untuk menunjang materi perkuliahan.", img: "{{ asset('templating/img/19.png') }}" },
                        { text: "E-Jurnal: Menyediakan akses e-jurnal nasional maupun internasional terkemuka (seperti IEEE, Emerald) untuk memperkaya referensi riset dan tugas akhir.", img: "{{ asset('templating/img/20.png') }}" },
                        { text: "Majalah Digital: Menyediakan berbagai koleksi majalah digital pilihan guna memperluas wawasan umum dan berita industri terkini.", img: "{{ asset('templating/img/21.png') }}" },
                        { text: "Database Ilmiah: Menyediakan akses ke database ilmiah dan repositori institusi yang memuat data riset serta dokumen ilmiah.", img: "{{ asset('templating/img/22.png') }}" },
                        { text: "Tools Referensi: Menyediakan berbagai tools referensi seperti bantuan sitasi otomatis dan fasilitas pengecekan plagiasi untuk penulisan karya ilmiah.", img: "{{ asset('templating/img/23.png') }}" }
                    ]
                },
                {
                    num: "05",
                    title: "Website Perpus",
                    desc: "Portal resmi informasi, layanan online, dan akses katalog digital perpustakaan.",
                    steps: [
                        { text: "Buka peramban web (browser) seperti Google Chrome atau Safari pada perangkat.", img: "{{ asset('templating/img/11.png') }}" },
                        { text: "Masukkan alamat URL resmi perpustakaan yaitu https://lib.polibatam.ac.id pada bilah alamat.", img: "{{ asset('templating/img/12.png') }}" },
                        { text: "Tekan Enter dan halaman utama website UPA Perpustakaan Polibatam siap diakses.", img: "{{ asset('templating/img/14.png') }}" }
                    ]
                },
                {
                    num: "06",
                    title: "Koleksi Perpus",
                    desc: "Akses koleksi pustaka lengkap untuk kebutuhan riset dan pengembangan proyek.",
                    steps: [
                        { text: "Koleksi Cetak: Temukan buku teks dan referensi di rak perpustakaan. Pastikan mencatat nomor panggil dari katalog OPAC.", img: "{{ asset('templating/img/2.png') }}" },
                        { text: "Koleksi Digital: Akses ribuan e-book dan materi digital kapan saja via portal perpustakaan menggunakan akun mahasiswa.", img: "{{ asset('templating/img/25.png') }}" },
                        { text: "Support PBL: Tersedia manual teknis, standar industri, dan laporan riset mahasiswa terdahulu.", img: "{{ asset('templating/img/26.png') }}" }
                    ]
                },
                {
                    num: "07",
                    title: "Sharing Session",
                    desc: "Program sharing session literasi informasi rutin di Perpustakaan Polibatam.",
                    steps: [
                        { text: "Program ini hadir untuk meningkatkan literasi akademik. Silakan daftarkan diri Anda melalui portal.", img: "{{ asset('templating/img/24.png') }}" }
                    ]
                },
                {
                    num: "08",
                    title: "Layanan Disabilitas",
                    desc: "Ruang privasi nyaman dan edukatif bagi pemustaka berkebutuhan khusus.",
                    steps: [
                        { 
                            text: "Layanan disabilitas dilengkapi quiet room (ruang tenang) privat, perangkat akses literasi digital, serta panduan Abjad Isyarat SIBI.", 
                            img: "{{ asset('templating/img/disabilitas.jpeg') }}" 
                        }
                    ]
                },
                {
                    num: "09",
                    title: "Kuesioner Layanan",
                    desc: "Masukan Anda sangat berharga untuk peningkatan kualitas layanan perpustakaan.",
                    steps: [
                        { 
                            text: "Silakan pindai (scan) QR Code di atas menggunakan kamera HP Anda untuk mengisi survei kepuasan layanan UPA Perpustakaan Polibatam. Terima kasih atas partisipasi Anda!", 
                            img: "{{ asset('templating/img/kuisioner.png') }}" 
                        }
                    ]
                },
            ];

            let currentIndex = 0;
            let subIndex = 0;
            let stepIndex = 0;
            
            const slideDuration = 8000; 
            const progressBar = document.getElementById("progress-bar");
            let timerId = null;

            function renderSlide() {
                const item = libraryData[currentIndex];
                
                document.getElementById("menu-number").innerText = item.num;

                let currentTitle = item.title;
                let currentDesc = item.desc;
                let currentBadge = "Menu Utama & Alur";
                let stepsToRender = item.steps;

                if (item.isSirkulasiSequence) {
                    const sub = item.subSections[subIndex];
                    currentTitle = sub.subTitle;
                    currentDesc = sub.desc;
                    currentBadge = sub.badge;
                    stepsToRender = sub.steps;
                }

                document.getElementById("menu-title").innerText = currentTitle;
                document.getElementById("menu-desc").innerText = currentDesc;
                document.getElementById("menu-badge").innerText = currentBadge;

                const currentStepData = stepsToRender[stepIndex];
                const stepsContainer = document.getElementById("steps-container");
                
                // Menata ulang layout menjadi single image presentation
                // KUNCI PERBAIKAN: Menggunakan "flex-1" pada kotak gambar agar tidak pernah tergencet di HP
                stepsContainer.innerHTML = `
                    <div class="w-full h-full flex flex-col justify-center items-center py-1 sm:py-2 animate-fade-in gap-3 sm:gap-4">
                        
                        <!-- Gambar Presentasi (flex-1 otomatis mengisi sisa layar) -->
                        <div class="relative w-full max-w-5xl flex-1 min-h-[150px] bg-blue-900/40 rounded-2xl sm:rounded-3xl overflow-hidden border border-blue-500/30 flex items-center justify-center p-2 sm:p-4 shadow-[0_0_40px_rgba(59,130,246,0.15)] backdrop-blur-sm">
                            
                            <img src="${currentStepData.img}" alt="Ilustrasi Langkah ${stepIndex + 1}" class="w-full h-full object-contain drop-shadow-xl rounded-xl">
                            
                            <!-- Indikator Langkah (Disembunyikan khusus untuk menu 08) -->
                            ${item.num !== "08" ? `
                            <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-blue-950/80 border border-blue-500/50 text-cyan-300 px-3 py-1 sm:px-4 sm:py-1.5 rounded-full text-[10px] sm:text-sm font-bold shadow-lg backdrop-blur-md">
                                Langkah ${stepIndex + 1} / ${stepsToRender.length}
                            </div>
                            ` : ''}
                        </div>

                        <!-- Teks Penjelasan di Bawah (Dibuat flex-shrink-0 agar tidak tergencet) -->
                        <div class="w-full max-w-5xl bg-blue-950/60 border border-blue-500/20 p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-lg backdrop-blur-md animate-slide-up text-center flex-shrink-0">
                            <p class="text-[11px] sm:text-base md:text-lg lg:text-xl text-blue-50 font-medium leading-relaxed">
                                ${currentStepData.text}
                            </p>
                        </div>

                    </div>
                `;
            }

            function goNextStep() {
                const item = libraryData[currentIndex];
                let stepsToRender = item.isSirkulasiSequence ? item.subSections[subIndex].steps : item.steps;

                stepIndex++;
                
                if (stepIndex >= stepsToRender.length) {
                    stepIndex = 0;
                    if (item.isSirkulasiSequence) {
                        subIndex++;
                        if (subIndex >= item.subSections.length) {
                            subIndex = 0;
                            currentIndex = (currentIndex + 1) % libraryData.length;
                        }
                    } else {
                        subIndex = 0;
                        currentIndex = (currentIndex + 1) % libraryData.length;
                    }
                }
                runSignageLoop();
            }

            function goPrevStep() {
                stepIndex--;

                if (stepIndex < 0) {
                    if (libraryData[currentIndex].isSirkulasiSequence) {
                        subIndex--;
                        if (subIndex < 0) {
                            currentIndex = (currentIndex - 1 + libraryData.length) % libraryData.length;
                            let prevItem = libraryData[currentIndex];
                            if (prevItem.isSirkulasiSequence) {
                                subIndex = prevItem.subSections.length - 1;
                                stepIndex = prevItem.subSections[subIndex].steps.length - 1;
                            } else {
                                subIndex = 0;
                                stepIndex = prevItem.steps.length - 1;
                            }
                        } else {
                            stepIndex = libraryData[currentIndex].subSections[subIndex].steps.length - 1;
                        }
                    } else {
                        currentIndex = (currentIndex - 1 + libraryData.length) % libraryData.length;
                        let prevItem = libraryData[currentIndex];
                        if (prevItem.isSirkulasiSequence) {
                            subIndex = prevItem.subSections.length - 1;
                            stepIndex = prevItem.subSections[subIndex].steps.length - 1;
                        } else {
                            subIndex = 0;
                            stepIndex = prevItem.steps.length - 1;
                        }
                    }
                }
                runSignageLoop();
            }

            function runSignageLoop() {
                renderSlide();
                
                progressBar.style.transition = "none";
                progressBar.style.width = "0%";
                
                setTimeout(() => {
                    progressBar.style.transition = `width ${slideDuration}ms linear`;
                    progressBar.style.width = "100%";
                }, 50);

                if (timerId) clearTimeout(timerId);

                timerId = setTimeout(() => {
                    goNextStep();
                }, slideDuration);
            }

            document.getElementById("prev-btn").addEventListener("click", () => goPrevStep());
            document.getElementById("next-btn").addEventListener("click", () => goNextStep());

            runSignageLoop();
        });
    </script>
</body>
</html>