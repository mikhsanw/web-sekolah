<x-app-layout>
    <x-slot name="title">{{ __($page->title ?? 'Pengaturan Website') }}</x-slot>

@if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $galat)<li>{{ $galat }}</li>@endforeach</ul>
    </div>
@endif

<form method="post" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengaturan Website Sekolah</h3>
            <div class="card-toolbar">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pengaturan</button>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                @foreach($kelompok as $nama => $daftar)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                            href="#tab-{{ $nama }}">{{ $namaKelompok[$nama] ?? ucfirst($nama) }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($kelompok as $nama => $daftar)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $nama }}">
                        <div class="row">
                            @foreach($daftar as $item)
                                <div class="col-md-6 mb-5">
                                    <label class="form-label fw-bold" for="{{ $item->kunci }}">{{ $item->label }}</label>

                                    @switch($item->tipe)
                                        @case('textarea')
                                            <textarea class="form-control" id="{{ $item->kunci }}"
                                                name="nilai[{{ $item->kunci }}]" rows="3">{{ old('nilai.'.$item->kunci, $item->nilai) }}</textarea>
                                            @break

                                        @case('color')
                                            <input type="color" class="form-control form-control-color" id="{{ $item->kunci }}"
                                                name="nilai[{{ $item->kunci }}]" value="{{ old('nilai.'.$item->kunci, $item->nilai ?: '#0f5132') }}">
                                            @break

                                        @case('image')
                                        @case('audio')
                                            @php $berkas = $item->berkas($item->kunci); @endphp
                                            @if($berkas)
                                                <div class="mb-2">
                                                    @if($item->tipe === 'image')
                                                        <img src="{{ $berkas->link_stream }}" alt="{{ $item->label }}" class="h-75px rounded border">
                                                    @else
                                                        <audio controls preload="none" class="w-100">
                                                            <source src="{{ $berkas->link_stream }}">
                                                        </audio>
                                                    @endif
                                                </div>
                                            @endif
                                            <input type="file" class="form-control" id="{{ $item->kunci }}"
                                                name="berkas[{{ $item->kunci }}]"
                                                accept="{{ $item->tipe === 'image' ? 'image/*' : 'audio/*' }}">
                                            @if($berkas)
                                                {{-- Tombol memakai atribut form agar tidak menyarangkan form di dalam form. --}}
                                                <button type="submit" form="hapus-{{ $item->kunci }}"
                                                    class="btn btn-sm btn-light-danger mt-2"
                                                    onclick="return confirm('Hapus berkas {{ $item->label }}?')">
                                                    <i class="fa fa-trash"></i> Hapus berkas
                                                </button>
                                            @endif
                                            @break

                                        @default
                                            <input type="text" class="form-control" id="{{ $item->kunci }}"
                                                name="nilai[{{ $item->kunci }}]" value="{{ old('nilai.'.$item->kunci, $item->nilai) }}">
                                    @endswitch

                                    @if($item->keterangan)
                                        <div class="form-text">{{ $item->keterangan }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pengaturan</button>
        </div>
    </div>
</form>

@foreach($kelompok as $daftar)
    @foreach($daftar as $item)
        @if(in_array($item->tipe, ['image', 'audio'], true) && $item->berkas($item->kunci))
            <form method="post" action="{{ route('pengaturan.berkas.hapus', $item->kunci) }}" class="d-none"
                id="hapus-{{ $item->kunci }}">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach
@endforeach

</x-app-layout>
