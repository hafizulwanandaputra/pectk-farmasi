<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="manifest" href="<?= base_url(); ?>/manifest.json">
    <meta name="theme-color" content="#d1e7dd" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#051b11" media="(prefers-color-scheme: dark)">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?= base_url(); ?>favicon.png" rel="icon" />
    <link href="<?= base_url(); ?>favicon.png" rel="apple-touch-icon" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/heroes/">
    <link href="<?= base_url(); ?>assets_public/fontawesome/css/all.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/css/main.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/css/JawiDubai.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&family=Noto+Sans+Arabic:wdth,wght@62.5..100,100..900&family=Noto+Sans+Mono:wdth,wght@62.5..100,100..900&family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.typekit.net/dew1xab.css">
    <link href="<?= base_url(); ?>assets_public/fonts/inter-hwp/inter-hwp.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fonts/base-font.css" rel="stylesheet">
    <style>
        .kbd {
            border-radius: 4px !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>

<body class="bg-success-subtle d-flex justify-content-center align-items-center vh-100">

    <div class="container col-xl-10 col-xxl-8 px-4 py-5">
        <div class="row align-items-center g-lg-5 py-5">
            <div class="col-lg-7 text-center text-lg-start">
                <img class="mb-3" src="<?= base_url('/assets/images/logo_pec.png'); ?>" width="128px">
                <h1 class="display-5 fw-bold lh-1 text-success-emphasis mb-3">Kasir dan Farmasi<br>PEC Teluk Kuantan</h1>
                <p class="col-lg-10 fs-4 text-success-emphasis">Sistem Informasi Kasir dan Farmasi Klinik Utama Mata Padang Eye Center Teluk Kuantan</p>
            </div>
            <div class="col-md-10 mx-auto col-lg-5">
                <?= form_open('check-login', 'id="loginForm"'); ?>
                <div class="p-4 border rounded-3 bg-body-tertiary transparent-blur shadow-lg">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control <?= (validation_show_error('username')) ? 'is-invalid' : ''; ?>" id="floatingInput" name="username" placeholder="Nama Pengguna" value="" autocomplete="off" list="username">
                        <datalist id="username">
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['username'] ?>">
                                <?php endforeach; ?>
                        </datalist>
                        <label for="floatingInput">
                            <div class="d-flex align-items-start">
                                <div style="width: 12px; text-align: center;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="w-100 ms-3">
                                    Nama Pengguna
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control <?= (validation_show_error('password')) ? 'is-invalid' : ''; ?>" id="floatingPassword" name="password" placeholder="Kata Sandi" autocomplete="off" data-bs-toggle="popover"
                            data-bs-placement="top"
                            data-bs-trigger="manual"
                            data-bs-title="<em>CAPS LOCK</em> AKTIF"
                            data-bs-content="Harap periksa status <span class='badge text-bg-dark bg-gradient kbd'>Caps Lock</span> pada papan tombol (<em>keyboard</em>) Anda.">
                        <label for="floatingPassword">
                            <div class="d-flex align-items-start">
                                <div style="width: 12px; text-align: center;">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <div class="w-100 ms-3">
                                    Kata Sandi
                                </div>
                            </div>
                        </label>
                    </div>
                    <input type="hidden" name="url" value="<?= (isset($_GET['redirect'])) ? base_url('/' . urldecode($_GET['redirect'])) : base_url('/home'); ?>">
                    <button id="loginBtn" class="w-100 btn btn-lg btn-primary bg-gradient rounded" type="submit">
                        <i class="fa-solid fa-right-to-bracket"></i> MASUK
                    </button>
                    <hr>
                    <div class="text-center">
                        <small class="text-body-secondary">
                            <span class="">&copy; 2024 <?= (date('Y') !== "2024") ? "- " . date('Y') : ''; ?> Klinik Utama Mata Padang Eye Center Teluk Kuantan</span>
                        </small>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
        <?php if (session()->getFlashdata('msg')) : ?>
            <div id="msgToast" class="toast align-items-center text-bg-success border border-success transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="w-100 mx-2 text-start">
                        <?= session()->getFlashdata('msg'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['redirect'])) : ?>
            <div id="redirectToast" class="toast align-items-center text-bg-danger border border-danger transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="w-100 mx-2 text-start">
                        Silakan masuk sebelum mengunjungi "<?= urldecode($_GET['redirect']); ?>"
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div id="errorToast" class="toast align-items-center text-bg-danger border border-danger transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="w-100 mx-2 text-start">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (validation_show_error('username') || validation_show_error('password')) : ?>
            <div id="validationToast" class="toast align-items-center text-bg-danger border border-danger transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="w-100 mx-2 text-start">
                        Gagal masuk:<br><?= validation_show_error('username') ?><br><?= validation_show_error('password') ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= base_url(); ?>assets_public/fontawesome/js/all.js"></script>
    <script>
        $(document).ready(function() {
            // Menangani semua input password dengan jQuery
            $('input[type="password"]').each(function() {
                const passwordInput = $(this); // Menggunakan jQuery untuk elemen input
                const popover = new bootstrap.Popover(passwordInput[0], {
                    html: true,
                    template: '<div class="popover shadow-lg" role="tooltip">' +
                        '<div class="popover-arrow"></div>' +
                        '<h3 class="popover-header"></h3>' +
                        '<div class="popover-body">Caps Lock aktif!</div>' +
                        '</div>'
                });

                let capsLockActive = false; // Status Caps Lock sebelumnya

                // Menambahkan event listener untuk 'focus' pada setiap input password
                passwordInput.on('focus', function() {
                    passwordInput[0].addEventListener('keyup', function(event) {
                        const currentCapsLock = event.getModifierState('CapsLock'); // Memeriksa status Caps Lock

                        // Jika status Caps Lock berubah
                        if (currentCapsLock !== capsLockActive) {
                            capsLockActive = currentCapsLock; // Perbarui status
                            if (capsLockActive) {
                                popover.show(); // Tampilkan popover jika Caps Lock aktif
                            } else {
                                popover.hide(); // Sembunyikan popover jika Caps Lock tidak aktif
                            }
                        }
                    });
                });

                // Menambahkan event listener untuk 'blur' pada setiap input password
                passwordInput.on('blur', function() {
                    popover.hide(); // Sembunyikan popover saat kehilangan fokus
                    passwordInput[0].removeEventListener('keyup', function() {}); // Hapus listener keyup saat blur
                    capsLockActive = false; // Reset status Caps Lock
                });
            });

            // Mengecek apakah elemen dengan id 'redirectToast' ada di dalam dokumen
            if ($('#redirectToast').length) {
                var redirectToast = new bootstrap.Toast($('#redirectToast')[0]);
                redirectToast.show(); // Menampilkan toast redirect
            }

            // Mengecek apakah elemen dengan id 'msgToast' ada di dalam dokumen
            if ($('#msgToast').length) {
                var msgToast = new bootstrap.Toast($('#msgToast')[0]);
                msgToast.show(); // Menampilkan toast pesan
            }

            // Mengecek apakah elemen dengan id 'errorToast' ada di dalam dokumen
            if ($('#errorToast').length) {
                var errorToast = new bootstrap.Toast($('#errorToast')[0]);
                errorToast.show(); // Menampilkan toast error
            }

            // Mengecek apakah elemen dengan id 'validationToast' ada di dalam dokumen
            if ($('#validationToast').length) {
                var validationToast = new bootstrap.Toast($('#validationToast')[0]);
                validationToast.show(); // Menampilkan toast validasi
            }

            // Mengatur waktu untuk menyembunyikan toast setelah 5 detik
            setTimeout(function() {
                // Mengecek kembali untuk menyembunyikan setiap toast jika ada
                if ($('#redirectToast').length) {
                    var redirectToast = new bootstrap.Toast($('#redirectToast')[0]);
                    redirectToast.hide(); // Menyembunyikan toast redirect
                }

                if ($('#msgToast').length) {
                    var msgToast = new bootstrap.Toast($('#msgToast')[0]);
                    msgToast.hide(); // Menyembunyikan toast pesan
                }

                if ($('#errorToast').length) {
                    var errorToast = new bootstrap.Toast($('#errorToast')[0]);
                    errorToast.hide(); // Menyembunyikan toast error
                }

                if ($('#validationToast').length) {
                    var validationToast = new bootstrap.Toast($('#validationToast')[0]);
                    validationToast.hide(); // Menyembunyikan toast validasi
                }
            }, 5000); // Durasi waktu untuk menyembunyikan toast (5000 ms)

            // Menghapus kelas 'is-invalid' dan menyembunyikan pesan invalid ketika input diubah
            $('input.form-control').on('input', function() {
                $(this).removeClass('is-invalid'); // Menghapus kelas 'is-invalid'
                $(this).siblings('.invalid-feedback').hide(); // Menyembunyikan pesan feedback invalid
            });

            // Menangani event klik pada tombol login
            $(document).on('click', '#loginBtn', function(e) {
                e.preventDefault(); // Mencegah aksi default tombol
                $('#loginForm').submit(); // Mengirimkan form login
                $('input').prop('disabled', true).removeClass('is-invalid'); // Menonaktifkan semua input dan menghapus kelas 'is-invalid'
                $('#loginBtn').prop('disabled', true).html(`
            <span class="spinner-border" style="width: 1em; height: 1em;" aria-hidden="true"></span>
            <span role="status">SILAKAN TUNGGU...</span>
        `); // Menampilkan spinner dan teks 'SILAKAN TUNGGU...' pada tombol login
            });
        });
    </script>
    <script>
        /*!
         * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
         * Copyright 2011-2023 The Bootstrap Authors
         * Licensed under the Creative Commons Attribution 3.0 Unported License.
         */

        (() => {
            'use strict'

            const getStoredTheme = () => localStorage.getItem('theme')
            const setStoredTheme = theme => localStorage.setItem('theme', theme)

            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme()
                if (storedTheme) {
                    return storedTheme
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            }

            const setTheme = theme => {
                if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.setAttribute('data-bs-theme', 'dark')
                } else {
                    document.documentElement.setAttribute('data-bs-theme', theme)
                }
            }

            setTheme(getPreferredTheme())

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitcher = document.querySelector('#bd-theme')

                if (!themeSwitcher) {
                    return
                }

                const themeSwitcherText = document.querySelector('#bd-theme-text')
                const activeThemeIcon = document.querySelector('.theme-icon-active use')
                const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active')
                    element.setAttribute('aria-pressed', 'false')
                })

                btnToActive.classList.add('active')
                btnToActive.setAttribute('aria-pressed', 'true')
                activeThemeIcon.setAttribute('href', svgOfActiveBtn)
                const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
                themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

                if (focus) {
                    themeSwitcher.focus()
                }
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const storedTheme = getStoredTheme()
                if (storedTheme !== 'light' && storedTheme !== 'dark') {
                    setTheme(getPreferredTheme())
                }
            })

            window.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme())

                document.querySelectorAll('[data-bs-theme-value]')
                    .forEach(toggle => {
                        toggle.addEventListener('click', () => {
                            const theme = toggle.getAttribute('data-bs-theme-value')
                            setStoredTheme(theme)
                            setTheme(theme)
                            showActiveTheme(theme, true)
                        })
                    })
            })
        })()
    </script>
</body>

</html>