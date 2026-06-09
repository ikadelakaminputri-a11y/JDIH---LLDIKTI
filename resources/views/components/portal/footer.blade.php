<footer class="bg-[#0C447C] px-4 sm:px-6 pt-7 pb-4">
    <div class="max-w-5xl mx-auto">

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

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
                    Lembaga Layanan Pendidikan Tinggi Wilayah XI Kalimantan<br>
                    Kementerian Pendidikan Tinggi, Sains, dan Teknologi
                </p>

                <div class="flex items-center gap-1.5 mt-2 text-[12px] text-white/45">
                    <i class="ti ti-map-pin text-[13px]"></i>
                    Banjarmasin, Kalimantan Selatan
                </div>
            </div>

            {{-- Kolom 2: Tautan --}}
            <div>
                <div class="text-[12px] font-medium text-white/75 mb-3">
                    Tautan
                </div>
                <div class="flex flex-col gap-2">
                    <a href="/"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline">
                        Beranda
                    </a>
                    <a href=""
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline">
                        Daftar Peraturan
                    </a>
                    <a href="#"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline">
                        Tentang JDIH
                    </a>
                    <a href="#"
                        class="text-[12px] text-white/50 hover:text-white/80 transition-colors no-underline">
                        Kontak
                    </a>
                </div>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div>
                <div class="text-[12px] font-medium text-white/75 mb-3">
                    Kontak
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-mail text-[13px]"></i>
                        jdih@lldiktixi.ac.id
                    </div>

                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-phone text-[13px]"></i>
                        (0536) 123-4567
                    </div>

                    <div class="flex items-center gap-1.5 text-[12px] text-white/50">
                        <i class="ti ti-world text-[13px]"></i>
                        lldiktixi.kemdikbud.go.id
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div
            class="border-t border-white/10 pt-3.5 flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">

            <span class="text-[11px] text-white/35">
                &copy; {{ date('Y') }} JDIH LLDIKTI Wilayah XI Kalimantan.
                Hak cipta dilindungi.
            </span>
        </div>

    </div>
</footer>
