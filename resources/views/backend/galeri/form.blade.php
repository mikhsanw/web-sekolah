{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('kategori')->text('Kategori') !!}
			{!! html()->select('kategori', config('sekolah.enum.kategori_galeri'), isset($data) ? $data->kategori : null)->class('form-control select2')->id('kategori')->placeholder('-- Pilih Kategori --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal')->text('Tanggal') !!}
			{!! html()->date('tanggal', isset($data) ? optional($data->tanggal)->format('Y-m-d') : null)->class('form-control')->id('tanggal') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('video_url')->text('URL Video YouTube') !!}
			{!! html()->text('video_url', isset($data) ? $data->video_url : null)->class('form-control')->id('video_url')->placeholder('https://') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('deskripsi')->text('Deskripsi') !!}
			{!! html()->textarea('deskripsi', isset($data) ? $data->deskripsi : null)->class('form-control')->id('deskripsi')->rows(4) !!}
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
			{!! html()->label()->class('control-label')->for('gambar')->text('Gambar') !!}
			{!! html()->file('gambar')->class('form-control')->id('gambar')->accept('image/*') !!}
			@if(isset($data) && $data->berkas('gambar'))
				<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-25 pt-2" alt="Gambar">
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
