<?php

use Livewire\Component;
use App\Models\Regulation as Peraturan;

new class extends Component {
    public Peraturan $peraturan;
    public bool $loading = true;
    public string $viewerMode = 'preview';

    public function mount(int $id): void
    {
        $this->peraturan = Peraturan::with(['category', 'activeVersion', 'outgoingRelations.targetRegulation.category', 'incomingRelations.sourceRegulation.category'])
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();
        $this->loading = false;
    }

    public function setViewerMode(string $mode): void
    {
        $this->viewerMode = in_array($mode, ['preview', 'fullscreen']) ? $mode : 'preview';
    }

    public function unduh(): void
    {
        $dokumen = $this->peraturan->activeVersion;
        if ($dokumen) {
            $dokumen->incrementDownload();
            $this->dispatch('mulai-unduh', url: route('portal.download', $this->peraturan->id));
        }
    }
};
?>

<div class="bg-gray-50 min-h-screen">
    {{-- ===== HERO HEADER ===== --}}
    <div class="relative bg-[#185FA5] h-[20vh] lg:h-[25vh] min-h-37.5 flex items-end overflow-hidden">
        <div class="absolute inset-0 bg-linear-to-br from-[#0a2d52] via-[#185FA5] to-[#1e7abf]"></div>
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        @if (!$loading)
            <div class="relative z-10 w-full max-w-5xl mx-auto px-4 lg:px-6 pb-6 pt-16">
                <div class="text-white/60 text-sm mb-1.5 uppercase tracking-widest">
                    {{ $peraturan->category->name ?? '-' }} Nomor {{ $peraturan->number }}
                </div>
                <h1 class="text-white text-xl  font-semibold leading-snug uppercase line-clamp-2">
                    {{ $peraturan->title }}
                </h1>
            </div>
        @else
            <div class="relative z-10 w-full max-w-5xl mx-auto px-4 lg:px-6 pb-6 pt-16 space-y-2">
                <div class="h-3 w-32 bg-white/20 rounded animate-pulse"></div>
                <div class="h-5 w-3/4 bg-white/20 rounded animate-pulse"></div>
                <div class="h-5 w-1/2 bg-white/20 rounded animate-pulse"></div>
            </div>
        @endif
    </div>

    {{-- ===== KONTEN UTAMA ===== --}}
    <div class="max-w-5xl lg:mx-auto lg:px-6 py-5">
        @if ($loading)
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-3">
                    <x-portal.skeleton-bar class="h-3 w-24" />
                    <x-portal.skeleton-bar class="h-5 w-full" />
                    <x-portal.skeleton-bar class="h-5 w-4/5" />
                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div class="col-span-3 bg-white border border-gray-200 rounded-xl p-5 h-96">
                        <x-portal.skeleton-bar class="h-full w-full rounded-lg" />
                    </div>
                    <div class="col-span-2 bg-white border border-gray-200 rounded-xl p-5 space-y-4">
                        @foreach (range(1, 5) as $i)
                            <div class="space-y-1.5">
                                <x-portal.skeleton-bar class="h-3 w-20" />
                                <x-portal.skeleton-bar class="h-4 w-36" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            {{-- Breadcrumb --}}
            <nav class="flex flex-wrap px-4 items-center gap-1.5 text-gray-400 mb-4">
                <a href="/" class="text-[#185FA5] text-lg hover:underline flex items-center gap-1 no-underline">
                    Beranda
                </a>
                <i class="ti ti-chevron-right text-sm"></i>
                <span class="text-gray-600 truncate text-sm max-w-xs">Detail</span>
            </nav>
            {{-- 2 Kolom --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-start">
                {{-- ===== KIRI: PDF VIEWER ===== --}}
                <div class="order-2 lg:order-1 col-span-1 lg:col-span-3 bg-white border border-gray-200 rounded-sm p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div class="text-lg font-medium text-gray-800 flex items-center gap-1.5 uppercase">
                            <i class="ti ti-file-type-pdf text-gray-500 hidden sm:block"></i>
                            Pratinjau <span class="text-gray-500">Dokumen</span>
                        </div>
                        <div class="flex gap-1.5">
                            <button wire:click="setViewerMode('preview')"
                                class="px-3 py-1.5 text-sm rounded-sm border transition-colors duration-150 hover:cursor-pointer
                                    {{ $viewerMode === 'preview'
                                        ? 'bg-[#185FA5] text-white border-[#185FA5]'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                Pratinjau
                            </button>
                            <button wire:click="setViewerMode('fullscreen')"
                                class="px-3 py-1.5 text-sm rounded-sm border transition-colors duration-150 hover:cursor-pointer
                                    {{ $viewerMode === 'fullscreen'
                                        ? 'bg-[#185FA5] text-white border-[#185FA5]'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                Layar penuh
                            </button>
                        </div>
                    </div>

                    @php
                        $dokumen = $peraturan->activeVersion;
                        $pdfUrl = $dokumen ? route('portal.pdf.view', $peraturan->id) : null;
                    @endphp

                    @if ($pdfUrl)
                        @if ($viewerMode === 'preview')
                            <div class="border border-gray-100 rounded-lg overflow-hidden">
                                <div
                                    class="bg-gray-50 border-b border-gray-100 px-3 py-1.5 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <button onclick="prevPage()"
                                            class="px-2 py-1 border border-gray-200 rounded-md text-[11px] text-gray-500 hover:border-gray-300 transition-colors">
                                            <i class="ti ti-chevron-left"></i>
                                        </button>
                                        <span class="text-[11px] text-gray-500">
                                            Halaman <span id="page-num" class="font-medium text-gray-800">1</span> /
                                            <span id="page-count" class="text-gray-800">-</span>
                                        </span>
                                        <button onclick="nextPage()"
                                            class="px-2 py-1 border border-gray-200 rounded-md text-[11px] text-gray-500 hover:border-gray-300 transition-colors">
                                            <i class="ti ti-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <button onclick="zoomOut()"
                                            class="px-2 py-1 border border-gray-200 rounded-md text-[11px] text-gray-500 hover:border-gray-300 transition-colors">
                                            <i class="ti ti-zoom-out"></i>
                                        </button>
                                        <span id="zoom-level"
                                            class="text-[11px] text-gray-500 min-w-9 text-center">100%</span>
                                        <button onclick="zoomIn()"
                                            class="px-2 py-1 border border-gray-200 rounded-md text-[11px] text-gray-500 hover:border-gray-300 transition-colors">
                                            <i class="ti ti-zoom-in"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="bg-[#C0C0C0] px-4 py-4 flex justify-center overflow-auto"
                                    style="min-height: 340px;">
                                    <canvas id="pdf-canvas" class="shadow-sm border border-gray-200"></canvas>
                                </div>
                            </div>
                        @endif

                        @if ($viewerMode === 'fullscreen')
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-[#1e1e1e] px-3 py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="ti ti-file-type-pdf text-red-400 text-base"></i>
                                        <span class="text-[12px] text-white/70 truncate max-w-xs">
                                            {{ basename($dokumen->file_path) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5">
                                            <button onclick="prevPage()"
                                                class="text-white/60 hover:text-white text-sm transition-colors">
                                                <i class="ti ti-chevron-left"></i>
                                            </button>
                                            <span class="text-[11px] text-white/50">
                                                Hal. <span id="page-num-fs">1</span> / <span id="page-count-fs">-</span>
                                            </span>
                                            <button onclick="nextPage()"
                                                class="text-white/60 hover:text-white text-sm transition-colors">
                                                <i class="ti ti-chevron-right"></i>
                                            </button>
                                        </div>
                                        <button wire:click="setViewerMode('preview')"
                                            class="bg-white/10 hover:bg-white/20 border-none text-white text-[11px] px-2.5 py-1 rounded transition-colors">
                                            <i class="ti ti-x"></i> Tutup
                                        </button>
                                    </div>
                                </div>
                                <div class="bg-[#555] px-4 py-5 flex justify-center overflow-auto"
                                    style="min-height: 440px;">
                                    <canvas id="pdf-canvas" class="shadow-md"></canvas>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="flex flex-col items-center justify-center h-48 text-gray-400 bg-gray-50 rounded-lg">
                            <i class="ti ti-file-off text-3xl mb-2"></i>
                            <span class="text-sm">File PDF belum tersedia</span>
                        </div>
                    @endif

                    @if ($dokumen)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-3 px-3 py-2.5 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2.5">
                                <i class="ti ti-file-type-pdf text-red-500 text-xl"></i>
                                <div>
                                    <div class="text-[12px] font-medium text-gray-800">
                                        {{ basename($dokumen->file_path) }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ $dokumen->file_size_formatted }}
                                    </div>
                                </div>
                            </div>
                            <button wire:click="unduh" wire:loading.attr="disabled" wire:target="unduh"
                                class="flex items-center gap-1.5 bg-[#185FA5] hover:bg-[#0f4a8a] text-white text-sm font-medium px-4 py-2 rounded-sm transition-colors duration-150 disabled:opacity-60">
                                <span wire:loading.remove wire:target="unduh">
                                    <i class="ti ti-download"></i> Unduh PDF
                                </span>
                                <span wire:loading.inline wire:target="unduh">
                                    <i class="ti ti-loader-2 animate-spin"></i> Mengunduh...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- ===== KANAN: INFORMASI + STATUS ===== --}}
                <div class="order-1 lg:order-2 col-span-1 lg:col-span-2 space-y-3">

                    {{-- Informasi Dokumen --}}
                    <div class="bg-white border border-gray-200 rounded-sm p-4">
                        <div class="text-lg font-medium text-gray-800 mb-3.5 flex items-center gap-1.5 uppercase border-b border-gray-300 pb-4">
                            <i class="ti ti-info-circle text-[#0A2647] hidden sm:block"></i>
                            Informasi <span class="text-gray-500">Peraturan</span>
                        </div>

                        @php
                            $metaItems = [
                                [
                                    'icon' => 'ti-file-text',
                                    'label' => 'Jenis Dokumen',
                                    'value' => $peraturan->category->name ?? '-',
                                ],
                                ['icon' => 'ti-hash', 'label' => 'Nomor', 'value' => $peraturan->number],
                                [
                                    'icon' => 'ti-calendar',
                                    'label' => 'Tanggal Terbit',
                                    'value' => $peraturan->publish_date->translatedFormat('d F Y'),
                                ],
                                [
                                    'icon' => 'ti-download',
                                    'label' => 'Total Unduhan',
                                    'value' => number_format($peraturan->activeVersion?->download_count ?? 0) . ' kali',
                                ],
                                [
                                    'icon' => 'ti-circle-check',
                                    'label' => 'Status',
                                    'value' => $peraturan->status === 'published' ? 'Aktif' : 'Tidak Aktif',
                                ],
                            ];
                        @endphp

                        <div class="divide-y divide-gray-100">
                            @foreach ($metaItems as $item)
                                <div class="flex items-start gap-2.5 py-2.5">
                                    <i class="ti {{ $item['icon'] }} text-gray-400 text-xl mt-0.5 shrink-0 hidden sm:block"></i>
                                    <div>
                                        <div class="text-sm text-gray-400 font-medium">{{ $item['label'] }}</div>
                                        <div class="text-sm  text-[#0A2647] mt-0.5">{{ $item['value'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Peraturan --}}
                    @php
                        $outgoing = $peraturan->outgoingRelations;
                        $incoming = $peraturan->incomingRelations;
                        $adaRelasi = $outgoing->isNotEmpty() || $incoming->isNotEmpty();
                    @endphp

                    @if ($adaRelasi)
                        <div class="bg-white border border-gray-200 rounded-sm p-4">
                            <div class="text-lg uppercase font-medium text-gray-800 mb-3.5 flex items-center gap-1.5 border-b border-gray-300 pb-4">
                                <i class="ti ti-git-branch text-gray-400 hidden sm:block"></i>
                                Status <span class="text-gray-500">Peraturan</span>
                            </div>

                            <div class="space-y-3">
                                @foreach ($outgoing->groupBy('relation_type') as $type => $items)
                                    <div>
                                        <div class="bg-[#F1F4FF] px-3 py-2 text-sm font-medium text-gray-700 mb-2">
                                            {{ match ($type) {
                                                'mengubah' => 'Mengubah :',
                                                'mencabut' => 'Mencabut :',
                                                'dicabut_sebagian' => 'Mencabut sebagian :',
                                                default => $type . ' :',
                                            } }}
                                        </div>
                                        <div class="px-3 space-y-2.5">
                                            @foreach ($items as $relasi)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-500 shrink-0 mt-0.5">
                                                        {{ chr(96 + $loop->index + 1) }}.
                                                    </span>
                                                    <div class="text-sm leading-snug">
                                                        <a href="{{ route('portal.detail', $relasi->targetRegulation->id) }}"
                                                            class="hover:underline capitalize text-red-400">
                                                            {{ $relasi->targetRegulation->title }}
                                                            No. {{ $relasi->targetRegulation->number }} Tahun
                                                            {{ $relasi->targetRegulation->year }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($incoming->groupBy('relation_type') as $type => $items)
                                    <div>
                                        <div class="bg-[#F1F4FF] px-3 py-2 text-sm font-medium text-gray-700 mb-2">
                                            {{ match ($type) {
                                                'mengubah' => 'Diubah dengan :',
                                                'mencabut' => 'Dicabut oleh :',
                                                'dicabut_sebagian' => 'Dicabut sebagian oleh :',
                                                default => $type . ' :',
                                            } }}
                                        </div>
                                        <div class="px-3 space-y-2.5">
                                            @foreach ($items as $relasi)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-500 shrink-0 mt-0.5">
                                                        {{ chr(96 + $loop->index + 1) }}.
                                                    </span>
                                                    <div class="text-sm leading-snug">
                                                        <a href="{{ route('portal.detail', $relasi->sourceRegulation->id) }}"
                                                            style="color: #dc2626;"
                                                            class="hover:underline font-medium">
                                                            {{ $relasi->sourceRegulation->title }}
                                                            Nomor {{ $relasi->sourceRegulation->number }} Tahun
                                                            {{ $relasi->sourceRegulation->year }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- PDF.js Script --}}
@if (!$loading && isset($pdfUrl) && $pdfUrl)
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        let pdfDoc = null;
        let pageNum = 1;
        let scale = 1;
        const pdfUrl = @json($pdfUrl);

        function renderPage(num) {
            pdfDoc.getPage(num).then(page => {
                const canvas = document.getElementById('pdf-canvas');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const viewport = page.getViewport({
                    scale
                });
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                page.render({
                    canvasContext: ctx,
                    viewport
                });

                ['page-num', 'page-num-fs'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = num;
                });
                ['page-count', 'page-count-fs'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = pdfDoc.numPages;
                });
                const zl = document.getElementById('zoom-level');
                if (zl) zl.textContent = Math.round(scale * 100) + '%';
            });
        }

        function prevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            renderPage(pageNum);
        }

        function nextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            renderPage(pageNum);
        }

        function zoomIn() {
            scale = Math.min(scale + 0.2, 3.0);
            renderPage(pageNum);
        }

        function zoomOut() {
            scale = Math.max(scale - 0.2, 0.4);
            renderPage(pageNum);
        }

        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdfDoc = pdf;
            renderPage(pageNum);
        });

        Livewire.hook('morph.updated', () => {
            if (pdfDoc) renderPage(pageNum);
        });

        Livewire.on('mulai-unduh', ({
            url
        }) => {
            const a = document.createElement('a');
            a.href = url;
            a.setAttribute('download', '');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    </script>
@endif
