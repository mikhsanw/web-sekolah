<div class="kepala-halaman tanpa-cetak">
    <div class="wadah">
        <h1>{{ $judulHalaman }}</h1>
        <div class="remah">
            <a href="{{ route('web.beranda') }}">Beranda</a>
            @foreach($breadcrumb ?? [] as $label => $url)
                <span>
                    @if($url)
                        <a href="{{ $url }}">{{ $label }}</a>
                    @else
                        {{ $label }}
                    @endif
                </span>
            @endforeach
        </div>
    </div>
</div>
