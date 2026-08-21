{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('nama')->text('Nama Lengkap') !!}
			{!! html()->text('nama', isset($data) ? $data->nama : null)->class('form-control')->id('nama') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('nip')->text('NIP') !!}
			{!! html()->text('nip', isset($data) ? $data->nip : null)->class('form-control')->id('nip') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('nuptk')->text('NUPTK') !!}
			{!! html()->text('nuptk', isset($data) ? $data->nuptk : null)->class('form-control')->id('nuptk') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('jenis')->text('Jenis') !!}
			{!! html()->select('jenis', config('sekolah.enum.jenis_pegawai'), isset($data) ? $data->jenis : null)->class('form-control select2')->id('jenis')->placeholder('-- Pilih Jenis --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('jabatan')->text('Jabatan') !!}
			{!! html()->text('jabatan', isset($data) ? $data->jabatan : null)->class('form-control')->id('jabatan') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('mata_pelajaran')->text('Mata Pelajaran') !!}
			{!! html()->text('mata_pelajaran', isset($data) ? $data->mata_pelajaran : null)->class('form-control')->id('mata_pelajaran') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('jenis_kelamin')->text('Jenis Kelamin') !!}
			{!! html()->select('jenis_kelamin', config('sekolah.enum.jenis_kelamin'), isset($data) ? $data->jenis_kelamin : null)->class('form-control select2')->id('jenis_kelamin')->placeholder('-- Pilih Jenis Kelamin --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('pendidikan_terakhir')->text('Pendidikan Terakhir') !!}
			{!! html()->text('pendidikan_terakhir', isset($data) ? $data->pendidikan_terakhir : null)->class('form-control')->id('pendidikan_terakhir') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('status_kepegawaian')->text('Status Kepegawaian') !!}
			{!! html()->text('status_kepegawaian', isset($data) ? $data->status_kepegawaian : null)->class('form-control')->id('status_kepegawaian') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('email')->text('Email') !!}
			{!! html()->email('email', isset($data) ? $data->email : null)->class('form-control')->id('email') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('urutan')->text('Urutan') !!}
			{!! html()->number('urutan', isset($data) ? $data->urutan : null)->class('form-control')->id('urutan') !!}
		</div>
		<div class='form-group'>
			<div class="form-check form-switch form-check-custom form-check-solid mt-3">
				{!! html()->hidden('aktif', 0) !!}
				{!! html()->checkbox('aktif', isset($data) ? (bool) $data->aktif : true, 1)->class('form-check-input')->id('aktif') !!}
				{!! html()->label()->class('form-check-label ms-2')->for('aktif')->text('Aktif') !!}
			</div>
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('foto')->text('Foto') !!}
			{!! html()->file('foto')->class('form-control')->id('foto')->accept('image/*') !!}
			@if(isset($data) && $data->berkas('foto'))
				<img src="{{ $data->berkas('foto')->link_stream }}" class="w-25 pt-2" alt="Foto">
			@endif
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
