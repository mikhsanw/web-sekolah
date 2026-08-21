{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('kategori')->text('Kategori') !!}
			{!! html()->select('kategori', config('sekolah.enum.kategori_halaman'), isset($data) ? $data->kategori : null)->class('form-control select2')->id('kategori')->placeholder('-- Pilih Kategori --') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('ringkasan')->text('Ringkasan') !!}
			{!! html()->textarea('ringkasan', isset($data) ? $data->ringkasan : null)->class('form-control')->id('ringkasan')->rows(4) !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('isi')->text('Isi Halaman') !!}
			{!! html()->textarea('isi', isset($data) ? $data->isi : null)->class('form-control tinymce')->id('isi') !!}
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
			{!! html()->label()->class('control-label')->for('gambar')->text('Gambar Sampul') !!}
			{!! html()->file('gambar')->class('form-control')->id('gambar')->accept('image/*') !!}
			@if(isset($data) && $data->berkas('gambar'))
				<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-25 pt-2" alt="Gambar Sampul">
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

    //tinymce
    $(document).ready(function () {
        var options = {
            selector: ".tinymce",
            height: "420",
            menubar: false,
            toolbar: ["styleselect fontselect fontsizeselect",
                "undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify",
                "bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview | code"],
            plugins: "advlist autolink link image lists charmap print preview code",
            setup: function (editor) {
                editor.on('init', function () {
                    $('form').on('submit', function () {
                        editor.save();
                    });
                });
            }
        };
        if (KTThemeMode.getMode() === "dark") {
            options["skin"] = "oxide-dark";
            options["content_css"] = "dark";
        }
        tinymce.init(options);
    });
    $('#modal-master').on('hidden.bs.modal', function () {
        tinymce.remove('.tinymce');
    });
</script>
