<x-auth-layout>
@php
    $warnaUtama = pengaturan('warna_utama', config('sekolah.tampilan.warna_utama'));
    $warnaAksen = pengaturan('warna_aksen', config('sekolah.tampilan.warna_aksen'));
    $namaSekolah = pengaturan('nama_sekolah', config('master.app.profile.name'));
    $alamatSekolah = pengaturan('alamat', config('sekolah.identitas.alamat'));
    $logoSekolah = pengaturanBerkas('logo') ?? asset(config('master.app.web.template').'/img/logo.svg');
@endphp
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Page bg-->
    <style>
        body {
            background:
                radial-gradient(900px circle at 15% 10%, {{ $warnaAksen }}33, transparent 55%),
                linear-gradient(160deg, {{ $warnaUtama }} 0%, #0b2118 55%, #06120c 100%);
            background-attachment: fixed;
        }
    </style>
    <!--end::Page bg-->
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">
        <!--begin::Aside-->
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <!--begin::Aside-->
            <div class="d-flex flex-center flex-lg-start flex-column">
                <!--begin::Logo-->
                <a href="{{ url('/') }}" class="mb-7 d-inline-flex">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white p-3 shadow-sm">
                        <img alt="Logo {{ $namaSekolah }}" width="84" height="84" style="object-fit: contain;" src="{{ $logoSekolah }}" />
                    </span>
                </a>
                <!--end::Logo-->
                <!--begin::Title-->
                <h2 class="text-white fw-bold m-0">{{ $namaSekolah }}</h2>
                <!--end::Title-->
                @if ($alamatSekolah && $alamatSekolah !== '-')
                    <p class="text-white text-opacity-75 fs-6 mt-2 mb-0" style="max-width: 420px;">{{ $alamatSekolah }}</p>
                @endif
                <p class="text-white text-opacity-50 fs-7 mt-6 mb-0 text-uppercase" style="letter-spacing: .1em;">Panel Admin</p>
                <!--end::Subtitle-->
            </div>
            <!--begin::Aside-->
        </div>
        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
            <!--begin::Card-->
            <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('login') }}">
                    @csrf

                        <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Masuk</h1>
                            <!--end::Title-->
                            <!--begin::Subtitle-->
                            <div class="text-gray-500 fw-semibold fs-6">{{ $namaSekolah }}</div>
                            <!--end::Subtitle=-->
                        </div>
                        <!--begin::Heading-->
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8">
                            <!--begin::Email-->
                            <input id="email" type="email" placeholder="Email" class="form-control bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--end::Email-->
                        </div>
                        <!--end::Input group=-->
                        <div class="fv-row mb-3">
                            <!--begin::Password-->
                            <input id="password" type="password" placeholder="Password" class="form-control bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <!--end::Password-->
                        </div>
                        <!--end::Input group=-->
                        <!--begin::Submit button-->
                        <div class="d-grid mb-4 mt-8">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Masuk</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Mohon tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->
                            </button>
                        </div>
                        <!--end::Submit button-->
                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="link-primary fs-6" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            </div>
                        @endif
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Footer-->
                <div class="text-center text-gray-500 fs-8">
                    &copy; {{ date('Y') }} {{ $namaSekolah }}
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
</x-auth-layout>
