{{ html()->form(isset($data) ? 'PUT' : 'POST', (isset($data) ? route($page->code.'.update', $data->id) : route($page->code.'.store')))->id('form-create-'.$page->code)->acceptsFiles()->class('form form-horizontal')->open() }}
<div class="panel">
    <div class="panel-body">
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tahun_ajaran')->text('Tahun Ajaran') !!}
			{!! html()->text('tahun_ajaran', isset($data) ? $data->tahun_ajaran : null)->class('form-control')->id('tahun_ajaran') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('judul')->text('Judul Kegiatan') !!}
			{!! html()->text('judul', isset($data) ? $data->judul : null)->class('form-control')->id('judul') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal_buka')->text('Dibuka') !!}
			{!! html()->datetime('tanggal_buka', isset($data) ? optional($data->tanggal_buka)->format('Y-m-d H:i:s') : null)->class('form-control')->id('tanggal_buka') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('tanggal_tutup')->text('Ditutup') !!}
			{!! html()->datetime('tanggal_tutup', isset($data) ? optional($data->tanggal_tutup)->format('Y-m-d H:i:s') : null)->class('form-control')->id('tanggal_tutup') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('kuota')->text('Kuota') !!}
			{!! html()->number('kuota', isset($data) ? $data->kuota : null)->class('form-control')->id('kuota') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('informasi')->text('Informasi') !!}
			{!! html()->textarea('informasi', isset($data) ? $data->informasi : null)->class('form-control tinymce')->id('informasi') !!}
		</div>
		<div class='form-group'>
			{!! html()->label()->class('control-label')->for('syarat')->text('Persyaratan') !!}
			{!! html()->textarea('syarat', isset($data) ? $data->syarat : null)->class('form-control tinymce')->id('syarat') !!}
		</div>
		<div class='form-group'>
			<div class="form-check form-switch form-check-custom form-check-solid mt-3">
				{!! html()->hidden('dibuka', 0) !!}
				{!! html()->checkbox('dibuka', isset($data) ? (bool) $data->dibuka : true, 1)->class('form-check-input')->id('dibuka') !!}
				{!! html()->label()->class('form-check-label ms-2')->for('dibuka')->text('Pendaftaran Dibuka') !!}
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
