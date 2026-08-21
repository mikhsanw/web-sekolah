{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('spmb_periode_id')->text('Periode SPMB') !!}
            {!! html()->select('spmb_periode_id', $periodeList, isset($data) ? $data->spmb_periode_id : null)->class('form-control select2')->id('spmb_periode_id')->placeholder('-- Pilih Periode --') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('asal_sekolah')->text('Asal Sekolah') !!}
            {!! html()->text('asal_sekolah', isset($data) ? $data->asal_sekolah : null)->class('form-control')->id('asal_sekolah') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nisn')->text('NISN') !!}
            {!! html()->text('nisn', isset($data) ? $data->nisn : null)->class('form-control')->id('nisn') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nik')->text('NIK') !!}
            {!! html()->text('nik', isset($data) ? $data->nik : null)->class('form-control')->id('nik') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nama')->text('Nama Lengkap') !!}
            {!! html()->text('nama', isset($data) ? $data->nama : null)->class('form-control')->id('nama') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('jenis_kelamin')->text('Jenis Kelamin') !!}
            {!! html()->select('jenis_kelamin', config('sekolah.enum.jenis_kelamin'), isset($data) ? $data->jenis_kelamin : null)->class('form-control select2')->id('jenis_kelamin')->placeholder('-- Pilih --') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('agama')->text('Agama') !!}
            {!! html()->select('agama', config('sekolah.enum.agama'), isset($data) ? $data->agama : null)->class('form-control select2')->id('agama')->placeholder('-- Pilih --') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('tempat_lahir')->text('Tempat Lahir') !!}
            {!! html()->text('tempat_lahir', isset($data) ? $data->tempat_lahir : null)->class('form-control')->id('tempat_lahir') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('tanggal_lahir')->text('Tanggal Lahir') !!}
            {!! html()->date('tanggal_lahir', isset($data) ? optional($data->tanggal_lahir)->format('Y-m-d') : null)->class('form-control')->id('tanggal_lahir') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('alamat')->text('Alamat') !!}
            {!! html()->textarea('alamat', isset($data) ? $data->alamat : null)->class('form-control')->id('alamat')->rows(3) !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('no_hp')->text('Nomor HP') !!}
            {!! html()->text('no_hp', isset($data) ? $data->no_hp : null)->class('form-control')->id('no_hp') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('nama_orang_tua')->text('Nama Orang Tua/Wali') !!}
            {!! html()->text('nama_orang_tua', isset($data) ? $data->nama_orang_tua : null)->class('form-control')->id('nama_orang_tua') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('status')->text('Status Verifikasi') !!}
            {!! html()->select('status', config('sekolah.enum.status_spmb'), isset($data) ? $data->status : 'baru')->class('form-control select2')->id('status') !!}
        </div>
        <div class='form-group'>
            {!! html()->label()->class('control-label')->for('catatan')->text('Catatan Panitia') !!}
            {!! html()->textarea('catatan', isset($data) ? $data->catatan : null)->class('form-control')->id('catatan')->rows(3) !!}
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
