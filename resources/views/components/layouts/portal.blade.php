<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'JDIH LLDIKTI') }}</title>
    {{-- Tailwind CSS v4 --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    {{-- PDF.js (untuk viewer) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.css">
    @livewireStyles
</head>

<body class="bg-white antialiased text-gray-900">

    {{-- Header --}}
    <x-portal.header />

    {{-- Konten utama --}}
    <main class="max-w-full overflow-hidden">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-portal.footer />

    @livewireScripts
</body>

</html>
