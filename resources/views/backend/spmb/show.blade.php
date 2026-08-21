<div class="panel">
    <div class="panel-body">
        <div class="row">
            @php
                $baris = [
                    'No Pendaftaran' => $data->no_pendaftaran,
                    'Periode' => $data->periode?->tahun_ajaran ?? '-',
                    'Asal Sekolah' => $data->asal_sekolah ?? '-',
                    'NISN' => $data->nisn,
                    'NIK' => $data->nik ?? '-',
                    'Nama Lengkap' => $data->nama,
                    'Jenis Kelamin' => $data->nama_jenis_kelamin,
                    'Agama' => $data->agama ?? '-',
                    'Tempat, Tanggal Lahir' => $data->ttl,
                    'Alamat' => $data->alamat ?? '-',
                    'Nomor HP' => $data->no_hp ?? '-',
                    'Orang Tua/Wali' => $data->nama_orang_tua ?? '-',
                    'Waktu Pendaftaran' => tanggalIndo($data->terdaftar_pada, 'D MMMM Y HH:mm') ?? '-',
                    'Catatan Panitia' => $data->catatan ?? '-',
                ];
            @endphp
            @foreach($baris as $label => $nilai)
                <div class="col-md-6"><div class="form-group">
                    {!! html()->span()->text($label)->class('control-label fw-bold') !!}
                    {!! html()->p($nilai) !!}
                </div></div>
            @endforeach
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Status')->class('control-label fw-bold') !!}
                <p><span class="badge badge-light-{{ $data->warna_status }}">{{ $data->nama_status }}</span></p>
            </div></div>
        </div>
    </div>
</div>
<style>.modal-lg { max-width: 1000px !important; }</style>
<script>
    $('.submit-data').hide();
    $('.modal-title').html('<i class="fa fa-search"></i> Detail Data {!! $page->title !!}');
</script>
