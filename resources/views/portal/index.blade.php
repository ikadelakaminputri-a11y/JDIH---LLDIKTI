<x-layouts.portal>
    {{-- 1. Gambar kantor + judul portal --}}
    @livewire('livewire.portal.hero-section')
    {{-- 2. Statistik --}}
    @livewire('livewire.portal.statistik-portal')
    {{-- 3. Search & Filter --}}
    @livewire('livewire.portal.search-filter')
    {{-- 4. Daftar Peraturan --}}
    @livewire('livewire.portal.daftar-peraturan')
</x-layouts.portal>
