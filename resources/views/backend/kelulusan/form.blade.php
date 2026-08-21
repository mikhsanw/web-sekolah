{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('kelulusan_periode_id')->text('Angkatan / Periode') !!}
            {!! html()->select('kelulusan_periode_id', $periodeList, isset($data) ? $data->kelulusan_periode_id : null)->class('form-control select2')->id('kelulusan_periode_id')->placeholder('-- Pilih Angkatan --') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nisn')->text('NISN') !!}
            {!! html()->text('nisn', isset($data) ? $data->nisn : null)->class('form-control')->id('nisn') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nis')->text('NIS') !!}
            {!! html()->text('nis', isset($data) ? $data->nis : null)->class('form-control')->id('nis') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nama')->text('Nama Siswa') !!}
            {!! html()->text('nama', isset($data) ? $data->nama : null)->class('form-control')->id('nama') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('tanggal_lahir')->text('Tanggal Lahir') !!}
            {!! html()->date('tanggal_lahir', isset($data) ? optional($data->tanggal_lahir)->format('Y-m-d') : null)->class('form-control')->id('tanggal_lahir') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('kelas')->text('Kelas') !!}
            {!! html()->text('kelas', isset($data) ? $data->kelas : null)->class('form-control')->id('kelas') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('status')->text('Status Kelulusan') !!}
            {!! html()->select('status', config('sekolah.enum.status_kelulusan'), isset($data) ? $data->status : 'lulus')->class('form-control select2')->id('status') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('tanggal_pengambilan')->text('Tanggal Pengambilan Administrasi') !!}
            {!! html()->date('tanggal_pengambilan', isset($data) ? optional($data->tanggal_pengambilan)->format('Y-m-d') : null)->class('form-control')->id('tanggal_pengambilan') !!}
            <small class="text-muted">Kosongkan untuk memakai jadwal dari periode kelulusan.</small>
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('keterangan')->text('Keterangan') !!}
            {!! html()->textarea('keterangan', isset($data) ? $data->keterangan : null)->class('form-control')->id('keterangan')->rows(3) !!}
        </div>
    </div>
</div>
{!! html()->hidden('table-id', 'datatable')->id('table-id') !!}
{!! html()->form()->close() !!}
<style>
    .select2-container { z-index: 9999 !important; width: 100% !important; }
    .modal-lg { max-width: 1000px !important; }
    .control-label { font-weight: 500 !important; font-size: 1.05rem !important; margin: .5rem !important; }
</style>
<script>
    $('.select2').select2();
    $('.modal-title').html('<i class="fa fa-plus-circle"></i> Form Data {!! $page->title !!}');
    $('.submit-data').html('<i class="fa fa-save"></i> Simpan Data');
</script>
