<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Nama Ekstrakurikuler")->class("control-label fw-bold") !!}
					{!! html()->p($data->nama ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pembina")->class("control-label fw-bold") !!}
					{!! html()->p($data->pembina ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Jadwal")->class("control-label fw-bold") !!}
					{!! html()->p($data->jadwal ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tempat")->class("control-label fw-bold") !!}
					{!! html()->p($data->tempat ?? '-') !!}
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
					{!! html()->span()->text("Urutan")->class("control-label fw-bold") !!}
					{!! html()->p($data->urutan ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Aktif")->class("control-label fw-bold") !!}
					{!! html()->p($data->aktif ? 'Ya' : 'Tidak') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Gambar")->class("control-label fw-bold") !!}
					@if($data->berkas('gambar'))
						<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-50 pt-2" alt="Gambar">
					@else
						{!! html()->p('-') !!}
					@endif
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
