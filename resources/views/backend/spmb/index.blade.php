<x-app-layout>
    <x-slot name="title">{{ __($page->title) }}</x-slot>

<div class="row g-5 mb-5">
    <div class="col-6 col-lg-3">
        <div class="card"><div class="card-body text-center py-4">
            <div class="fs-2hx fw-bold text-primary">{{ $rekap['total'] }}</div>
            <div class="text-muted">Total Pendaftar</div>
        </div></div>
    </div>
    @foreach(config('sekolah.enum.status_spmb') as $kunci => $label)
        <div class="col-6 col-lg-3">
            <div class="card"><div class="card-body text-center py-4">
                <div class="fs-2hx fw-bold text-{{ config('sekolah.enum.warna_spmb')[$kunci] }}">{{ $rekap[$kunci] ?? 0 }}</div>
                <div class="text-muted">{{ $label }}</div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span class="path2"></span></i>
                <input type="text" id="search" data-kt-docs-table-filter="search"
                    class="form-control form-control-solid w-250px ps-15" placeholder="Cari pendaftar" />
            </div>
            <form method="get" class="ms-3 d-flex gap-2">
                <select name="periode" class="form-select form-select-solid w-175px" onchange="this.form.submit()">
                    <option value="">Semua periode</option>
                    @foreach($periodeList as $id => $tahun)
                        <option value="{{ $id }}" @selected(request('periode') === $id)>{{ $tahun }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select form-select-solid w-175px" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    @foreach(config('sekolah.enum.status_spmb') as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('status') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('spmb.export', request()->only('periode', 'status')) }}" class="btn btn-light-success">
                    <i class="fa fa-file-excel"></i> Export Excel
                </a>
                @can($page->code.' create')
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
                    <th>No Pendaftaran</th>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Asal Sekolah</th>
                    <th>Periode</th>
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
