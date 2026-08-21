<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tahun Ajaran")->class("control-label fw-bold") !!}
					{!! html()->p($data->tahun_ajaran ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Judul Kegiatan")->class("control-label fw-bold") !!}
					{!! html()->p($data->judul ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Dibuka")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal_buka, 'D MMMM Y HH:mm') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Ditutup")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal_tutup, 'D MMMM Y HH:mm') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Kuota")->class("control-label fw-bold") !!}
					{!! html()->p($data->kuota ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Informasi")->class("control-label fw-bold") !!}
					<div>{!! $data->informasi !!}</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Persyaratan")->class("control-label fw-bold") !!}
					<div>{!! $data->syarat !!}</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pendaftaran Dibuka")->class("control-label fw-bold") !!}
					{!! html()->p($data->dibuka ? 'Ya' : 'Tidak') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Aktif")->class("control-label fw-bold") !!}
					{!! html()->p($data->aktif ? 'Ya' : 'Tidak') !!}
				</div>
			</div>
        </div>
    </div>
</div>
<style>
    .modal-lg { max-width: 1000px !important; }
</style>
<script>
    $('.submit-data').hide();
    $('.modal-title').html('<i class="fa fa-search"></i> Detail Data {!! $page->title !!}');
</script>
