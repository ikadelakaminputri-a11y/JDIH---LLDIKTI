<?php

use Livewire\Component;
use App\Models\Regulation;
use App\Models\Category;

new class extends Component {
    public string $keyword = '';
    public string $jenis = '';
    public string $tahun = '';
    public array $daftarKategori = [];
    public array $daftarTahun = [];

    public function mount(): void
    {
        $this->daftarKategori = Category::orderBy('name')->pluck('name', 'id')->toArray();
        $this->daftarTahun = Regulation::where('status', 'published')->selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year')->toArray();
    }

    public function updated(): void
    {
        $this->dispatchFilter();
    }

    public function resetFilter(): void
    {
        $this->reset(['keyword', 'jenis', 'tahun']);
        $this->dispatchFilter();
    }

    private function dispatchFilter(): void
    {
        $this->dispatch('filter-changed', [
            'keyword' => $this->keyword,
            'jenis' => $this->jenis,
            'tahun' => $this->tahun,
        ]);
    }
};
?>

<div class="px-6 py-6 max-w-5xl mx-auto w-full">
    <div class="border border-gray-200 rounded-xl overflow-hidden">

        {{-- Baris 1: Input pencarian --}}
        <div class="flex items-center gap-3 px-4 py-2.5 border-b border-gray-100">
            <i class="ti ti-search text-gray-400 text-lg shrink-0"></i>
            <input type="text" wire:model.live.debounce.400ms="keyword" placeholder="Cari judul atau nomor peraturan..."
                class="flex-1 border-none outline-none text-sm text-gray-900 placeholder-gray-400 bg-transparent py-0.5">
            {{-- Tombol clear keyword --}}
            @if ($keyword)
                <button wire:click="$set('keyword', '')" class="text-gray-300 hover:text-gray-500 transition-colors">
                    <i class="ti ti-x text-sm"></i>
                </button>
            @endif
        </div>

        {{-- Baris 2: Filter select --}}
        <div class="flex items-center gap-0 px-4 py-3">

            {{-- Jenis Kategori --}}
            <div class="flex items-center gap-1.5 flex-1">
                <i class="ti ti-file-description text-gray-400 text-sm shrink-0"></i>
                <span class="text-sm text-gray-500 font-medium whitespace-nowrap">Jenis Kategori:</span>
                <select wire:model.live="jenis"
                    class="flex-1 border-none outline-none text-sm text-gray-700 bg-transparent cursor-pointer min-w-0">
                    <option value="">Semua</option>
                    @foreach ($daftarKategori as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-px h-5 bg-gray-100 shrink-0 mx-2"></div>

            {{-- Tahun --}}
            <div class="flex items-center gap-1.5 flex-1">
                <i class="ti ti-calendar text-gray-400 text-sm shrink-0"></i>
                <span class="text-sm text-gray-500 font-medium whitespace-nowrap">Tahun:</span>
                <select wire:model.live="tahun"
                    class="flex-1 border-none outline-none text-sm text-gray-700 bg-transparent cursor-pointer min-w-0">
                    <option value="">Semua</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-px h-5 bg-gray-100 shrink-0 mx-2"></div>

            {{-- Reset --}}
            <button wire:click="resetFilter"
                class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-red-500 hover:cursor-pointer transition-colors duration-150">
                <i class="ti ti-refresh text-sm" wire:loading.class="animate-spin" wire:target="resetFilter"></i>
                Reset
            </button>
        </div>
    </div>
</div>
