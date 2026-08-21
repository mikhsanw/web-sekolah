<div class="panel">
    <div class="panel-body">
        <div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Nama Lengkap")->class("control-label fw-bold") !!}
					{!! html()->p($data->nama ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("NIP")->class("control-label fw-bold") !!}
					{!! html()->p($data->nip ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("NUPTK")->class("control-label fw-bold") !!}
					{!! html()->p($data->nuptk ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Jenis")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.jenis_pegawai'))[$data->jenis] ?? $data->jenis) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Jabatan")->class("control-label fw-bold") !!}
					{!! html()->p($data->jabatan ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Mata Pelajaran")->class("control-label fw-bold") !!}
					{!! html()->p($data->mata_pelajaran ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Jenis Kelamin")->class("control-label fw-bold") !!}
					{!! html()->p((config('sekolah.enum.jenis_kelamin'))[$data->jenis_kelamin] ?? $data->jenis_kelamin) !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Pendidikan Terakhir")->class("control-label fw-bold") !!}
					{!! html()->p($data->pendidikan_terakhir ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Status Kepegawaian")->class("control-label fw-bold") !!}
					{!! html()->p($data->status_kepegawaian ?? '-') !!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! html()->span()->text("Email")->class("control-label fw-bold") !!}
					{!! html()->p($data->email ?? '-') !!}
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
					{!! html()->span()->text("Foto")->class("control-label fw-bold") !!}
					@if($data->berkas('foto'))
						<img src="{{ $data->berkas('foto')->link_stream }}" class="w-50 pt-2" alt="Foto">
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
