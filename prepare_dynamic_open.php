<?php
$file = '/home/farrel/projects/project-ta/resources/views/kiosk/index.blade.php';
$content = file_get_contents($file);

$oldHeader = <<<'HTML'
                          <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5">
                              <span class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest text-cyan-300">Jam Operasional</span>
                              <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[7px] sm:text-[8px] font-extrabold px-1.5 py-0.5 rounded uppercase">BUKA</span>
                          </div>
                          <span class="text-[9px] sm:text-[11px] font-bold text-white">Senin - Jumat | 08:00 WIB</span>
HTML;

$newHeader = <<<'HTML'
                          <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5">
                              <span class="text-[7px] sm:text-[8px] font-bold uppercase tracking-widest text-cyan-300">Jam Operasional</span>
                              @php
                                  $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
                                  $day = $now->dayOfWeek; // 1 = Senin, 5 = Jumat
                                  $hour = $now->hour;
                                  $isOpen = ($day >= 1 && $day <= 5) && ($hour >= 8 && $hour < 17);
                              @endphp

                              @if($isOpen)
                                  <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[7px] sm:text-[8px] font-extrabold px-1.5 py-0.5 rounded uppercase shadow-[0_0_10px_rgba(16,185,129,0.2)] animate-pulse">BUKA</span>
                              @else
                                  <span class="bg-rose-500/20 text-rose-300 border border-rose-500/40 text-[7px] sm:text-[8px] font-extrabold px-1.5 py-0.5 rounded uppercase shadow-[0_0_10px_rgba(244,63,94,0.2)]">TUTUP</span>
                              @endif
                          </div>
                          <span class="text-[9px] sm:text-[11px] font-bold text-white">Senin - Jumat | 08:00 - 17:00 WIB</span>
HTML;

// Note: I also updated the hardcoded "08:00 WIB" text below it to "08:00 - 17:00 WIB" to be clearer.
// Need to carefully replace only the relevant part. Let's do a more robust regex or direct str_replace if it matches.
if (strpos($content, '<span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40') !== false) {
    // We will find the exact block.
}
?>
