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

<div class="bg-white lg:px-6 pb-5 pt-20">
    <div class="max-w-5xl mx-auto">

        {{-- Skeleton loading (saat pertama mount) --}}
        @if ($loading)
            <div class="space-y-4">
                <x-portal.skeleton-bar class="h-3 w-32" />
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
            <nav class="flex flex-wrap items-center gap-1.5 text-[12px] text-gray-400 mb-4 px-6 lg:px-0">
                <a href="/" class="text-[#185FA5] text-sm hover:underline flex items-center gap-1 no-underline">
                    <i class="ti ti-home text-sm"></i> Beranda
                </a>
                <i class="ti ti-chevron-right text-sm"></i>
                <span class="text-gray-600 truncate text-sm max-w-xs">Detail</span>
            </nav>

            {{-- Judul --}}
            <div class="bg-white border border-gray-200 rounded-sm px-5 py-4 mb-3.5">
                {{-- FIX: title (bukan judul) --}}
                <h1 class="text-lg sm:text-xl lg:text-2xl font-medium text-gray-900 leading-snug uppercase mb-1">
                    {{ $peraturan->title }}
                </h1>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span class="text-sm capitalize">
                        {{-- FIX: category->name (bukan kategori->nama) --}}
                        {{ $peraturan->category->name ?? '-' }}
                    </span>
                    <span>-</span>
                    {{-- FIX: number (bukan nomor) --}}
                    <span>Nomor {{ $peraturan->number }}</span>
                </div>
            </div>

            {{-- 2 Kolom: Pratinjau (kiri) + Informasi (kanan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3 items-start">
                {{-- ===== KIRI: PDF VIEWER ===== --}}
                <div class="order-2 lg:order-1 col-span-1 lg:col-span-3 bg-white border border-gray-200 rounded-sm p-4">
                    {{-- Header viewer --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div class="lg:text-sm font-medium text-gray-800 flex items-center gap-1.5">
                            <i class="ti ti-file-type-pdf text-gray-500"></i>
                            Pratinjau Dokumen
                        </div>
                        <div class="flex gap-1.5">
                            <button wire:click="setViewerMode('preview')"
                                class="px-3 py-1.5 text-[12px] rounded-sm border transition-colors duration-150 hover:cursor-pointer
                                    {{ $viewerMode === 'preview'
                                        ? 'bg-[#185FA5] text-white border-[#185FA5]'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                Pratinjau
                            </button>
                            <button wire:click="setViewerMode('fullscreen')"
                                class="px-3 py-1.5 text-[12px] rounded-sm border transition-colors duration-150 hover:cursor-pointer
                                    {{ $viewerMode === 'fullscreen'
                                        ? 'bg-[#185FA5] text-white border-[#185FA5]'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                Layar penuh
                            </button>
                        </div>
                    </div>

                    @php
                        // {{-- FIX: activeVersion (bukan dokumenTerbaru) --}}
                        $dokumen = $peraturan->activeVersion;
                        // {{-- FIX: file_path (bukan path_file), akses via controller bukan asset() langsung --}}
                        $pdfUrl = $dokumen ? route('portal.pdf.view', $peraturan->id) : null;
                    @endphp

                    @if ($pdfUrl)
                        {{-- MODE PRATINJAU --}}
                        @if ($viewerMode === 'preview')
                            <div class="border border-gray-100 rounded-lg overflow-hidden">
                                {{-- Toolbar navigasi halaman --}}
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

                                {{-- Canvas PDF.js --}}
                                <div class="bg-[#C0C0C0] px-4 py-4 flex justify-center overflow-auto"
                                    style="min-height: 340px;">
                                    <canvas id="pdf-canvas" class="shadow-sm border border-gray-200"></canvas>
                                </div>
                            </div>
                        @endif

                        {{-- MODE LAYAR PENUH --}}
                        @if ($viewerMode === 'fullscreen')
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                {{-- Toolbar dark --}}
                                <div class="bg-[#1e1e1e] px-3 py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="ti ti-file-type-pdf text-red-400 text-base"></i>
                                        {{-- FIX: file_path (bukan path_file) --}}
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

                                {{-- Canvas PDF.js fullscreen --}}
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

                    {{-- Info file + tombol unduh --}}
                    @if ($dokumen)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-3 px-3 py-2.5 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2.5">
                                <i class="ti ti-file-type-pdf text-red-500 text-xl"></i>
                                <div>
                                    {{-- FIX: basename(file_path) --}}
                                    <div class="text-[12px] font-medium text-gray-800">
                                        {{ basename($dokumen->file_path) }}
                                    </div>
                                    {{-- FIX: file_size_formatted (accessor dari model) --}}
                                    <div class="text-[11px] text-gray-400">
                                        {{ $dokumen->file_size_formatted }}
                                    </div>
                                </div>
                            </div>
                            <button wire:click="unduh" wire:loading.attr="disabled" wire:target="unduh"
                                class="flex items-center gap-1.5 bg-[#185FA5] hover:bg-[#0f4a8a] text-white text-[12px] font-medium px-4 py-2 rounded-sm transition-colors duration-150 disabled:opacity-60">
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

                {{-- ===== KANAN: INFORMASI DOKUMEN ===== --}}
                <div class="order-1 lg:order-2 col-span-1 lg:col-span-2 space-y-3">
                    {{-- Panel informasi --}}
                    <div class="bg-white border border-gray-200 rounded-sm p-4">
                        <div class="text-sm font-medium text-gray-800 mb-3.5 flex items-center gap-1.5">
                            <i class="ti ti-info-circle text-gray-400"></i>
                            Informasi Dokumen
                        </div>

                        @php
                            $metaItems = [
                                [
                                    'icon' => 'ti-file-text',
                                    'label' => 'Jenis Dokumen',
                                    // {{-- FIX: category->name --}}
                                    'value' => $peraturan->category->name ?? '-',
                                ],
                                [
                                    'icon' => 'ti-hash',
                                    'label' => 'Nomor',
                                    // {{-- FIX: number --}}
                                    'value' => $peraturan->number,
                                ],
                                [
                                    'icon' => 'ti-calendar',
                                    'label' => 'Tanggal Terbit',
                                    // {{-- FIX: publish_date (bukan tanggal_terbit) --}}
                                    'value' => $peraturan->publish_date->translatedFormat('d F Y'),
                                ],
                                [
                                    'icon' => 'ti-download',
                                    'label' => 'Total Unduhan',
                                    // {{-- FIX: dari activeVersion->download_count, bukan peraturan->jumlah_unduhan --}}
                                    'value' => number_format($peraturan->activeVersion?->download_count ?? 0) . ' kali',
                                ],
                                [
                                    'icon' => 'ti-circle-check',
                                    'label' => 'Status',
                                    // {{-- FIX: dari enum status, bukan status_label --}}
                                    'value' => $peraturan->status === 'published' ? 'Aktif' : 'Tidak Aktif',
                                ],
                            ];
                        @endphp

                        <div class="divide-y divide-gray-100">
                            @foreach ($metaItems as $item)
                                <div class="flex items-start gap-2.5 py-2.5">
                                    <i class="ti {{ $item['icon'] }} text-gray-400 text-base mt-0.5 shrink-0"></i>
                                    <div>
                                        <div class="text-[11px] text-gray-400">{{ $item['label'] }}</div>
                                        <div class="text-[13px] font-medium text-gray-900 mt-0.5">{{ $item['value'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Badge resmi --}}
                        <div class="mt-3.5 px-3 py-2.5 bg-gray-50 rounded-lg flex items-center gap-2">
                            <i class="ti ti-shield-check text-[#185FA5] text-lg shrink-0"></i>
                            <p class="text-[11px] text-gray-500 leading-relaxed">
                                Dokumen resmi diterbitkan oleh LLDIKTI Wilayah XI Kalimantan
                            </p>
                        </div>
                    </div>

                    {{-- ===== RELASI ANTAR REGULASI ===== --}}
                    @php
                        $outgoing = $peraturan->outgoingRelations;
                        $incoming = $peraturan->incomingRelations;
                        $adaRelasi = $outgoing->isNotEmpty() || $incoming->isNotEmpty();
                    @endphp

                    @if ($adaRelasi)
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="text-[13px] font-medium text-gray-800 mb-3.5 flex items-center gap-1.5">
                                <i class="ti ti-arrows-exchange text-gray-400"></i>
                                Relasi Regulasi
                            </div>

                            <div class="space-y-2">
                                {{-- Outgoing: A mengubah/mencabut B --}}
                                @foreach ($outgoing as $relasi)
                                    <div class="flex items-start gap-2.5 py-2 border-b border-gray-50 last:border-0">
                                        <span
                                            class="mt-0.5 shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded
                                            {{ $relasi->relation_type === 'mencabut' ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-700' }}">
                                            {{ $relasi->label_from_source }}
                                        </span>
                                        <a href="{{ route('portal.detail', $relasi->targetRegulation->id) }}"
                                            class="text-[12px] text-[#185FA5] hover:underline leading-snug">
                                            {{ $relasi->targetRegulation->title }} No.
                                            {{ $relasi->targetRegulation->number }}
                                        </a>
                                    </div>
                                @endforeach

                                {{-- Incoming: A diubah/dicabut oleh B --}}
                                @foreach ($incoming as $relasi)
                                    <div class="flex items-start gap-2.5 py-2 border-b border-gray-50 last:border-0">
                                        <span
                                            class="mt-0.5 shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded
                                            {{ $relasi->relation_type === 'mencabut' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                                            {{ $relasi->label_from_target }}
                                        </span>
                                        <a href="{{ route('portal.detail', $relasi->sourceRegulation->id) }}"
                                            class="text-[12px] text-[#185FA5] hover:underline leading-snug">
                                            {{ $relasi->sourceRegulation->title }} No.
                                            {{ $relasi->sourceRegulation->number }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
                {{-- /KANAN --}}

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
        let scale = 1.2;
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

        // Re-render canvas setelah Livewire update (mode switch)
        Livewire.hook('morph.updated', () => {
            if (pdfDoc) renderPage(pageNum);
        });

        // Trigger unduh file
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
