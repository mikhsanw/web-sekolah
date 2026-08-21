{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tahun_ajaran')->text('Tahun Ajaran') !!}
			{!! html()->text('tahun_ajaran', isset($data) ? $data->tahun_ajaran : null)->class('form-control')->id('tahun_ajaran') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul Pengumuman') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal_pengumuman')->text('Waktu Pengumuman') !!}
			{!! html()->datetime('tanggal_pengumuman', isset($data) ? optional($data->tanggal_pengumuman)->format('Y-m-d H:i:s') : null)->class('form-control')->id('tanggal_pengumuman') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('pengambilan_mulai')->text('Pengambilan Adm. Mulai') !!}
			{!! html()->date('pengambilan_mulai', isset($data) ? optional($data->pengambilan_mulai)->format('Y-m-d') : null)->class('form-control')->id('pengambilan_mulai') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('pengambilan_selesai')->text('Pengambilan Adm. Selesai') !!}
			{!! html()->date('pengambilan_selesai', isset($data) ? optional($data->pengambilan_selesai)->format('Y-m-d') : null)->class('form-control')->id('pengambilan_selesai') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tempat_pengambilan')->text('Tempat Pengambilan') !!}
			{!! html()->text('tempat_pengambilan', isset($data) ? $data->tempat_pengambilan : null)->class('form-control')->id('tempat_pengambilan') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('keterangan')->text('Keterangan') !!}
			{!! html()->textarea('keterangan', isset($data) ? $data->keterangan : null)->class('form-control')->id('keterangan')->rows(4) !!}
		</div>
		<div class='form-group'>
			<div class="form-check form-switch form-check-custom form-check-solid mt-3">
				{!! html()->hidden('dibuka', 0) !!}
				{!! html()->checkbox('dibuka', isset($data) ? (bool) $data->dibuka : true, 1)->class('form-check-input')->id('dibuka') !!}
				{!! html()->label()->class('form-check-label ms-2')->for('dibuka')->text('Pengumuman Dibuka') !!}
			</div>
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
