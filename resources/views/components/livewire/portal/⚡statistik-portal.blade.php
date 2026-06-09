<?php

use Livewire\Component;
use App\Models\Regulation as Peraturan;
use App\Models\Category as Kategori;

new class extends Component {
    public bool $loading = true;
    public int $totalPeraturan = 0;
    public int $tahunTerbaru = 0;
    public int $totalKategori = 0;
    public int $totalUnduhan = 0;

    public function mount(): void
    {
        $this->loadStatistik();
    }

    public function loadStatistik(): void
    {
        $this->totalPeraturan = Peraturan::where('status', 'published')->count();
        $this->tahunTerbaru = (int) (Peraturan::where('status', 'published')->max('year') ?? 0);
        $this->totalKategori = Kategori::count();
        $this->totalUnduhan = Peraturan::where('status', 'published')->sum('jumlah_unduhan');

        $this->loading = false;
    }
};
?>

<div class="px-2 sm:px-4 md:px-6 py-6 max-w-4xl mx-auto w-full">
    @if ($loading)
        <x-portal.skeleton-stats />
    @else
        <div class="flex justify-center items-stretch gap-0 w-full">
            @php
                $stats = [
                    [
                        'value' => number_format($totalPeraturan),
                        'label' => 'Total Peraturan',
                        'icon' => 'ti-file-text',
                    ],
                    [
                        'value' => $tahunTerbaru,
                        'label' => 'Tahun Terbaru',
                        'icon' => 'ti-calendar',
                    ],
                    [
                        'value' => $totalKategori,
                        'label' => 'Kategori',
                        'icon' => 'ti-category',
                    ],
                    [
                        'value' => $totalUnduhan,
                        'label' => 'Total Unduhan',
                        'icon' => 'ti-download',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="flex-1 text-center px-1 sm:px-2 md:px-4 lg:px-6 py-1">
                    <div x-data="{
                        current: 0,
                        target: {{ is_numeric($stat['value']) ? $stat['value'] : 0 }},
                        display: 0,
                        start() {
                    
                            if (this.current > 0) return;

                            let duration = 1500;
                            let stepTime = 16;
                            let totalSteps = duration / stepTime;
                            let increment = this.target / totalSteps;
                    
                            let counter = setInterval(() => {
                    
                                this.current += increment;
                    
                                if (this.current >= this.target) {
                                    this.current = this.target;
                                    clearInterval(counter);
                                }
                    
                                let value = Math.floor(this.current);
                    
                                this.display =
                                    '{{ $stat['label'] }}' === 'Tahun Terbaru' ?
                                    value :
                                    value.toLocaleString();
                    
                            }, stepTime);
                        }
                    }" x-intersect.once="start()"
                        class="text-4xl font-medium text-[#0A2647] leading-tight"
                        x-text="display">
                    </div>
                    <div
                        class="text-xs sm:text-sm text-gray-400 mt-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:ppercase sm:font-medium">
                        <i class="ti {{ $stat['icon'] }} text-sm sm:text-xs md:text-sm hidden sm:block"></i>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
