<footer class="bg-[#0C447C] px-4 sm:px-6 pt-7 pb-4">
    <div class="max-w-5xl mx-auto">

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 lg:gap-x-20">
            {{-- Kolom 1: Identitas --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-cover">
                    </div>
                    <span class="text-sm font-medium text-white">
                        JDIH LLDIKTI Wilayah XI
                    </span>
                </div>
                <p class="text-[12px] text-white/55 leading-relaxed">
                    Lembaga Layanan Pendidikan Tinggi Wilayah XI Kalimantan
                </p>
                <div class="flex items-start gap-2 mt-2 text-[12px] text-white/45">
                    <i class="ti ti-map-pin text-[13px] mt-1"></i>
                    <div>
                        Jl. Adhyaksa, Sungai Miai,<br>
                        Kota Banjarmasin, Kalimantan Selatan
                    </div>
                </div>
            </div>
            {{-- Kolom 2: Kontak --}}
            <div>
                <div class="text-[12px] font-medium text-white/75 mb-3">
                    Kontak
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-mail text-[13px]"></i>
                        <a href="mailto:lldikti11@kemdiktisaintek.go.id" target="_blank" class="text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                            lldikti11@kemdiktisaintek.go.id
                        </a>
                    </div>
                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-phone text-[13px]"></i>
                        <a href="https://wa.me/6281255614411" target="_blank"
                            class="text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                            +62 812-5561-4411
                        </a>
                    </div>
                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-world text-[13px]"></i>
                        <a href="https://maps.app.goo.gl/7QMiE3doYcTbASwx8" target="_blank" class="text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                            Lihat Lokasi
                        </a>
                    </div>
                </div>
            </div>
            {{-- Kolom 3: Tautan --}}
            <div>
                <div class="text-[12px] font-medium text-white/75 mb-3">
                    Ikuti Kami
                </div>
                <div class="flex flex-col gap-2">
                    <a href="https://www.facebook.com/lldiktixi" target="_blank"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                        facebook
                    </a>
                    <a href="https://www.instagram.com/lldiktixi" target="_blank"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                        instagram
                    </a>
                    <a href="https://www.youtube.com/@lldiktixi" target="_blank"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                        Youtube
                    </a>
                    <a href="https://www.tiktok.com/@lldiktixi" target="_blank"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline hover:underline">
                        TikTok
                    </a>
                </div>
            </div>
        </div>
        {{-- Bottom Bar --}}
        <div
            class="border-t border-white/10 pt-3.5 flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">
            <span class="text-[11px] text-white/35">
                &copy; 2026 JDIH LLDIKTI Wilayah XI Kalimantan.
                Hak cipta dilindungi.
            </span>
        </div>

    </div>
</footer>
