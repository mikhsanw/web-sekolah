@extends('frontend.layouts.app')

@section('konten')
<div class="wadah">
    <div class="tata-letak">
        <div class="kartu">
            <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="lokasi" ukuran="17" /> Alamat & Kontak</h2></div>
            <div class="kartu__isi">
                <ul class="daftar-rincian">
                    <li><span class="label">Nama Sekolah</span><span>{{ $identitas['nama_panjang'] ?: $identitas['nama'] }}</span></li>
                    @if(! empty($identitas['npsn']) && $identitas['npsn'] !== '-')
                        <li><span class="label">NPSN</span><span>{{ $identitas['npsn'] }}</span></li>
                    @endif
                    <li><span class="label">Alamat</span><span>{{ $identitas['alamat'] }}</span></li>
                    @if(! empty($identitas['telepon']) && $identitas['telepon'] !== '-')
                        <li><span class="label">Telepon</span><span>{{ $identitas['telepon'] }}</span></li>
                    @endif
                    @if(! empty($identitas['email']) && $identitas['email'] !== '-')
                        <li><span class="label">Email</span><span>{{ $identitas['email'] }}</span></li>
                    @endif
                    @if(! empty($identitas['kepala_sekolah']) && $identitas['kepala_sekolah'] !== '-')
                        <li><span class="label">Kepala Sekolah</span><span>{{ $identitas['kepala_sekolah'] }}</span></li>
                    @endif
                </ul>

                @if($identitas['maps_embed'] ?? false)
                    <div style="margin-top:16px;border-radius:8px;overflow:hidden;">
                        <iframe src="{{ $identitas['maps_embed'] }}" width="100%" height="320" style="border:0;" allowfullscreen
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi sekolah"></iframe>
                    </div>
                @endif
            </div>
        </div>

        <aside>
            <div class="kartu">
                <div class="kartu__kepala"><h2 class="kartu__judul"><x-ikon nama="telepon" ukuran="17" /> Media Sosial</h2></div>
                <div class="kartu__isi">
                    <p style="font-size:.9rem;color:var(--teks-lembut);">Ikuti kami untuk informasi terbaru:</p>
                    <div style="display:grid;gap:8px;">
                        @if(! empty($identitas['whatsapp']))
                            <a class="tombol tombol--blok" href="https://wa.me/{{ $identitas['whatsapp'] }}" target="_blank" rel="noopener">
                                <x-ikon nama="whatsapp" ukuran="16" /> WhatsApp
                            </a>
                        @endif
                        @if(! empty($identitas['facebook']))
                            <a class="tombol tombol--blok tombol--garis" href="{{ $identitas['facebook'] }}" target="_blank" rel="noopener">
                                <x-ikon nama="facebook" ukuran="16" /> Facebook
                            </a>
                        @endif
                        @if(! empty($identitas['instagram']))
                            <a class="tombol tombol--blok tombol--garis" href="{{ $identitas['instagram'] }}" target="_blank" rel="noopener">
                                <x-ikon nama="instagram" ukuran="16" /> Instagram
                            </a>
                        @endif
                        @if(! empty($identitas['youtube']))
                            <a class="tombol tombol--blok tombol--garis" href="{{ $identitas['youtube'] }}" target="_blank" rel="noopener">
                                <x-ikon nama="youtube" ukuran="16" /> YouTube
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
