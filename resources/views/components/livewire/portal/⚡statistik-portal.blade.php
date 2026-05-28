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

<div class="px-6 py-6 max-w-4xl mx-auto w-full">
    @if ($loading)
        {{-- Skeleton statistik --}}
        <x-portal.skeleton-stats />
    @else
        <div class="flex justify-center items-stretch gap-0">
            @php
                $stats = [
                    ['value' => number_format($totalPeraturan), 'label' => 'Total Peraturan', 'icon' => 'ti-file-text'],
                    ['value' => $tahunTerbaru, 'label' => 'Tahun Terbaru', 'icon' => 'ti-calendar'],
                    ['value' => $totalKategori, 'label' => 'Kategori', 'icon' => 'ti-category'],
                    [
                        'value' => $totalUnduhan,
                        'label' => 'Total Unduhan',
                        'icon' => 'ti-download',
                    ],
                ];
            @endphp
            @foreach ($stats as $i => $stat)
                <div class="flex-1 text-center px-6 py-1">
                    <div x-data="{
                        current: 0,
                        target: {{ is_numeric($stat['value']) ? $stat['value'] : 0 }},
                        display: 0,
                    
                        start() {
                    
                            // supaya tidak jalan dua kali
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
                    }" x-intersect.once="start()" class="text-5xl font-medium text-gray-900"
                        x-text="display"></div>
                    <div class="text-sm text-gray-400 mt-1 flex items-center justify-center gap-1">
                        <i class="ti {{ $stat['icon'] }} text-sm"></i>
                        {{ $stat['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
