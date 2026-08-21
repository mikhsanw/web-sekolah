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
					{!! html()->span()->text("Judul Pengumuman")->class("control-label fw-bold") !!}
					{!! html()->p($data->judul ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Waktu Pengumuman")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal_pengumuman, 'D MMMM Y HH:mm') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pengambilan Adm. Mulai")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->pengambilan_mulai, 'D MMMM Y') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pengambilan Adm. Selesai")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->pengambilan_selesai, 'D MMMM Y') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tempat Pengambilan")->class("control-label fw-bold") !!}
					{!! html()->p($data->tempat_pengambilan ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Keterangan")->class("control-label fw-bold") !!}
					{!! html()->p($data->keterangan ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pengumuman Dibuka")->class("control-label fw-bold") !!}
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
