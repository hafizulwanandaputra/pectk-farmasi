<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('css'); ?>
<?= $this->include('select2/normal'); ?>
<?= $this->endSection(); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?> <span id="totalRecords" class="date"></span></span></span>
    <div id="loadingSpinner" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-3 px-md-4 pt-3">
    <div class="d-flex flex-column flex-lg-row mb-1 gap-2 mb-3">
        <select id="statusFilter" class="form-select form-select-sm w-auto rounded-3">
            <option value="">Semua</option>
            <option value="1">Diproses</option>
            <option value="0">Belum Diproses</option>
        </select>
        <div class="input-group input-group-sm flex-fill">
            <input type="search" id="searchInput" class="form-control rounded-start-3" placeholder="Cari pasien, petugas kasir, dan tanggal transaksi...">
            <button class="btn btn-success btn-sm bg-gradient" type="button" id="refreshButton"><i class="fa-solid fa-sync"></i></button>
            <button class="btn btn-body btn-sm bg-gradient rounded-end-3" type="button" id="reportButton" onclick="window.location.href = '<?= base_url('transaksi/report') ?>';"><i class="fa-solid fa-file-export"></i> Laporan</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <fieldset class="border rounded-3 px-2 py-0 mb-3">
                <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Tambah Pasien Rawat Jalan</legend>
                <form id="transaksiForm1" enctype="multipart/form-data" class="d-flex flex-column mb-2 gap-2">
                    <div class="flex-fill">
                        <select class="form-select rounded-3" id="nomor_registrasi" name="nomor_registrasi" aria-label="nomor_registrasi">
                            <option value="" disabled selected>-- Pilih Pasien Rawat Jalan --</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="submitButtonContainer">
                        <button type="submit" id="submitButton1" class="btn btn-primary bg-gradient rounded-3" disabled>
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>
                </form>
            </fieldset>
        </div>
        <div class="col-lg-6">
            <fieldset class="border rounded-3 px-2 py-0 mb-3">
                <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Tambah Pasien dari Resep Luar</legend>
                <form id="transaksiForm2" enctype="multipart/form-data" class="d-flex flex-column mb-2 gap-2">
                    <div class="flex-fill">
                        <select class="form-select rounded-3" id="id_resep" name="id_resep" aria-label="id_resep">
                            <option value="" disabled selected>-- Pilih Pasien dari Resep Luar --</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="submitButtonContainer">
                        <button type="submit" id="submitButton2" class="btn btn-primary bg-gradient rounded-3" disabled>
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
    <ul id="transaksiContainer" class="list-group shadow-sm rounded-3 mt-1">
        <?php for ($i = 0; $i < 12; $i++) : ?>
            <li class="list-group-item bg-body-tertiary pb-3 pt-3">
                <div class="d-flex">
                    <div class="align-self-center ps-2 w-100">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder" style="width: 100%"></span>
                        </h5>
                        <h6 class="card-subtitle mb-2 placeholder-glow">
                            <span class="placeholder" style="width: 25%;"></span>
                        </h6>
                        <p class="card-text placeholder-glow">
                            <small>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span>
                            </small>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <a class="btn btn-body bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    <a class="btn btn-danger bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                </div>
            </li>
        <?php endfor; ?>
    </ul>
    <nav id="paginationNav" class="d-flex justify-content-center justify-content-lg-end mt-3 overflow-auto w-100">
        <ul class="pagination pagination-sm" style="--bs-pagination-border-radius: var(--bs-border-radius-lg);"></ul>
    </nav>
    <div class="modal modal-sheet p-4 py-md-5 fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-body-tertiary rounded-4 shadow-lg transparent-blur">
                <div class="modal-body p-4 text-center">
                    <h5 id="deleteMessage"></h5>
                    <h6 class="mb-0" id="deleteSubmessage"></h6>
                </div>
                <div class="modal-footer flex-nowrap p-0" style="border-top: 1px solid var(--bs-border-color-translucent);">
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end" style="border-right: 1px solid var(--bs-border-color-translucent)!important;" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" id="confirmDeleteBtn">Ya</button>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>
<?= $this->section('datatable'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script>
    let limit = 12;
    let currentPage = 1;
    let transaksiId = null;
    var placeholder = `
            <li class="list-group-item bg-body-tertiary pb-3 pt-3">
                <div class="d-flex">
                    <div class="align-self-center ps-2 w-100">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder" style="width: 100%"></span>
                        </h5>
                        <h6 class="card-subtitle mb-2 placeholder-glow">
                            <span class="placeholder" style="width: 25%;"></span>
                        </h6>
                        <p class="card-text placeholder-glow">
                            <small>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span><br>
                                <span class="placeholder" style="width: 12.5%;"></span>
                            </small>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <a class="btn btn-body bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    <a class="btn btn-danger bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                </div>
            </li>
    `;
    async function fetchPasienOptions1() {
        try {
            const response = await axios.get('<?= base_url('transaksi/pasienlist') ?>');

            if (response.data.success) {
                const options = response.data.data;
                const select = $('#nomor_registrasi');

                // Clear existing options except the first one
                select.find('option:not(:first)').remove();

                // Sort the options by 'value' in ascending order
                options.sort((a, b) => b.value.localeCompare(a.value, 'en', {
                    numeric: true
                }));

                // Loop through the options and append them to the select element
                options.forEach(option => {
                    select.append(`<option value="${option.value}">${option.text}</option>`);
                });
            }
        } catch (error) {
            showFailedToast('Gagal mendapatkan pasien.<br>' + error);
        }
    }
    async function fetchPasienOptions2() {
        try {
            const response = await axios.get('<?= base_url('transaksi/pasienlistexternal') ?>');

            if (response.data.success) {
                const options = response.data.data;
                const select = $('#id_resep');

                // Clear existing options except the first one
                select.find('option:not(:first)').remove();

                // Sort the options by 'value' in ascending order
                options.sort((a, b) => b.value.localeCompare(a.value, 'en', {
                    numeric: true
                }));

                // Loop through the options and append them to the select element
                options.forEach(option => {
                    select.append(`<option value="${option.value}">${option.text}</option>`);
                });
            }
        } catch (error) {
            showFailedToast('Gagal mendapatkan pasien.<br>' + error);
        }
    }
    async function fetchTransaksi() {
        const search = $('#searchInput').val();
        const offset = (currentPage - 1) * limit;
        const status = $('#statusFilter').val();

        // Show the spinner
        $('#loadingSpinner').show();

        try {
            const response = await axios.get('<?= base_url('transaksi/listtransaksi') ?>', {
                params: {
                    search: search,
                    limit: limit,
                    offset: offset,
                    status: status
                }
            });

            const data = response.data;
            $('#transaksiContainer').empty();
            $('#totalRecords').text(`(${data.total})`);

            if (data.total === 0) {
                $('#paginationNav ul').empty();
                $('#transaksiContainer').append(
                    '<li class="list-group-item bg-body-tertiary pb-3 pt-3">' +
                    '    <h1 class="display-4 text-center text-muted" style="font-weight: 100;">Data Kosong</h1>' +
                    '</li>'
                );
            } else {
                data.transaksi.forEach(function(transaksi) {
                    const total_pembayaran = parseInt(transaksi.total_pembayaran);
                    const statusBadge = transaksi.lunas == '1' ?
                        `<span class="badge bg-success bg-gradient">Transaksi Diproses</span>` :
                        `<span class="badge bg-danger bg-gradient">Transaksi Belum Diproses</span>`;
                    const bank = transaksi.bank ? ` (${transaksi.bank})` : ``;
                    const metode_pembayaran = transaksi.metode_pembayaran == '' ?
                        `<em>Belum ada</em>` :
                        transaksi.metode_pembayaran + bank;
                    const resepluar = transaksi.id_resep ? `<span class="badge bg-secondary bg-gradient">Resep Luar</span>` : ``;
                    const transaksiElement = `
            <li class="list-group-item bg-body-tertiary pb-3 pt-3">
                <div class="d-flex">
                    <div class="align-self-center ps-2 w-100">
                        <h5 class="card-title">
                            ${transaksi.nama_pasien} ${resepluar}
                        </h5>
                        <h6 class="card-subtitle mb-2">
                            ${transaksi.kasir}
                        </h6>
                        <p class="card-text">
                            <small class="date">
                                ID Transaksi: ${transaksi.id_transaksi}<br>
                                Nomor Kwitansi: ${transaksi.no_kwitansi}<br>
                                Tanggal dan Waktu Transaksi: ${transaksi.tgl_transaksi}<br>
                                Total Pembayaran: Rp${total_pembayaran.toLocaleString('id-ID')}<br>
                                Metode Pembayaran: ${metode_pembayaran}<br>
                                ${statusBadge}
                            </small>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-body btn-sm bg-gradient rounded-3" onclick="window.location.href = '<?= base_url('transaksi/detailtransaksi') ?>/${transaksi.id_transaksi}';">
                        <i class="fa-solid fa-circle-info"></i> Detail
                    </button>
                    <button type="button" class="btn btn-danger btn-sm bg-gradient rounded-3 delete-btn" data-id="${transaksi.id_transaksi}" data-name="${transaksi.nama_pasien}" data-date="${transaksi.tgl_transaksi}">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            </li>
                `;

                    $('#transaksiContainer').append(transaksiElement);
                });

                const totalPages = Math.ceil(data.total / limit);
                $('#paginationNav ul').empty();

                if (currentPage > 1) {
                    $('#paginationNav ul').append(`
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="1">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="${currentPage - 1}">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>
                `);
                }

                for (let i = 1; i <= totalPages; i++) {
                    $('#paginationNav ul').append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link bg-gradient date" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
                }

                if (currentPage < totalPages) {
                    $('#paginationNav ul').append(`
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="${currentPage + 1}">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="${totalPages}">
                            <i class="fa-solid fa-angles-right"></i>
                        </a>
                    </li>
                `);
                }
            }
        } catch (error) {
            showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
            $('#transaksiContainer').empty();
            $('#paginationNav ul').empty();
        } finally {
            // Hide the spinner when done
            $('#loadingSpinner').hide();
        }
    }

    $(document).on('click', '#paginationNav a', function(event) {
        event.preventDefault(); // Prevents default behavior (scrolling)
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchPasienOptions1()
            fetchPasienOptions2()
            fetchTransaksi();
        }
    });

    $('#statusFilter').on('change', function() {
        $('#transaksiContainer').empty();
        for (let i = 0; i < limit; i++) {
            $('#transaksiContainer').append(placeholder);
        }
        fetchPasienOptions1()
        fetchPasienOptions2()
        fetchTransaksi();
    });

    function toggleSubmitButton1() {
        var selectedValue = $('#nomor_registrasi').val();
        if (selectedValue === null || selectedValue === "") {
            $('#submitButton1').prop('disabled', true);
        } else {
            $('#submitButton1').prop('disabled', false);
        }
    }
    $('#nomor_registrasi').on('change.select2', function() {
        toggleSubmitButton1();
    });

    function toggleSubmitButton2() {
        var selectedValue = $('#id_resep').val();
        if (selectedValue === null || selectedValue === "") {
            $('#submitButton2').prop('disabled', true);
        } else {
            $('#submitButton2').prop('disabled', false);
        }
    }
    $('#id_resep').on('change.select2', function() {
        toggleSubmitButton2();
    });

    $(document).ready(function() {
        $('#nomor_registrasi').select2({
            dropdownParent: $('#transaksiForm1'),
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#id_resep').select2({
            dropdownParent: $('#transaksiForm2'),
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#searchInput').on('input', function() {
            currentPage = 1;
            fetchTransaksi();
        });

        // Store the ID of the user to be deleted
        var transaksiId;
        var transaksiName;
        var transaksiDate;

        // Show delete confirmation modal
        $(document).on('click', '.delete-btn', function() {
            transaksiId = $(this).data('id');
            transaksiName = $(this).data('name');
            transaksiDate = $(this).data('date');
            $('[data-bs-toggle="tooltip"]').tooltip('hide');
            $('#deleteMessage').html(`Hapus transaksi dari "` + transaksiName + `"?`);
            $('#deleteSubmessage').html(`Tanggal Transaksi: ` + transaksiDate);
            $('#deleteModal').modal('show');
        });

        $('#confirmDeleteBtn').click(async function() {
            $('#deleteModal button').prop('disabled', true);
            $('#deleteMessage').addClass('mb-0').html('Mengapus, silakan tunggu...');
            $('#deleteSubmessage').hide();

            try {
                await axios.delete(`<?= base_url('/transaksi/delete') ?>/${transaksiId}`);
                fetchPasienOptions1();
                fetchPasienOptions2();
                fetchTransaksi();
            } catch (error) {
                if (error.response.request.status === 401) {
                    showFailedToast(error.response.data.message);
                } else {
                    showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
                }
            } finally {
                $('#deleteModal').modal('hide');
                $('#deleteMessage').removeClass('mb-0');
                $('#deleteSubmessage').show();
                $('#deleteModal button').prop('disabled', false);
            }
        });

        $('#transaksiForm1').submit(async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            console.log("Form Data:", $(this).serialize());

            // Clear previous validation states
            $('#transaksiForm1 .is-invalid').removeClass('is-invalid');
            $('#transaksiForm1 .invalid-feedback').text('').hide();
            $('#submitButton1').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Tambah
            `);

            // Disable form inputs
            $('#transaksiForm1 select').prop('disabled', true);

            try {
                const response = await axios.post(`<?= base_url('transaksi/create') ?>`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    $('#transaksiForm1')[0].reset();
                    $('#nomor_registrasi').val(null).trigger('change');
                    $('#transaksiForm1 .is-invalid').removeClass('is-invalid');
                    $('#transaksiForm1 .invalid-feedback').text('').hide();
                    $('#submitButton1').prop('disabled', true);
                    fetchPasienOptions1();
                    fetchTransaksi();
                } else {
                    console.log("Validation Errors:", response.data.errors);

                    // Clear previous validation states
                    $('#transaksiForm1 .is-invalid').removeClass('is-invalid');
                    $('#transaksiForm1 .invalid-feedback').text('').hide();

                    // Display new validation errors
                    for (const field in response.data.errors) {
                        if (response.data.errors.hasOwnProperty(field)) {
                            const fieldElement = $('#' + field);
                            const feedbackElement = fieldElement.siblings('.invalid-feedback');

                            console.log("Target Field:", fieldElement);
                            console.log("Target Feedback:", feedbackElement);

                            if (fieldElement.length > 0 && feedbackElement.length > 0) {
                                fieldElement.addClass('is-invalid');
                                feedbackElement.text(response.data.errors[field]).show();

                                // Remove error message when the user corrects the input
                                fieldElement.on('input change', function() {
                                    $(this).removeClass('is-invalid');
                                    $(this).siblings('.invalid-feedback').text('').hide();
                                });
                            } else {
                                console.warn("Elemen tidak ditemukan pada field:", field);
                            }
                        }
                    }
                    console.error('Perbaiki kesalahan pada formulir.');
                }
            } catch (error) {
                if (error.response.request.status === 404) {
                    showFailedToast(error.response.data.message);
                } else {
                    showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
                }
                $('#submitButton1').prop('disabled', false);
            } finally {
                $('#submitButton1').html(`
                    <i class="fa-solid fa-plus"></i> Tambah
                `);
                $('#transaksiForm1 select').prop('disabled', false);
            }
        });

        $('#transaksiForm2').submit(async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            console.log("Form Data:", $(this).serialize());

            // Clear previous validation states
            $('#transaksiForm2 .is-invalid').removeClass('is-invalid');
            $('#transaksiForm2 .invalid-feedback').text('').hide();
            $('#submitButton2').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Tambah
            `);

            // Disable form inputs
            $('#transaksiForm2 select').prop('disabled', true);

            try {
                const response = await axios.post(`<?= base_url('transaksi/createexternal') ?>`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    $('#transaksiForm2')[0].reset();
                    $('#id_resep').val(null).trigger('change');
                    $('#transaksiForm2 .is-invalid').removeClass('is-invalid');
                    $('#transaksiForm2 .invalid-feedback').text('').hide();
                    $('#submitButton2').prop('disabled', true);
                    fetchPasienOptions2();
                    fetchTransaksi();
                } else {
                    console.log("Validation Errors:", response.data.errors);

                    // Clear previous validation states
                    $('#transaksiForm2 .is-invalid').removeClass('is-invalid');
                    $('#transaksiForm2 .invalid-feedback').text('').hide();

                    // Display new validation errors
                    for (const field in response.data.errors) {
                        if (response.data.errors.hasOwnProperty(field)) {
                            const fieldElement = $('#' + field);
                            const feedbackElement = fieldElement.siblings('.invalid-feedback');

                            console.log("Target Field:", fieldElement);
                            console.log("Target Feedback:", feedbackElement);

                            if (fieldElement.length > 0 && feedbackElement.length > 0) {
                                fieldElement.addClass('is-invalid');
                                feedbackElement.text(response.data.errors[field]).show();

                                // Remove error message when the user corrects the input
                                fieldElement.on('input change', function() {
                                    $(this).removeClass('is-invalid');
                                    $(this).siblings('.invalid-feedback').text('').hide();
                                });
                            } else {
                                console.warn("Elemen tidak ditemukan pada field:", field);
                            }
                        }
                    }
                    console.error('Perbaiki kesalahan pada formulir.');
                }
            } catch (error) {
                if (error.response.request.status === 404) {
                    showFailedToast(error.response.data.message);
                } else {
                    showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
                }
                $('#submitButton2').prop('disabled', false);
            } finally {
                $('#submitButton2').html(`
                    <i class="fa-solid fa-plus"></i> Tambah
                `);
                $('#transaksiForm2 select').prop('disabled', false);
            }
        });
        $('#refreshButton').on('click', function() {
            $('#transaksiContainer').empty();
            for (let i = 0; i < limit; i++) {
                $('#transaksiContainer').append(placeholder);
            }
            fetchPasienOptions1();
            fetchPasienOptions2();
            fetchTransaksi(); // Refresh articles on button click
        });

        fetchTransaksi();
        fetchPasienOptions1();
        fetchPasienOptions2();
        toggleSubmitButton1();
        toggleSubmitButton2();
    });
    // Show toast notification
    <?= $this->include('toast/index') ?>
</script>
<?= $this->endSection(); ?>