<header x-data="{
    lastScroll: 0,
    show: true,
    scrolled: false
}" x-init="window.addEventListener('scroll', () => {
    let current = window.pageYOffset;
    // background saat scroll
    scrolled = current > 20;

    // hide saat scroll bawah
    if (current > lastScroll && current > 100) {
        show = false;
    } else {
        show = true;
    }
    lastScroll = current;
});"
    :class="{
        'bg-[#185FA5]/95 backdrop-blur-md shadow-md': scrolled,
        'bg-transparent': !scrolled,
        '-translate-y-full': !show,
        'translate-y-0': show
    }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 transform">
    <div class="px-6">
        <div class="flex items-center justify-between py-3.5 max-w-5xl mx-auto">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 no-underline">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-cover">
                </div>
                <div>
                    <div class="text-sm font-medium text-white leading-tight">
                        JDIH LLDIKTI
                    </div>
                    <div class="text-[11px] text-white/65">
                        Wilayah XI Kalimantan
                    </div>
                </div>
            </a>

            {{-- Tombol Login --}}
            {{-- <a href=""
                class="flex items-center gap-1.5 bg-white/15 border border-white/30 rounded-lg px-4 py-2 text-white text-sm hover:bg-white/25 transition-colors duration-150">
                <i class="ti ti-lock text-base"></i>
                Login
            </a> --}}
        </div>
    </div>
</header>
