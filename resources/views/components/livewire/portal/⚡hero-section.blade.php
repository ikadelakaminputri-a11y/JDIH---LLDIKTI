<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Hero Slider --}}
    <div x-data x-init="new Swiper($refs.heroSwiper, {
        modules: [
            window.SwiperModules.Autoplay,
            window.SwiperModules.Pagination,
        ],
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });" class="relative overflow-hidden">

        <div class="swiper lg:h-screen h-80" x-ref="heroSwiper">
            <div class="swiper-wrapper">
                {{-- Slide 1 --}}
                <div class="swiper-slide h-full">
                    <img src="https://lldikti11.kemdiktisaintek.go.id/source/berita/1whatsapp_image_2023-08-22_at_08.37.43.jpeg"
                        class="w-full h-full object-cover object-center" alt="LLDIKTI XI Kalimantan">
                </div>
                {{-- Slide 2 --}}
                <div class="swiper-slide h-full">
                    <img src="https://cdn-jjmn.jawapos.com/images/7/2026/05/01/kolaborasi-kanwil-kemenkum-kalsel-dan-lldikti-xi-kalimantan-berkolaborasi-mematangkan-program-sentra-ki-dan-magang-posbankum-yang-melibatkan-50-perguruan-tinggifoto-foto-kanwil-kemenkum-kalsel-nVm8i.webp"
                        class="w-full h-full object-cover object-center" alt="Kolaborasi LLDIKTI XI">
                </div>
                {{-- Slide 3 --}}
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1562774053-701939374585"
                        class="w-full h-full object-cover object-center" alt="Pendidikan Tinggi">
                </div>
            </div>

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/50 z-10"></div>

            {{-- Text --}}
            <div class="absolute inset-0 z-20 flex items-center justify-center px-4 sm:px-8">
                <div class="text-center max-w-3xl">
                    <h1
                        class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold text-white mb-2 sm:mb-3 leading-tight">
                        JARINGAN DOKUMENTASI & INFORMASI HUKUM
                    </h1>
                    <p class="text-white/80 text-sm sm:text-base md:text-lg">
                        Lembaga Layanan Pendidikan Tinggi Wilayah XI Kalimantan
                    </p>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="swiper-pagination bottom-4!"></div>
        </div>
    </div>
</div>
