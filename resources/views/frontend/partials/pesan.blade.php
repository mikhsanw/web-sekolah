@if (session('sukses'))
    <div class="alert alert--sukses">{{ session('sukses') }}</div>
@endif

@if (session('galat'))
    <div class="alert alert--bahaya">{{ session('galat') }}</div>
@endif

@if (session('info'))
    <div class="alert alert--info">{{ session('info') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert--bahaya">
        <strong>Periksa kembali isian berikut:</strong>
        <ul style="margin:6px 0 0;padding-left:1.1rem;">
            @foreach ($errors->all() as $galat)
                <li>{{ $galat }}</li>
            @endforeach
        </ul>
    </div>
@endif
