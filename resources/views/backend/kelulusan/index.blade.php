<x-app-layout>
    <x-slot name="title">{{ __($page->title) }}</x-slot>

@if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif
@if(session('galat'))
    <div class="alert alert-danger">{{ session('galat') }}</div>
@endif
@if(session('galatImport'))
    <div class="alert alert-warning">
        <strong>Catatan impor:</strong>
        <ul class="mb-0">
            @foreach(session('galatImport') as $baris)
                <li>{{ $baris }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" id="search" data-kt-docs-table-filter="search"
                    class="form-control form-control-solid w-250px ps-15" placeholder="Cari siswa" />
            </div>
            <form method="get" class="ms-3">
                <select name="periode" class="form-select form-select-solid w-200px" onchange="this.form.submit()">
                    <option value="">Semua angkatan</option>
                    @foreach($periodeList as $id => $tahun)
                        <option value="{{ $id }}" @selected(request('periode') === $id)>{{ $tahun }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                <a href="{{ route('kelulusan.export', request()->only('periode')) }}" class="btn btn-light-success">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                @can($page->code.' create')
                    <a href="{{ route('kelulusan.import.form') }}" class="btn btn-light-info">
                        <i class="fa fa-file-import"></i> Import Excel
                    </a>
                    <a href="#" class="btn btn-primary btn-action" data-title="Tambah" data-action="create"
                        data-url="{{ config('master.app.url.backend').'/'.$page->url }}">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body py-4">
        <table id="datatable" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-0">No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Angkatan</th>
                    <th>Status</th>
                    <th class="text-center max-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold"></tbody>
        </table>
    </div>
</div>

@prepend('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endprepend
@prepend('js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/'.$backend.'/'.$page->code.'/datatable.js') }}"></script>
<script src="{{ asset('js/jquery-validation-1.19.5/lib/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery-crud.js') }}"></script>
@endprepend

</x-app-layout>
