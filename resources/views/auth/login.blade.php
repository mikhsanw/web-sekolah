<x-auth-layout>
@php
    $warnaUtama = pengaturan('warna_utama', config('sekolah.tampilan.warna_utama')) ?: '#0f5132';
    $warnaAksen = pengaturan('warna_aksen', config('sekolah.tampilan.warna_aksen')) ?: '#f0b429';
    $namaSekolah = pengaturan('nama_sekolah', config('master.app.profile.name'));
    $alamatSekolah = pengaturan('alamat', config('sekolah.identitas.alamat'));
    $logoSekolah = pengaturanBerkas('logo') ?? asset(config('master.app.web.template').'/img/logo.svg');
@endphp
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <style>
        :root {
            --login-primary: {{ $warnaUtama }};
            --login-accent: {{ $warnaAksen }};
        }

        /* ---------- Latar belakang ---------- */
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(1100px circle at 12% 8%, {{ $warnaAksen }}2e, transparent 58%),
                radial-gradient(900px circle at 88% 92%, {{ $warnaUtama }}cc, transparent 62%),
                linear-gradient(155deg, {{ $warnaUtama }}f2 0%, #0a1f14 52%, #050e09 100%);
            overflow: hidden;
        }

        /* Ornamen batik/geometris halus */
        .login-bg::before {
            content: "";
            position: absolute;
            inset: -25%;
            background-image:
                repeating-linear-gradient(45deg, rgba(255,255,255,.045) 0 2px, transparent 2px 26px),
                repeating-linear-gradient(-45deg, rgba(255,255,255,.035) 0 2px, transparent 2px 26px);
            transform: rotate(8deg);
            pointer-events: none;
        }

        /* Blob cahaya bergerak */
        .login-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .38;
            animation: loginFloat 16s ease-in-out infinite;
        }
        .login-blob--1 { width: 420px; height: 420px; background: {{ $warnaAksen }}; top: -90px; left: -70px; }
        .login-blob--2 { width: 360px; height: 360px; background: #2fbf71; bottom: -110px; right: -60px; animation-delay: -6s; }
        .login-blob--3 { width: 260px; height: 260px; background: #ffffff; opacity: .12; top: 45%; left: 42%; animation-delay: -11s; }

        @keyframes loginFloat {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50%      { transform: translate3d(28px, -34px, 0) scale(1.08); }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-blob { animation: none; }
        }

        .login-wrap { position: relative; z-index: 1; }

        /* ---------- Sisi kiri (branding) ---------- */
        .login-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .38rem .9rem;
            border-radius: 999px;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.20);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: .78rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .login-badge .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: {{ $warnaAksen }};
            box-shadow: 0 0 0 4px {{ $warnaAksen }}33;
        }

        .login-logo-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 128px; height: 128px;
            border-radius: 50%;
            background: rgba(255,255,255,.96);
            box-shadow:
                0 22px 55px rgba(0,0,0,.42),
                0 0 0 10px rgba(255,255,255,.08),
                0 0 0 20px rgba(255,255,255,.04);
            transition: transform .45s cubic-bezier(.2,.8,.2,1);
        }
        .login-logo-ring:hover { transform: translateY(-5px) scale(1.03); }

        .login-title {
            font-size: clamp(2rem, 4.2vw, 3.1rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -.02em;
            color: #fff;
            text-shadow: 0 3px 24px rgba(0,0,0,.35);
        }
        .login-title .accent {
            background: linear-gradient(100deg, {{ $warnaAksen }}, #ffe08a);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-rule {
            width: 78px; height: 4px; border-radius: 999px;
            background: linear-gradient(90deg, {{ $warnaAksen }}, transparent);
        }

        .login-chip {
            display: flex; align-items: center; gap: .6rem;
            color: rgba(255,255,255,.82);
            font-size: .88rem;
        }
        .login-chip svg { flex: 0 0 auto; opacity: .9; }

        /* ---------- Kartu form ---------- */
        .login-card {
            border-radius: 26px;
            background: rgba(255,255,255,.97);
            border: 1px solid rgba(255,255,255,.55);
            box-shadow: 0 34px 80px rgba(0,0,0,.42);
            backdrop-filter: blur(14px);
            overflow: hidden;
        }
        .login-card__top {
            height: 6px;
            background: linear-gradient(90deg, {{ $warnaUtama }}, {{ $warnaAksen }}, {{ $warnaUtama }});
        }

        .login-field .input-group-text {
            background: transparent;
            border-right: 0;
            color: {{ $warnaUtama }};
        }
        .login-field .form-control {
            border-left: 0;
            padding-left: .25rem;
        }
        .login-field .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .login-field:focus-within .input-group-text,
        .login-field:focus-within .form-control {
            border-color: {{ $warnaUtama }};
        }
        .login-field { transition: transform .18s ease; }
        .login-field:focus-within { transform: translateY(-1px); }

        .login-btn {
            background: linear-gradient(100deg, {{ $warnaUtama }}, #17714a);
            border: 0;
            color: #fff;
            font-weight: 700;
            letter-spacing: .02em;
            padding: .85rem 1rem;
            border-radius: 12px;
            box-shadow: 0 12px 26px {{ $warnaUtama }}4d;
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }
        .login-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            filter: brightness(1.06);
            box-shadow: 0 18px 34px {{ $warnaUtama }}66;
        }
        .login-btn:active:not(:disabled) { transform: translateY(0); }

        .login-eyebrow {
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: {{ $warnaUtama }};
            opacity: .85;
        }

        .login-hint {
            border-radius: 14px;
            background: {{ $warnaUtama }}0f;
            border: 1px dashed {{ $warnaUtama }}40;
            font-size: .8rem;
            color: #3f5668;
        }

        a.login-link { color: {{ $warnaUtama }}; font-weight: 600; }
        a.login-link:hover { color: {{ $warnaAksen }}; }
    </style>

    <!--begin::Page bg-->
    <div class="login-bg">
        <span class="login-blob login-blob--1"></span>
        <span class="login-blob login-blob--2"></span>
        <span class="login-blob login-blob--3"></span>
    </div>
    <!--end::Page bg-->

    <!--begin::Authentication - Sign-in -->
    <div class="login-wrap d-flex flex-column flex-column-fluid flex-lg-row">
        <!--begin::Aside (branding)-->
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <div class="d-flex flex-center flex-lg-start flex-column text-center text-lg-start">
                <!--begin::Logo-->
                <a href="{{ url('/') }}" class="mb-8 d-inline-flex">
                    <span class="login-logo-ring">
                        <img alt="Logo {{ $namaSekolah }}" width="82" height="82" style="object-fit: contain;" src="{{ $logoSekolah }}" />
                    </span>
                </a>
                <!--end::Logo-->

                <!--begin::Badge-->
                <span class="login-badge mb-5">
                    <span class="dot"></span> Panel Admin
                </span>
                <!--end::Badge-->

                <!--begin::Title-->
                <h1 class="login-title mb-4">{{ $namaSekolah }}</h1>
                <!--end::Title-->

                <div class="login-rule mb-4"></div>

                @if ($alamatSekolah && $alamatSekolah !== '-')
                    <div class="login-chip mb-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M8 1.5a4.5 4.5 0 0 0-4.5 4.5c0 3.2 4.5 8.5 4.5 8.5s4.5-5.3 4.5-8.5A4.5 4.5 0 0 0 8 1.5Zm0 6.1a1.6 1.6 0 1 1 0-3.2 1.6 1.6 0 0 1 0 3.2Z"/>
                        </svg>
                        <span>{{ $alamatSekolah }}</span>
                    </div>
                @endif

                <div class="login-chip">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path d="M2 3.5h12a1 1 0 0 1 1 1V12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1Zm.6 1.2L8 7.6l5.4-2.9v-.1H2.6v.1Z"/>
                    </svg>
                    <span class="text-white-50">Sistem Informasi Sekolah</span>
                </div>
            </div>
        </div>
        <!--end::Aside-->

        <!--begin::Body (form)-->
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-8 p-lg-16">
            <!--begin::Card-->
            <div class="login-card d-flex flex-column align-items-stretch flex-center w-100 w-md-540px">
                <div class="login-card__top"></div>
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid px-8 px-lg-14 py-12">
                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <div class="login-eyebrow mb-2">Selamat Datang</div>
                            <h2 class="text-gray-900 fw-bolder mb-2">Masuk ke Akun Anda</h2>
                            <div class="text-gray-500 fw-semibold fs-7">Gunakan email dan password yang diberikan admin</div>
                        </div>
                        <!--end::Heading-->

                        <!--begin::Alert error global-->
                        @if ($errors->has('email') || $errors->has('password'))
                            <div class="alert alert-danger d-flex align-items-center p-4 mb-7 rounded-3" role="alert">
                                <svg width="18" height="18" viewBox="0 0 16 16" fill="currentColor" class="me-3 flex-shrink-0" aria-hidden="true">
                                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1Zm0 3.2a.9.9 0 0 1 .9.9v3.4a.9.9 0 1 1-1.8 0V5.1a.9.9 0 0 1 .9-.9Zm0 7.9a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                </svg>
                                <span class="fs-7 fw-semibold">
                                    {{ $errors->first('email') ?: $errors->first('password') }}
                                </span>
                            </div>
                        @endif
                        <!--end::Alert-->

                        <!--begin::Email-->
                        <div class="fv-row mb-7">
                            <label for="email" class="form-label fw-semibold text-gray-700 fs-7">Email</label>
                            <div class="input-group login-field">
                                <span class="input-group-text" id="email-addon">
                                    <svg width="17" height="17" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M2 3.5h12a1 1 0 0 1 1 1V12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4.5a1 1 0 0 1 1-1Zm.6 1.2L8 7.6l5.4-2.9v-.1H2.6v.1Z"/>
                                    </svg>
                                </span>
                                <input id="email" type="email" class="form-control bg-transparent @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" placeholder="nama@mail.com"
                                       aria-describedby="email-addon" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <!--end::Email-->

                        <!--begin::Password-->
                        <div class="fv-row mb-4">
                            <label for="password" class="form-label fw-semibold text-gray-700 fs-7">Password</label>
                            <div class="input-group login-field">
                                <span class="input-group-text" id="password-addon">
                                    <svg width="17" height="17" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M8 1a3.5 3.5 0 0 0-3.5 3.5V6H4a1.5 1.5 0 0 0-1.5 1.5v5A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 12 6h-.5V4.5A3.5 3.5 0 0 0 8 1Zm2 5H6V4.5a2 2 0 1 1 4 0V6Z"/>
                                    </svg>
                                </span>
                                <input id="password" type="password" class="form-control bg-transparent @error('password') is-invalid @enderror"
                                       name="password" placeholder="••••••••" aria-describedby="password-addon"
                                       required autocomplete="current-password">
                                <button type="button" class="btn btn-icon btn-sm" id="togglePassword"
                                        aria-label="Tampilkan password" title="Tampilkan password"
                                        style="border:1px solid #ced4da; border-left:0;">
                                    <svg id="eyeIcon" width="17" height="17" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M8 3C4.4 3 1.4 5.6.2 8c1.2 2.4 4.2 5 7.8 5s6.6-2.6 7.8-5C14.6 5.6 11.6 3 8 3Zm0 8.2A3.2 3.2 0 1 1 8 4.8a3.2 3.2 0 0 1 0 6.4Zm0-1.6a1.6 1.6 0 1 0 0-3.2 1.6 1.6 0 0 0 0 3.2Z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <!--end::Password-->

                        <!--begin::Remember + lupa password-->
                        <div class="d-flex flex-stack flex-wrap gap-3 mb-8">
                            <label class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="remember" value="1"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="form-check-label fw-semibold text-gray-700 fs-7">Ingat saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="login-link fs-7" href="{{ route('password.request') }}">Lupa password?</a>
                            @endif
                        </div>
                        <!--end::Remember-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-6">
                            <button type="submit" id="kt_sign_in_submit" class="login-btn">
                                <span class="indicator-label d-inline-flex align-items-center justify-content-center gap-2">
                                    Masuk
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M1.5 8a.75.75 0 0 1 .75-.75h9.19L8.72 4.53a.75.75 0 1 1 1.06-1.06l3.5 3.5a.75.75 0 0 1 0 1.06l-3.5 3.5a.75.75 0 1 1-1.06-1.06l2.72-2.72H2.25A.75.75 0 0 1 1.5 8Z"/>
                                    </svg>
                                </span>
                                <span class="indicator-progress d-none">Mohon tunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Hint-->
                        <div class="login-hint p-3 px-4 text-center">
                            Belum punya akun? <span class="fw-bold">Hubungi administrator sekolah</span> —
                            pendaftaran mandiri dinonaktifkan.
                        </div>
                        <!--end::Hint-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Footer-->
                <div class="text-center text-gray-500 fs-8 pb-7">
                    &copy; {{ date('Y') }} {{ $namaSekolah }} &middot; Sistem Informasi Sekolah
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->

    <script>
        // Toggle tampil/sembunyikan password
        (function () {
            var btn = document.getElementById('togglePassword');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('title', show ? 'Sembunyikan password' : 'Tampilkan password');
                input.focus();
            });
        })();

        // Tombol submit: tampilkan spinner (pola template Metronic)
        (function () {
            var form = document.querySelector('form.form');
            var btn = document.getElementById('kt_sign_in_submit');
            if (!form || !btn) return;
            form.addEventListener('submit', function () {
                var label = btn.querySelector('.indicator-label');
                var prog = btn.querySelector('.indicator-progress');
                if (label) label.classList.add('d-none');
                if (prog) prog.classList.remove('d-none');
                btn.disabled = true;
                btn.setAttribute('data-kt-indicator', 'on');
            });
        })();
    </script>
</div>
<!--end::Root-->
</x-auth-layout>
