<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Regulation as Peraturan;

new class extends Component {
    use WithPagination;

    public string $keyword = '';
    public string $jenis = '';
    public string $tahun = '';
    public string $topik = '';
    public int $totalHasil = 0;

    protected $listeners = [
        'filter-changed' => 'terapkanFilter',
    ];

    public function terapkanFilter(array $filter): void
    {
        $this->keyword = $filter['keyword'] ?? '';
        $this->jenis = $filter['jenis'] ?? '';
        $this->tahun = $filter['tahun'] ?? '';
        $this->topik = $filter['topik'] ?? '';
        $this->totalHasil = $this->queryPeraturan()->count();
        $this->resetPage();

        // Beritahu Alpine bahwa proses sudah selesai
        $this->dispatch('peraturan-loaded');
    }

    public function mount(): void
    {
        $this->totalHasil = $this->queryPeraturan()->count();
    }

    private function queryPeraturan()
    {
        $query = Peraturan::with(['category', 'activeVersion'])->where('status', 'published');

        if ($this->keyword) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->keyword . '%')->orWhere('number', 'like', '%' . $this->keyword . '%');
            });
        }

        if ($this->jenis) {
            $query->where('category_id', $this->jenis);
        }

        if ($this->tahun) {
            $query->where('year', $this->tahun);
        }

        return $query->latest('publish_date');
    }

    public function getPeraturanProperty()
    {
        return $this->queryPeraturan()->paginate(10);
    }
};
?>

<div class="bg-gray-50 px-6 py-5 border-t border-gray-100 min-h-screen" x-data="{ loading: false }"
    @filter-changed.window="loading = true" @peraturan-loaded.window="loading = false">
    <div class="max-w-5xl mx-auto">

        {{-- Loading skeleton — muncul saat filter ATAU pagination --}}
        <div x-show="loading">
            <div class="text-[12px] text-gray-400 mb-3.5">
                <x-portal.skeleton-bar class="h-3 w-40" />
            </div>
            <div class="space-y-2.5">
                @foreach (range(1, 6) as $i)
                    <x-portal.skeleton-card />
                @endforeach
            </div>
        </div>
        {{-- ← skeleton khusus pagination --}}
        <div wire:loading.block wire:target="nextPage,previousPage,gotoPage" class="w-full mb-3.5">
            <div class="text-[12px] text-gray-400 mb-3.5">
                <x-portal.skeleton-bar class="h-3 w-40" />
            </div>
            <div class="space-y-2.5">
                @foreach (range(1, 6) as $i)
                    <x-portal.skeleton-card />
                @endforeach
            </div>
        </div>
        {{-- Konten aktual --}}
        <div x-show="!loading">
            <div wire:loading.remove>
                {{-- Jumlah hasil --}}
                <div class="text-sm text-gray-500 mb-3.5">
                    Menampilkan
                    <strong class="text-gray-800">{{ $this->peraturan->count() }}</strong>
                    dari
                    <strong class="text-gray-800">{{ number_format($totalHasil) }}</strong>
                    peraturan
                </div>
                @if ($this->peraturan->isEmpty())
                    {{-- Kosong --}}
                    <div class="text-center py-16 text-gray-400">
                        <i class="ti ti-file-off text-4xl mb-3 block"></i>
                        <div class="text-sm">Tidak ada peraturan yang sesuai dengan filter yang dipilih.</div>
                    </div>
                @else
                    {{-- Daftar card --}}
                    <div class="space-y-2.5 mb-5">
                        @foreach ($this->peraturan as $item)
                            <a href="{{ route('portal.detail', $item->slug) }}"
                                class="block bg-white border border-gray-200 rounded-lg px-4 py-4 hover:border-[#185FA5] transition-colors duration-150 no-underline group"
                                wire:key="peraturan-{{ $item->slug }}">
                                <div
                                    class="text-[13px] sm:text-lg font-medium text-[#0A2647] leading-snug group-hover:text-[#185FA5] transition-colors duration-150 uppercase">
                                    {{ $item->title }} Nomor {{ $item->number }}
                                </div>
                                <div class="flex items-center gap-2 mt-2 flex-wrap">
                                    <span
                                        class="text-[12px] text-gray-500 capitalize">{{ $item->category->name ?? '-' }}</span>
                                    <span class="w-0.75 h-0.75 rounded-full bg-gray-300"></span>
                                    <span class="text-[12px] text-gray-500">Tahun {{ $item->year }}</span>
                                    <span class="w-0.75 h-0.75 rounded-full bg-gray-300"></span>
                                    <span class="text-[12px] text-gray-500 flex items-center gap-1">
                                        <i class="ti ti-download text-[12px]"></i>
                                        {{ number_format($item->activeVersion?->download_count ?? 0) }} unduhan
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            {{-- Pagination --}}
            @if ($this->peraturan->hasPages())
                <div class="flex items-center justify-center gap-1.5">

                    {{-- Prev --}}
                    @if ($this->peraturan->onFirstPage())
                        <span
                            class="px-2.5 py-1.5 border border-gray-200 rounded-lg text-gray-300 text-sm cursor-not-allowed">
                            <i class="ti ti-chevron-left"></i>
                        </span>
                    @else
                        <button wire:click="previousPage" wire:loading.attr="disabled"
                            class="px-2.5 py-1.5 border border-gray-200 rounded-lg text-gray-500 text-sm hover:border-[#185FA5] hover:text-[#185FA5] transition-colors">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                    @endif

                    {{-- Halaman --}}
                    @foreach ($this->peraturan->getUrlRange(max(1, $this->peraturan->currentPage() - 2), min($this->peraturan->lastPage(), $this->peraturan->currentPage() + 2)) as $page => $url)
                        @if ($page == $this->peraturan->currentPage())
                            <span
                                class="px-3 py-1.5 bg-[#185FA5] text-white text-sm rounded-lg">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                wire:target="nextPage,previousPage,gotoPage"
                                class="px-3 py-1.5 border border-gray-200 text-gray-600 text-sm rounded-lg hover:border-[#185FA5] hover:text-[#185FA5] transition-colors">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach

                    @if ($this->peraturan->currentPage() + 2 < $this->peraturan->lastPage())
                        <span class="text-gray-400 text-sm px-1">...</span>
                        <button wire:click="gotoPage({{ $this->peraturan->lastPage() }})" wire:loading.attr="disabled"
                            wire:target="nextPage,previousPage,gotoPage"
                            class="px-3 py-1.5 border border-gray-200 text-gray-600 text-sm rounded-lg hover:border-[#185FA5] hover:text-[#185FA5] transition-colors">
                            {{ $this->peraturan->lastPage() }}
                        </button>
                    @endif

                    {{-- Next --}}
                    @if ($this->peraturan->hasMorePages())
                        <button wire:click="nextPage"
                            class="px-2.5 py-1.5 border border-gray-200 rounded-lg text-gray-500 text-sm hover:border-[#185FA5] hover:text-[#185FA5] transition-colors">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    @else
                        <span
                            class="px-2.5 py-1.5 border border-gray-200 rounded-lg text-gray-300 text-sm cursor-not-allowed">
                            <i class="ti ti-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
