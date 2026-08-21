<x-app-layout>
    <x-slot name="title">Import Data Kelulusan</x-slot>

@if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif
@if(session('galat'))
    <div class="alert alert-danger">{{ session('galat') }}</div>
@endif
@if(session('galatImport'))
    <div class="alert alert-warning">
        <strong>Baris yang dilewati:</strong>
        <ul class="mb-0">
            @foreach(session('galatImport') as $baris)
                <li>{{ $baris }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $galat)<li>{{ $galat }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-5">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Import Data Kelulusan dari Excel</h3>
            </div>
            <form method="post" action="{{ route('kelulusan.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="mb-5">
                        <label class="form-label required">Angkatan / Periode</label>
                        <select name="kelulusan_periode_id" class="form-select" required>
                            <option value="">-- Pilih Angkatan --</option>
                            @foreach($periodeList as $id => $tahun)
                                <option value="{{ $id }}" @selected(old('kelulusan_periode_id') === $id)>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Berkas Excel</label>
                        <input type="file" name="berkas" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format .xlsx, .xls, atau .csv — maksimal 5 MB.</div>
                    </div>

                    <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                        <input type="hidden" name="timpa" value="0">
                        <input class="form-check-input" type="checkbox" name="timpa" value="1" id="timpa">
                        <label class="form-check-label ms-2" for="timpa">Perbarui data yang NISN-nya sudah ada</label>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Proses Import</button>
                    <a href="{{ route('kelulusan.template') }}" class="btn btn-light-success"><i class="fa fa-download"></i> Unduh Template</a>
                    <a href="{{ route('kelulusan.index') }}" class="btn btn-light">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Format Kolom</h3></div>
            <div class="card-body">
                <p class="text-muted">Baris pertama berkas harus berisi judul kolom berikut:</p>
                <table class="table table-row-bordered">
                    <thead><tr><th>Kolom</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td><code>NISN</code></td><td>Wajib. Kunci pencocokan data.</td></tr>
                        <tr><td><code>NIS</code></td><td>Opsional.</td></tr>
                        <tr><td><code>Nama</code></td><td>Wajib.</td></tr>
                        <tr><td><code>Tanggal Lahir</code></td><td>Wajib. Format <code>YYYY-MM-DD</code> atau tanggal Excel.</td></tr>
                        <tr><td><code>Kelas</code></td><td>Opsional.</td></tr>
                        <tr><td><code>Status</code></td><td><code>lulus</code> atau <code>tidak_lulus</code>.</td></tr>
                        <tr><td><code>Keterangan</code></td><td>Opsional.</td></tr>
                        <tr><td><code>Tanggal Pengambilan</code></td><td>Opsional.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
