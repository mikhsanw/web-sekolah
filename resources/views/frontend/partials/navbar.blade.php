<nav class="navbar tanpa-cetak" aria-label="Navigasi utama">
    <div class="wadah">
        <ul class="nav-menu" id="menu-utama">
            @foreach($menuWeb ?? [] as $item)
                <li class="{{ $item['aktif'] || collect($item['anak'])->contains('aktif', true) ? 'aktif' : '' }}">
                    <a href="{{ $item['url'] }}">
                        {{ $item['label'] }}
                        @if(! empty($item['anak']))
                            <x-ikon nama="panah-bawah" ukuran="12" class="panah" />
                        @endif
                    </a>
                    @if(! empty($item['anak']))
                        <ul class="submenu">
                            @foreach($item['anak'] as $anak)
                                <li><a href="{{ $anak['url'] }}">{{ $anak['label'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="nav-alat">
            <button type="button" class="nav-tombol" data-toggle-cari aria-expanded="false"
                aria-controls="panel-pencarian">
                <x-ikon nama="cari" ukuran="17" />
                <span class="cari-label">Cari</span>
            </button>
            <button type="button" class="nav-tombol nav-hamburger" data-toggle-menu aria-expanded="false"
                aria-controls="menu-utama" aria-label="Buka menu">
                <x-ikon nama="menu" ukuran="20" />
            </button>
        </div>
    </div>
</nav>

<div class="pencarian-panel {{ request()->routeIs('web.pencarian') ? 'tampil' : '' }}" id="panel-pencarian">
    <div class="wadah">
        <form class="pencarian-form" action="{{ route('web.pencarian') }}" method="get" role="search">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita, agenda, prestasi, halaman…"
                aria-label="Kata kunci pencarian">
            <button type="submit">Cari</button>
        </form>
    </div>
</div>
