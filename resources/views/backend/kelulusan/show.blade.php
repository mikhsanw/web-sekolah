<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Angkatan')->class('control-label fw-bold') !!}
                {!! html()->p($data->periode?->tahun_ajaran ?? '-') !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('NISN')->class('control-label fw-bold') !!}
                {!! html()->p($data->nisn) !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('NIS')->class('control-label fw-bold') !!}
                {!! html()->p($data->nis ?? '-') !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Nama')->class('control-label fw-bold') !!}
                {!! html()->p($data->nama) !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Tanggal Lahir')->class('control-label fw-bold') !!}
                {!! html()->p(tanggalIndo($data->tanggal_lahir, 'D MMMM Y') ?? '-') !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Kelas')->class('control-label fw-bold') !!}
                {!! html()->p($data->kelas ?? '-') !!}
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Status')->class('control-label fw-bold') !!}
                <p><span class="badge badge-light-{{ $data->warna_status }}">{{ $data->nama_status }}</span></p>
            </div></div>
            <div class="col-md-6"><div class="form-group">
                {!! html()->span()->text('Pengambilan Administrasi')->class('control-label fw-bold') !!}
                {!! html()->p($data->info_pengambilan ?? '-') !!}
            </div></div>
            <div class="col-md-12"><div class="form-group">
                {!! html()->span()->text('Keterangan')->class('control-label fw-bold') !!}
                {!! html()->p($data->keterangan ?? '-') !!}
            </div></div>
        </div>
    </div>
</div>
<style>.modal-lg { max-width: 1000px !important; }</style>
<script>
    $('.submit-data').hide();
    $('.modal-title').html('<i class="fa fa-search"></i> Detail Data {!! $page->title !!}');
</script>
