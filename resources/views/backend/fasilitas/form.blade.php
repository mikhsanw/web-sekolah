{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('nama')->text('Nama Sarana') !!}
			{!! html()->text('nama', isset($data) ? $data->nama : null)->class('form-control')->id('nama') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('jumlah')->text('Jumlah') !!}
			{!! html()->number('jumlah', isset($data) ? $data->jumlah : null)->class('form-control')->id('jumlah') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('kondisi')->text('Kondisi') !!}
			{!! html()->select('kondisi', config('sekolah.enum.kondisi_fasilitas'), isset($data) ? $data->kondisi : null)->class('form-control select2')->id('kondisi')->placeholder('-- Pilih Kondisi --') !!}
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
