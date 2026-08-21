{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul Agenda') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('jenis')->text('Jenis') !!}
			{!! html()->select('jenis', config('sekolah.enum.jenis_agenda'), isset($data) ? $data->jenis : null)->class('form-control select2')->id('jenis')->placeholder('-- Pilih Jenis --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal_mulai')->text('Tanggal Mulai') !!}
			{!! html()->date('tanggal_mulai', isset($data) ? optional($data->tanggal_mulai)->format('Y-m-d') : null)->class('form-control')->id('tanggal_mulai') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal_selesai')->text('Tanggal Selesai') !!}
			{!! html()->date('tanggal_selesai', isset($data) ? optional($data->tanggal_selesai)->format('Y-m-d') : null)->class('form-control')->id('tanggal_selesai') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('waktu')->text('Waktu') !!}
			{!! html()->text('waktu', isset($data) ? $data->waktu : null)->class('form-control')->id('waktu') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('lokasi')->text('Lokasi') !!}
			{!! html()->text('lokasi', isset($data) ? $data->lokasi : null)->class('form-control')->id('lokasi') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('deskripsi')->text('Deskripsi') !!}
			{!! html()->textarea('deskripsi', isset($data) ? $data->deskripsi : null)->class('form-control')->id('deskripsi')->rows(4) !!}
		</div>
		<div class='form-group'>
			<div class="form-check form-switch form-check-custom form-check-solid mt-3">
				{!! html()->hidden('aktif', 0) !!}
				{!! html()->checkbox('aktif', isset($data) ? (bool) $data->aktif : true, 1)->class('form-check-input')->id('aktif') !!}
				{!! html()->label()->class('form-check-label ms-2')->for('aktif')->text('Aktif') !!}
			</div>
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
