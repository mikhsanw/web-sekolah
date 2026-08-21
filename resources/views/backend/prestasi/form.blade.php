{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul Prestasi') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('kategori')->text('Kategori') !!}
			{!! html()->select('kategori', config('sekolah.enum.kategori_prestasi'), isset($data) ? $data->kategori : null)->class('form-control select2')->id('kategori')->placeholder('-- Pilih Kategori --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('nama_peraih')->text('Nama Peraih') !!}
			{!! html()->text('nama_peraih', isset($data) ? $data->nama_peraih : null)->class('form-control')->id('nama_peraih') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tingkat')->text('Tingkat') !!}
			{!! html()->select('tingkat', config('sekolah.enum.tingkat_prestasi'), isset($data) ? $data->tingkat : null)->class('form-control select2')->id('tingkat')->placeholder('-- Pilih Tingkat --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('peringkat')->text('Peringkat') !!}
			{!! html()->text('peringkat', isset($data) ? $data->peringkat : null)->class('form-control')->id('peringkat') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('penyelenggara')->text('Penyelenggara') !!}
			{!! html()->text('penyelenggara', isset($data) ? $data->penyelenggara : null)->class('form-control')->id('penyelenggara') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tahun')->text('Tahun') !!}
			{!! html()->number('tahun', isset($data) ? $data->tahun : null)->class('form-control')->id('tahun') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal')->text('Tanggal') !!}
			{!! html()->date('tanggal', isset($data) ? optional($data->tanggal)->format('Y-m-d') : null)->class('form-control')->id('tanggal') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('deskripsi')->text('Deskripsi') !!}
			{!! html()->textarea('deskripsi', isset($data) ? $data->deskripsi : null)->class('form-control')->id('deskripsi')->rows(4) !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('gambar')->text('Dokumentasi') !!}
			{!! html()->file('gambar')->class('form-control')->id('gambar')->accept('image/*') !!}
			@if(isset($data) && $data->berkas('gambar'))
				<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-25 pt-2" alt="Dokumentasi">
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
