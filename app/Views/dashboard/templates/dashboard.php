<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="manifest" href="<?= base_url(); ?>/manifest.json">
    <meta name="theme-color" content="#5eba00">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Favicons -->
    <link href="<?= base_url(); ?>favicon.png" rel="icon" />
    <link href="<?= base_url(); ?>favicon.png" rel="apple-touch-icon" />
    <link href="<?= base_url(); ?>assets/css/dashboard/dashboard.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/css/main.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/css/JawiDubai.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&family=Noto+Sans+Arabic:wdth,wght@62.5..100,100..900&family=Noto+Sans+Mono:wdth,wght@62.5..100,100..900&family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fonts/Geist-1.3.0/font-face.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fonts/GeistMono-1.3.0/font-face.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fonts/base-font/geist.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets_public/fontawesome/css/all.css" rel="stylesheet">
    <script src="<?= base_url(); ?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= base_url(); ?>/service-worker.js')
                    .then((registration) => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch((error) => {
                        console.error('Service Worker registration failed:', error);
                    });
            });
        }
    </script>
    <style>
        /* Custom Scrollbar Styles */
        html {
            scrollbar-width: thin;
            /* For Firefox */
            scrollbar-color: var(--bs-secondary-color) var(--bs-border-color-translucent);
        }

        ::-webkit-scrollbar {
            width: 16px;
            height: 16px;
        }

        ::-webkit-scrollbar-track {
            background-color: var(--bs-border-color-translucent);
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--bs-secondary-color);
            border-radius: 10px;
            border: 4px solid var(--bs-border-color-translucent);
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--bs-secondary-color);
        }

        ::-webkit-scrollbar-button:single-button {
            background-color: var(--bs-secondary-color);
            border: 1px solid var(--bs-border-color-translucent);
            width: 16px;
            height: 16px;
        }

        ::-webkit-scrollbar-button:single-button:vertical:decrement {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23ffffff" viewBox="0 0 16 16"><path d="M4 10l4-4 4 4H4z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        ::-webkit-scrollbar-button:single-button:vertical:increment {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23ffffff" viewBox="0 0 16 16"><path d="M12 6L8 10 4 6h8z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        ::-webkit-scrollbar-button:single-button:horizontal:decrement {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23ffffff" viewBox="0 0 16 16"><path d="M10 12l-4-4 4-4v8z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        ::-webkit-scrollbar-button:single-button:horizontal:increment {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="%23ffffff" viewBox="0 0 16 16"><path d="M6 4l4 4-4 4V4z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            overflow: auto;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
            /* Use viewport height to ensure full height */
        }

        .header {
            flex-shrink: 0;
            /* Prevent header from shrinking */
        }

        .main-content-wrapper {
            display: flex;
            flex: 1;
            /* Allows this container to grow and fill the remaining space */
            overflow: hidden;
            /* Prevents overflow from affecting the container's size */
            padding-left: calc(var(--bs-gutter-x) * .5);
            padding-right: calc(var(--bs-gutter-x) * .5);
        }

        .toast-container {
            padding-top: 4rem !important;
            right: 0 !important;
        }

        .sidebar {
            box-shadow: inset 0px 0 0 rgba(0, 0, 0, 0);
            border-right: 1px solid var(--bs-border-color-translucent);
            height: 100%;
            overflow: auto;
        }

        .main-content {
            flex: 1;
            /* Allows the content area to grow and fill remaining space */
            overflow: auto;
            /* Enables scrolling in the content area */
        }

        .profilephotosidebar {
            background-color: var(--bs-gray-500);
            width: 32px;
            aspect-ratio: 1/1;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            outline: 3px solid var(--bs-gray-500);
            box-shadow: 0 0 0 4px var(--bs-border-color);
        }

        .profilephotosidebar svg {
            fill: var(--bs-body-color);
            /* Bootstrap white color */
        }

        div.dataTables_processing>div:last-child {
            display: none;
        }

        div.dataTables_wrapper div.dataTables_processing.card {
            position: fixed;
            margin: 0 !important;
            z-index: 999;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            background-color: rgba(var(--bs-body-bg-rgb), var(--bs-bg-opacity)) !important;
            --bs-bg-opacity: 0.6667;
            backdrop-filter: blur(20px);
            border: 1px solid var(--bs-border-color-translucent);
            box-shadow: var(--bs-box-shadow) !important;
            border-radius: var(--bs-border-radius-lg) !important;
        }

        .modal-body div.dataTables_wrapper div.dataTables_processing.card {
            background-color: rgba(var(--bs-body-bg-rgb), var(--bs-bg-opacity)) !important;
            --bs-bg-opacity: 1;
            backdrop-filter: none;
        }

        .two-column-list {
            columns: 2;
            /* Creates two columns */
            -webkit-columns: 2;
            /* For Safari and older browsers */
            -moz-columns: 2;
            /* For Firefox */
        }

        .two-column-list li {
            break-inside: avoid-column;
            /* Prevents items from breaking between columns */
            word-break: break-all;
        }

        .dropdown-item-pec {
            color: var(--bs-light);
        }

        .dropdown-item-pec:hover,
        .dropdown-item-pec:focus {
            color: var(--bs-dark);
            background-color: var(--bs-light);
            box-shadow: inset 0 0 0 1px var(--bs-light);
        }

        .dropdown-item-pec.active,
        .dropdown-item-pec:active {
            color: var(--bs-dark);
            text-decoration: none;
            background-color: var(--bs-light);
            box-shadow: inset 0 0 0 1px var(--bs-light);
        }

        @media (prefers-reduced-transparency) {
            div.dataTables_wrapper div.dataTables_processing.card {
                --bs-bg-opacity: 1;
                backdrop-filter: none;
            }
        }

        @media (max-width: 767.98px) {
            .toast-container {
                padding-top: 7rem !important;
                transform: translateX(-50%) !important;
                left: 50% !important;
            }

            .sidebar {
                top: 48px;
                backdrop-filter: blur(20px);
                --bs-bg-opacity: 0.6667;
                border-right: 0px solid var(--bs-border-color-translucent);
                width: 100%;
            }

            #sidebarMenu2 {
                height: calc(100vh - 96px);
                padding-top: 0;
            }

            #sidebarMenu {
                opacity: 0;
                transition: opacity 0.25s ease-out, transform 0.25s ease-out;
                transform: translateY(-10px);
            }

            #sidebarMenu.show {
                opacity: 1;
                transform: translateY(0);
            }

            @media (prefers-reduced-motion: reduce) {
                #sidebarMenu {
                    transition: none;
                }
            }

            @media (prefers-reduced-transparency) {
                .sidebar {
                    --bs-bg-opacity: 1;
                    backdrop-filter: none;
                }
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- HEADER -->
        <header class="navbar sticky-top flex-md-nowrap p-0 shadow-sm text-white header" style="border-bottom: 1px solid var(--bs-border-color-translucent); background-color: #5eba00;" data-bs-theme="dark">
            <div class="d-flex justify-content-center align-items-center col-md-3 col-lg-2 me-0 px-3 py-md-1" style="min-height: 48px; max-height: 48px;">
                <span class="navbar-brand mx-0 text-start text-md-center lh-1 d-flex justify-content-center align-items-center" style="font-size: 7pt;">
                    <img src="<?= base_url('/assets/images/logo_pec.png'); ?>" alt="KLINIK MATA PECTK" height="24px" style="filter: drop-shadow(0 0 0.25rem white);">
                    <div class="ps-2 text-start">KLINIK UTAMA MATA<br><span class="fw-bold">PADANG EYE CENTER</span><br>TELUK KUANTAN</div>
                </span>
            </div>
            <button class="navbar-toggler position-absolute text-white d-md-none bg-gradient rounded-3 border-white collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex flex-nowrap w-100 align-items-center" style="min-height: 48px;">
                <div class="w-100 ps-3 pe-1 text-truncate">
                    <?= $this->renderSection('title'); ?>
                </div>
                <div class="dropdown mx-3">
                    <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-pill bg-body profilephotosidebar me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM14 14s-1-4-6-4-6 4-6 4 1 0 6 0 6 0 6 0z" />
                            </svg>
                        </div>
                    </a>
                    <ul class="dropdown-menu rounded-3 dropdown-menu-end text-small shadow transparent-blur" style="width: 240px; background-color: #5eba00;">
                        <li class="lh-1 p-1">
                            <div class="d-flex align-items-start text-light">
                                <span style="width: 32px; text-align: center;"><i class="fa-solid fa-user"></i></span>
                                <span><?= session()->get('fullname'); ?><br><span class="fw-medium" style="font-size: 8pt;">@<?= session()->get('username'); ?></span><br><span class="fw-medium" style="font-size: 8pt;"><?= session()->get('role'); ?></span></span>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item p-1 dropdown-item-pec" href="<?= base_url('/settings'); ?>" onclick="showSpinner()">
                                <div class="d-flex align-items-start">
                                    <span style="width: 32px; text-align: center;"><i class="fa-solid fa-gear"></i></span>
                                    <span>Pengaturan</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item p-1 dropdown-item-pec" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <div class="d-flex align-items-start">
                                    <span style="width: 32px; text-align: center;"><i class="fa-solid fa-right-from-bracket"></i></span>
                                    <span>Keluar</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="modal modal-sheet p-4 py-md-5 fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModal" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-body rounded-4 shadow-lg transparent-blur">
                    <div class="modal-body p-4 text-center">
                        <h5 class="mb-0" id="logoutMessage">Apakah Anda ingin keluar?</h5>
                    </div>
                    <div class="modal-footer flex-nowrap p-0" style="border-top: 1px solid var(--bs-border-color-translucent);">
                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal" style="border-right: 1px solid var(--bs-border-color-translucent);">Tidak</button>
                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" id="confirmLogout" onclick="window.location.href='<?= base_url('/logout'); ?>';">Yes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENTS -->
        <div class="main-content-wrapper">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar shadow-sm collapse" style="background-color: #5eba00;">
                <div id="sidebarMenu2" class="position-sticky sidebar-sticky p-1">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link p-2 <?= (strpos(uri_string(), 'home') === 0) ? 'active bg-light' : '' ?>" href=" <?= base_url('/home'); ?>">
                                <div class="d-flex align-items-start <?= (strpos(uri_string(), 'home') === 0) ? 'link-dark' : 'link-light' ?>">
                                    <div style="min-width: 24px; max-width: 24px; text-align: center;">
                                        <i class="fa-solid fa-house"></i>
                                    </div>
                                    <div class="flex-fill ms-2">
                                        Beranda
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php if (session()->get('role') == "Admin") : ?>
                            <li class="nav-item">
                                <a class="nav-link p-2 <?= (strpos(uri_string(), 'admin') === 0) ? 'active bg-light' : '' ?>" href=" <?= base_url('/admin'); ?>">
                                    <div class="d-flex align-items-start <?= (strpos(uri_string(), 'admin') === 0) ? 'link-dark' : 'link-light' ?>">
                                        <div style="min-width: 24px; max-width: 24px; text-align: center;">
                                            <i class="fa-solid fa-users"></i>
                                        </div>
                                        <div class="flex-fill ms-2">
                                            Pengguna
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
            <main class="main-content">
                <?= $this->renderSection('content'); ?>
            </main>
        </div>
        <div id="toastContainer" class="toast-container position-fixed top-0 p-3" aria-live="polite" aria-atomic="true">
            <?php if (session()->getFlashdata('info')) : ?>
                <div class="toast fade show align-items-center text-bg-info border border-info rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body d-flex align-items-start">
                        <div style="width: 24px; text-align: center;">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div class="w-100 mx-2 text-start">
                            <?= session()->getFlashdata('info'); ?>
                        </div>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('msg')) : ?>
                <div class="toast fade show align-items-center text-bg-success border border-success rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
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
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="toast fade show align-items-center text-bg-danger border border-danger rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
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
            <?= $this->renderSection('toast'); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="<?= base_url(); ?>assets_public/fontawesome/js/all.js"></script>
    <script src="<?= base_url(); ?>assets/js/dashboard/dashboard.js"></script>
    <?= $this->renderSection('javascript'); ?>
    <?= $this->renderSection('datatable'); ?>
    <?= $this->renderSection('tinymce'); ?>
    <?= $this->renderSection('chartjs'); ?>
    <?= $this->renderSection('imgupload'); ?>
    <script>
        function showSpinner() {
            $('#loadingSpinner').show();
        }
        $(window).on('beforeunload', function() {
            showSpinner();
        });
        $(document).on('click', '#confirmLogout', function() {
            $('#logoutModal button').prop('disabled', true);
            $('#logoutMessage').html(`Silakan tunggu...`);
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
    <script>
        feather.replace({
            'aria-hidden': 'true'
        });
    </script>
</body>

</html>