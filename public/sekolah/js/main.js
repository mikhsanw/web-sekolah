/* Website Sekolah — script halaman publik */
(function () {
    'use strict';

    var HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    var BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function duaDigit(angka) {
        return angka < 10 ? '0' + angka : '' + angka;
    }

    /* --- Tanggal dan jam berjalan di topbar --- */
    function jalankanJam() {
        var elTanggal = document.querySelector('[data-tanggal]');
        var elJam = document.querySelector('[data-jam]');

        if (!elTanggal && !elJam) {
            return;
        }

        function perbarui() {
            var kini = new Date();

            if (elTanggal) {
                elTanggal.textContent = HARI[kini.getDay()] + ', ' + kini.getDate() + ' ' +
                    BULAN[kini.getMonth()] + ' ' + kini.getFullYear();
            }

            if (elJam) {
                var zona = elJam.getAttribute('data-zona') || 'WIB';
                elJam.textContent = duaDigit(kini.getHours()) + ':' + duaDigit(kini.getMinutes()) +
                    ':' + duaDigit(kini.getSeconds()) + ' ' + zona;
            }
        }

        perbarui();
        setInterval(perbarui, 1000);
    }

    /* --- Menu mobile (hamburger) --- */
    function siapkanMenu() {
        var tombol = document.querySelector('[data-toggle-menu]');
        var menu = document.getElementById('menu-utama');

        if (tombol && menu) {
            tombol.addEventListener('click', function () {
                var terbuka = menu.classList.toggle('tampil');
                tombol.setAttribute('aria-expanded', terbuka ? 'true' : 'false');
            });
        }

        // Submenu dibuka dengan klik saat tampilan mobile.
        Array.prototype.forEach.call(document.querySelectorAll('.nav-menu > li > a'), function (tautan) {
            tautan.addEventListener('click', function (e) {
                var induk = tautan.parentElement;
                var punyaSubmenu = induk.querySelector('.submenu');

                if (punyaSubmenu && window.matchMedia('(max-width: 768px)').matches) {
                    e.preventDefault();
                    induk.classList.toggle('terbuka');
                }
            });
        });
    }

    /* --- Panel pencarian --- */
    function siapkanPencarian() {
        var tombol = document.querySelector('[data-toggle-cari]');
        var panel = document.getElementById('panel-pencarian');

        if (!tombol || !panel) {
            return;
        }

        tombol.addEventListener('click', function () {
            var tampil = panel.classList.toggle('tampil');
            tombol.setAttribute('aria-expanded', tampil ? 'true' : 'false');

            if (tampil) {
                var input = panel.querySelector('input[type="search"], input[name="q"]');
                if (input) {
                    input.focus();
                }
            }
        });
    }

    /* --- Tombol cetak --- */
    function siapkanCetak() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-cetak]'), function (tombol) {
            tombol.addEventListener('click', function (e) {
                e.preventDefault();
                window.print();
            });
        });
    }

    /* --- Konfirmasi sederhana (mis. keluar dari sesi SPMB) --- */
    function siapkanKonfirmasi() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-konfirmasi]'), function (elemen) {
            elemen.addEventListener('submit', function (e) {
                if (!window.confirm(elemen.getAttribute('data-konfirmasi'))) {
                    e.preventDefault();
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        jalankanJam();
        siapkanMenu();
        siapkanPencarian();
        siapkanCetak();
        siapkanKonfirmasi();
    });
})();
