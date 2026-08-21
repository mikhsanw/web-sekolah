<div class="topbar tanpa-cetak">
    <div class="wadah">
        <div class="topbar__tanggal">
            <x-ikon nama="kalender" ukuran="15" />
            <span data-tanggal>{{ tanggalIndo(now()) }}</span>
            <span aria-hidden="true">•</span>
            <x-ikon nama="jam" ukuran="15" />
            <span class="topbar__jam" data-jam data-zona="WIB">{{ now()->format('H:i:s') }} WIB</span>
        </div>

        <div class="topbar__sosial">
            @if(! empty($identitas['facebook']))
                <a href="{{ $identitas['facebook'] }}" target="_blank" rel="noopener" title="Facebook"><x-ikon nama="facebook" ukuran="16" /></a>
            @endif
            @if(! empty($identitas['instagram']))
                <a href="{{ $identitas['instagram'] }}" target="_blank" rel="noopener" title="Instagram"><x-ikon nama="instagram" ukuran="16" /></a>
            @endif
            @if(! empty($identitas['youtube']))
                <a href="{{ $identitas['youtube'] }}" target="_blank" rel="noopener" title="YouTube"><x-ikon nama="youtube" ukuran="16" /></a>
            @endif
            @if(! empty($identitas['whatsapp']))
                <a href="https://wa.me/{{ $identitas['whatsapp'] }}" target="_blank" rel="noopener" title="WhatsApp"><x-ikon nama="whatsapp" ukuran="16" /></a>
            @endif
        </div>
    </div>
</div>
