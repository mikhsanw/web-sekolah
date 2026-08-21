<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Judul Prestasi")->class("control-label fw-bold") !!}
					{!! html()->p($data->judul ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Kategori")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.kategori_prestasi'))[$data->kategori] ?? $data->kategori) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Nama Peraih")->class("control-label fw-bold") !!}
					{!! html()->p($data->nama_peraih ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tingkat")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.tingkat_prestasi'))[$data->tingkat] ?? $data->tingkat) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Peringkat")->class("control-label fw-bold") !!}
					{!! html()->p($data->peringkat ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Penyelenggara")->class("control-label fw-bold") !!}
					{!! html()->p($data->penyelenggara ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tahun")->class("control-label fw-bold") !!}
					{!! html()->p($data->tahun ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Tanggal")->class("control-label fw-bold") !!}
					{!! html()->p(tanggalIndo($data->tanggal, 'D MMMM Y') ?? '-') !!}
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
					{!! html()->span()->text("Dokumentasi")->class("control-label fw-bold") !!}
					@if($data->berkas('gambar'))
						<img src="{{ $data->berkas('gambar')->link_stream }}" class="w-50 pt-2" alt="Dokumentasi">
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
