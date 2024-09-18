<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('css'); ?>
<?= $this->include('select2/normal'); ?>
<style>
    .list-group-container {
        height: calc(100vh - 300px);
        min-height: 100px;
    }

    @media (max-width: 767.98px) {
        .list-group-container {
            height: calc(100vh - 349px);
            min-height: 100px;
        }
    }

    @media (min-width: 991.98px) {
        .list-group-container {
            height: calc(100vh - 262px);
            min-height: 100px;
        }
    }
</style>
<?= $this->endSection(); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?></span>
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
            <input type="search" id="searchInput" class="form-control rounded-start-3" placeholder="Cari pasien, dokter, dan tanggal resep...">
            <button class="btn btn-success btn-sm bg-gradient rounded-end-3" type="button" id="refreshButton"><i class="fa-solid fa-sync"></i></button>
        </div>
    </div>
    <fieldset class="border rounded-3 px-2 py-0 mb-3">
        <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Tambah Pasien</legend>
        <form id="resepForm" enctype="multipart/form-data" class="d-flex flex-row mb-2 gap-2">
            <div class="flex-fill">
                <select class="form-select rounded-3" id="id_pasien" name="id_pasien" aria-label="id_pasien">
                    <option value="" disabled selected>-- Pilih Pasien --</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="w-auto" id="submitButtonContainer" style="display: none;">
                <button type="submit" id="submitButton" class="btn btn-primary bg-gradient rounded-3">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </form>
    </fieldset>
    <div class="list-group-container overflow-auto">
        <ul id="resepContainer" class="list-group shadow-sm rounded-3 mt-1">
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
                                    <span class="placeholder" style="width: 12.5%;"></span>
                                </small>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-grid gap-2 d-flex justify-content-end">
                        <a class="btn btn-info bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                        <a class="btn btn-secondary bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                        <a class="btn btn-danger bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    </div>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <nav id="paginationNav" class="d-flex justify-content-center justify-content-lg-end mt-3 overflow-auto w-100">
        <ul class="pagination pagination-sm" style="--bs-pagination-border-radius: var(--bs-border-radius-lg);"></ul>
    </nav>
    <div class="modal modal-sheet p-4 py-md-5 fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-body rounded-4 shadow-lg transparent-blur">
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
    let pembelianObatId = null;
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
                                <span class="placeholder" style="width: 12.5%;"></span>
                            </small>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <a class="btn btn-info bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    <a class="btn btn-secondary bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    <a class="btn btn-danger bg-gradient rounded-3 disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                </div>
            </li>
    `;
    async function fetchPasienOptions() {
        try {
            const response = await axios.get('<?= base_url('resep/pasienlist') ?>');

            if (response.data.success) {
                const options = response.data.data;
                const select = $('#id_pasien');

                // Clear existing options except the first one
                select.find('option:not(:first)').remove();

                // Loop through the options and append them to the select element
                options.forEach(option => {
                    select.append(`<option value="${option.value}">${option.text}</option>`);
                });
            }
        } catch (error) {
            showFailedToast('Gagal mendapatkan pasien.<br>' + error);
        }
    }
    async function fetchResep() {
        const search = $('#searchInput').val();
        const offset = (currentPage - 1) * limit;
        const status = $('#statusFilter').val();

        // Show the spinner
        $('#loadingSpinner').show();

        try {
            const response = await axios.get('<?= base_url('resep/listresep') ?>', {
                params: {
                    search: search,
                    limit: limit,
                    offset: offset,
                    status: status
                }
            });

            const data = response.data;
            $('#resepContainer').empty();

            if (data.total === 0) {
                $('#paginationNav ul').empty();
                $('#resepContainer').append(
                    '<li class="list-group-item bg-body-tertiary pb-3 pt-3">' +
                    '    <h1 class="display-4 text-center text-muted" style="font-weight: 100;">Data Kosong</h1>' +
                    '</li>'
                );
            } else {
                data.resep.forEach(function(resep) {
                    const jumlah_resep = parseInt(resep.jumlah_resep);
                    const total_biaya = parseInt(resep.total_biaya);
                    const statusBadge = resep.status == '1' ?
                        `<span class="badge bg-success bg-gradient">Transaksi Diproses</span>` :
                        `<span class="badge bg-danger bg-gradient">Transaksi Belum Diproses</span>`;
                    const statusButtons = resep.status == '1' ?
                        `disabled` :
                        ``;
                    const resepElement = `
            <li class="list-group-item bg-body-tertiary pb-3 pt-3">
                <div class="d-flex">
                    <div class="align-self-center ps-2 w-100">
                        <h5 class="card-title">
                            ${resep.pasien_nama_pasien}
                        </h5>
                        <h6 class="card-subtitle mb-2">
                            ${resep.user_fullname} (@${resep.user_username})
                        </h6>
                        <p class="card-text">
                            <small class="date">
                                ID Resep: ${resep.id_resep}<br>
                                Tanggal dan Waktu Resep: ${resep.tanggal_resep}<br>
                                Total Resep: ${jumlah_resep.toLocaleString('id-ID')}<br>
                                Total Harga: Rp${total_biaya.toLocaleString('id-ID')}<br>
                                ${statusBadge}
                            </small>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-info btn-sm bg-gradient rounded-3" onclick="window.location.href = '<?= base_url('resep/detailresep') ?>/${resep.id_resep}';">
                        <i class="fa-solid fa-circle-info"></i> Detail
                    </button>
                    <button type="button" class="btn btn-danger btn-sm bg-gradient rounded-3 delete-btn" data-id="${resep.id_resep}" data-name="${resep.pasien_nama_pasien}" data-date="${resep.tanggal_resep}" ${statusButtons}>
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            </li>
                `;

                    $('#resepContainer').append(resepElement);
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
            $('#resepContainer').empty();
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
            fetchResep();
        }
    });

    $('#statusFilter').on('change', function() {
        $('#resepContainer').empty();
        for (let i = 0; i < limit; i++) {
            $('#resepContainer').append(placeholder);
        }
        fetchResep();
    });

    function toggleSubmitButton() {
        var selectedValue = $('#id_pasien').val();
        if (selectedValue === null || selectedValue === "") {
            $('#submitButtonContainer').hide();
        } else {
            $('#submitButtonContainer').show();
        }
    }
    $('#id_pasien').on('change.select2', function() {
        toggleSubmitButton();
    });

    $(document).ready(function() {
        $('#id_pasien').select2({
            dropdownParent: $('#resepForm'),
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#searchInput').on('input', function() {
            currentPage = 1;
            fetchResep();
        });

        // Store the ID of the user to be deleted
        var resepId;
        var resepName;
        var resepDate;

        // Show delete confirmation modal
        $(document).on('click', '.delete-btn', function() {
            resepId = $(this).data('id');
            resepName = $(this).data('name');
            resepDate = $(this).data('date');
            $('[data-bs-toggle="tooltip"]').tooltip('hide');
            $('#deleteMessage').html(`Hapus resep dari "` + resepName + `?`);
            $('#deleteSubmessage').html(`Tanggal Resep: ` + resepDate);
            $('#deleteModal').modal('show');
        });

        $('#confirmDeleteBtn').click(async function() {
            $('#deleteModal button').prop('disabled', true);
            $('#deleteMessage').addClass('mb-0').html('Mengapus, silakan tunggu...');
            $('#deleteSubmessage').hide();

            try {
                await axios.delete(`<?= base_url('/resep/delete') ?>/${resepId}`);
                showSuccessToast('Pembelian obat berhasil dihapus.');
                fetchResep();
            } catch (error) {
                showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
            } finally {
                $('#deleteModal').modal('hide');
                $('#deleteMessage').removeClass('mb-0');
                $('#deleteSubmessage').show();
                $('#deleteModal button').prop('disabled', false);
            }
        });

        $('#resepForm').submit(async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            console.log("Form Data:", $(this).serialize());

            // Clear previous validation states
            $('#resepForm .is-invalid').removeClass('is-invalid');
            $('#resepForm .invalid-feedback').text('').hide();
            $('#submitButton').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
            `);

            // Disable form inputs
            $('#resepForm select').prop('disabled', true);

            try {
                const response = await axios.post(`<?= base_url('resep/create') ?>`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    showSuccessToast(response.data.message, 'success');
                    $('#resepForm')[0].reset();
                    $('#id_pasien').val(null).trigger('change');
                    $('#resepForm .is-invalid').removeClass('is-invalid');
                    $('#resepForm .invalid-feedback').text('').hide();
                    $('#submitButtonContainer').hide();
                    fetchResep();
                } else {
                    console.log("Validation Errors:", response.data.errors);

                    // Clear previous validation states
                    $('#resepForm .is-invalid').removeClass('is-invalid');
                    $('#resepForm .invalid-feedback').text('').hide();

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
                showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
            } finally {
                $('#submitButton').prop('disabled', false).html(`
                    <i class="fa-solid fa-plus"></i>
                `);
                $('#resepForm select').prop('disabled', false);
            }
        });
        $('#refreshButton').on('click', function() {
            $('#resepContainer').empty();
            for (let i = 0; i < limit; i++) {
                $('#resepContainer').append(placeholder);
            }
            fetchResep(); // Refresh articles on button click
        });

        fetchResep();
        fetchPasienOptions();
        toggleSubmitButton();
    });
    // Show toast notification
    function showSuccessToast(message) {
        var toastHTML = `<div id="toast" class="toast fade show align-items-center text-bg-success border border-success rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="w-100 mx-2 text-start" id="toast-message">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
        var toastElement = $(toastHTML);
        $('#toastContainer').append(toastElement); // Make sure there's a container with id `toastContainer`
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
    }

    function showFailedToast(message) {
        var toastHTML = `<div id="toast" class="toast fade show align-items-center text-bg-danger border border-danger rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-start">
                    <div style="width: 24px; text-align: center;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="w-100 mx-2 text-start" id="toast-message">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
        var toastElement = $(toastHTML);
        $('#toastContainer').append(toastElement); // Make sure there's a container with id `toastContainer`
        var toast = new bootstrap.Toast(toastElement);
        toast.show();
    }
</script>
<?= $this->endSection(); ?>