<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Judul Agenda")->class("control-label fw-bold") !!}
					{!! html()->p($data->judul ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Jenis")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.jenis_agenda'))[$data->jenis] ?? $data->jenis) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tanggal Mulai")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal_mulai, 'D MMMM Y') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tanggal Selesai")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal_selesai, 'D MMMM Y') ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Waktu")->class("control-label fw-bold") !!}
					{!! html()->p($data->waktu ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Lokasi")->class("control-label fw-bold") !!}
					{!! html()->p($data->lokasi ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Deskripsi")->class("control-label fw-bold") !!}
					{!! html()->p($data->deskripsi ?? '-') !!}
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
