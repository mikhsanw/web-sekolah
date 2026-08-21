<header class="masthead">
    <div class="wadah">
        <a href="{{ route('web.beranda') }}" aria-label="Beranda {{ $identitas['nama'] ?? '' }}">
            <img class="masthead__logo"
                src="{{ $identitas['logo'] ?? asset(config('master.app.web.template').'/img/logo.svg') }}"
                alt="Logo {{ $identitas['nama'] ?? 'sekolah' }}">
        </a>
        <div class="masthead__teks">
            <p class="masthead__nama">{{ $identitas['nama'] ?? config('sekolah.identitas.nama') }}</p>
            <p class="masthead__sub">
                {{ $identitas['nama_panjang'] ?? '' }}
                @if(! empty($identitas['npsn']) && $identitas['npsn'] !== '-')
                    • NPSN {{ $identitas['npsn'] }}
                @endif
            </p>
        </div>
    </div>
</header>
