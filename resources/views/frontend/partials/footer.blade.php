<footer class="footer tanpa-cetak">
    <div class="wadah">
        <div class="footer__grid">
            <div>
                <div class="footer__identitas">
                    <img class="footer__logo"
                        src="{{ $identitas['logo'] ?? asset(config('master.app.web.template').'/img/logo.svg') }}"
                        alt="Logo {{ $identitas['nama'] ?? 'sekolah' }}">
                    <div>
                        <div class="footer__nama">{{ $identitas['nama'] ?? config('sekolah.identitas.nama') }}</div>
                        <p style="margin:6px 0 0;font-size:.9rem;">{{ $identitas['alamat'] ?? '' }}</p>
                        @if(! empty($identitas['moto']))
                            <p style="margin:6px 0 0;font-size:.85rem;opacity:.8;"><em>{{ $identitas['moto'] }}</em></p>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h4>Tautan</h4>
                <ul class="footer__daftar">
                    <li><a href="{{ route('web.berita.index') }}">Berita Sekolah</a></li>
                    <li><a href="{{ route('web.agenda') }}">Agenda Kegiatan</a></li>
                    <li><a href="{{ route('web.galeri.index') }}">Galeri</a></li>
                    <li><a href="{{ route('web.kelulusan.index') }}">Pengumuman Kelulusan</a></li>
                    <li><a href="{{ route('web.spmb.informasi') }}">Informasi SPMB</a></li>
                    <li><a href="{{ route('web.kontak') }}">Kontak</a></li>
                </ul>
            </div>

            <div>
                @php
                    $adaTelepon = ! empty($identitas['telepon']) && $identitas['telepon'] !== '-';
                    $adaEmail = ! empty($identitas['email']) && $identitas['email'] !== '-';
                    $adaSosial = collect(['facebook', 'instagram', 'youtube', 'whatsapp'])
                        ->contains(fn ($kunci) => ! empty($identitas[$kunci]));
                @endphp
                <h4>Kontak</h4>
                <ul class="footer__daftar">
                    @if($adaTelepon)
                        <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $identitas['telepon']) }}">{{ $identitas['telepon'] }}</a></li>
                    @endif
                    @if($adaEmail)
                        <li><a href="mailto:{{ $identitas['email'] }}">{{ $identitas['email'] }}</a></li>
                    @endif
                    @unless($adaTelepon || $adaEmail || $adaSosial)
                        <li>{{ $identitas['alamat'] ?? '' }}</li>
                        <li><a href="{{ route('web.kontak') }}">Lihat halaman kontak</a></li>
                    @endunless
                </ul>

                <div class="sosial">
                    @if(! empty($identitas['facebook']))
                        <a href="{{ $identitas['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><x-ikon nama="facebook" ukuran="18" /></a>
                    @endif
                    @if(! empty($identitas['instagram']))
                        <a href="{{ $identitas['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><x-ikon nama="instagram" ukuran="18" /></a>
                    @endif
                    @if(! empty($identitas['youtube']))
                        <a href="{{ $identitas['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><x-ikon nama="youtube" ukuran="18" /></a>
                    @endif
                    @if(! empty($identitas['whatsapp']))
                        <a href="https://wa.me/{{ $identitas['whatsapp'] }}" target="_blank" rel="noopener" aria-label="WhatsApp"><x-ikon nama="whatsapp" ukuran="18" /></a>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer__bawah">
            <span>&copy; {{ date('Y') }} {{ $identitas['nama'] ?? '' }}. {{ $identitas['teks_footer'] ?? 'Seluruh hak cipta dilindungi.' }}</span>
            <a href="{{ url(config('master.app.url.backend').'/home') }}">Login Admin</a>
        </div>
    </div>
</footer>
