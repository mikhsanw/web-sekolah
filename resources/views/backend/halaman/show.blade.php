<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Judul")->class("control-label fw-bold") !!}
					{!! html()->p($data->judul ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Kategori")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.kategori_halaman'))[$data->kategori] ?? $data->kategori) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Ringkasan")->class("control-label fw-bold") !!}
					{!! html()->p($data->ringkasan ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Isi Halaman")->class("control-label fw-bold") !!}
					<div>{!! $data->isi !!}</div>
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
					{!! html()->span()->text("Gambar Sampul")->class("control-label fw-bold") !!}
					@if($data->berkas('gambar'))
						<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-50 pt-2" alt="Gambar Sampul">
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
