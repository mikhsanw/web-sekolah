@php
    $aset = config('master.app.web.template');
    $identitas = $identitas ?? [];
    $namaSekolah = $identitas['nama'] ?? config('sekolah.identitas.nama');
    $judul = $judulHalaman ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judul ? $judul.' — '.$namaSekolah : $namaSekolah }}</title>
    <meta name="description" content="{{ $deskripsiHalaman ?? config('master.app.profile.description') }}">
    <meta name="keywords" content="{{ config('master.app.profile.keywords') }}">
    <meta name="author" content="{{ $namaSekolah }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $namaSekolah }}">
    <meta property="og:title" content="{{ $judul ?? $namaSekolah }}">
    <meta property="og:description" content="{{ $deskripsiHalaman ?? config('master.app.profile.description') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(! empty($identitas['logo']))
        <meta property="og:image" content="{{ $identitas['logo'] }}">
    @endif
    <link rel="icon" href="{{ pengaturanBerkas('favicon') ?? asset($aset.'/img/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset($aset.'/css/style.css') }}?v={{ filemtime(public_path($aset.'/css/style.css')) }}">
    <style>
        :root {
            --warna-utama: {{ $identitas['warna_utama'] ?? config('sekolah.tampilan.warna_utama') }};
            --warna-aksen: {{ $identitas['warna_aksen'] ?? config('sekolah.tampilan.warna_aksen') }};
        }
    </style>
    @stack('css')
</head>
<body>
    <a href="#konten-utama" class="sr-only">Lompat ke konten utama</a>

    @include('frontend.partials.topbar')
    @include('frontend.partials.masthead')
    @include('frontend.partials.navbar')

    @if($judul)
        @include('frontend.partials.kepala-halaman')
    @endif

    <main class="isi" id="konten-utama">
        @yield('konten')
    </main>

    @include('frontend.partials.footer')

    <script src="{{ asset($aset.'/js/main.js') }}"></script>
    @stack('js')
</body>
</html>
