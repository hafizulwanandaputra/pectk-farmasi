<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('css'); ?>
<?= $this->include('select2/normal'); ?>
<?= $this->endSection(); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?> <span id="totalRecords" class="date"></span></span>
    <div id="loadingSpinner" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<main class="main-content-inside">
    <div class="sticky-top" style="z-index: 99;">
        <ul class="list-group shadow-sm rounded-0 mb-2">
            <li class="list-group-item border-top-0 border-end-0 border-start-0 bg-body-tertiary transparent-blur">
                <div class="no-fluid-content">
                    <div class="d-flex flex-column flex-lg-row mb-1 gap-2 mb-2">
                        <div class="input-group input-group-sm">
                            <input type="date" id="tanggalFilter" class="form-control ">
                            <button class="btn btn-danger btn-sm bg-gradient " type="button" id="clearTglButton" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Bersihkan Tanggal"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="input-group input-group-sm flex-fill">
                            <input type="search" id="searchInput" class="form-control " placeholder="Cari merek dan nama supplier">
                            <button class="btn btn-success btn-sm bg-gradient " type="button" id="refreshButton" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Segarkan"><i class="fa-solid fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="accordion" id="accordionFilter">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                                    Pencarian Tambahan
                                </button>
                            </h2>
                            <div id="collapseFilter" class="accordion-collapse collapse" data-bs-parent="#accordionFilter">
                                <div class="accordion-body px-2 py-1">
                                    <div class="d-flex flex-column flex-lg-row mb-1 gap-1 my-1">
                                        <select id="statusFilter" class="form-select form-select-sm w-auto  flex-fill">
                                            <option value="">Semua Status Terima</option>
                                            <option value="1">Diterima</option>
                                            <option value="0">Belum Diterima</option>
                                        </select>
                                    </div>
                                    <select id="apotekerFilter" class="form-select form-select-sm  my-1">
                                        <option value="">Semua Apoteker</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="px-3">
        <div class="no-fluid-content">
            <div class="shadow-sm rounded">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-sm bg-gradient  rounded-bottom-0" type="button" data-bs-toggle="collapse" data-bs-target="#pembelianObatFormContainter" aria-expanded="false" aria-controls="pembelianObatFormContainter">
                        <i class="fa-solid fa-plus"></i> Tambah Pembelian Obat
                    </button>
                </div>
                <ul id="pembelianObatFormContainter" class="list-group rounded-0 collapse">
                    <li class="list-group-item border-top-0 bg-body-tertiary">
                        <form id="pembelianObatForm" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                            <div class="flex-fill">
                                <select class="form-select " id="id_supplier" name="id_supplier" aria-label="id_supplier">
                                    <option value="" disabled selected>-- Pilih Supplier --</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="submitButtonContainer">
                                <button type="submit" id="submitButton" class="btn btn-primary bg-gradient " disabled>
                                    <i class="fa-solid fa-plus"></i> Tambah
                                </button>
                            </div>
                        </form>
                    </li>
                </ul>
                <ul id="pembelianObatContainer" class="list-group rounded-top-0 ">
                    <?php for ($i = 0; $i < 12; $i++) : ?>
                        <li class="list-group-item border-top-0 bg-body-tertiary pb-3 pt-3" style="cursor: wait;">
                            <div class="d-flex">
                                <div class="align-self-center w-100">
                                    <h5 class="card-title placeholder-glow">
                                        <span class="placeholder w-100"></span>
                                    </h5>
                                    <h6 class="card-subtitle mb-2 placeholder-glow">
                                        <span class="placeholder w-100" style="max-width: 200px;"></span>
                                    </h6>
                                    <hr class="mt-0">
                                    <div class="card-text">
                                        <div style="font-size: 0.75em;">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-1 row placeholder-glow">
                                                        <div class="col-5 col-lg-3 fw-medium">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                        <div class="col placeholder-glow">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-1 row placeholder-glow">
                                                        <div class="col-5 col-lg-3 fw-medium">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                        <div class="col placeholder-glow">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-1 row placeholder-glow">
                                                        <div class="col-5 col-lg-3 fw-medium">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                        <div class="col placeholder-glow">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-1 row placeholder-glow">
                                                        <div class="col-5 col-lg-3 fw-medium">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                        <div class="col placeholder-glow">
                                                            <span class="placeholder w-100"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="placeholder w-100" style="max-width: 100px;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-grid gap-2 d-flex justify-content-end">
                                <a class="btn btn-body bg-gradient  disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                                <a class="btn btn-danger bg-gradient  disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
            <nav id="paginationNav" class="d-flex justify-content-center justify-content-lg-end mt-3 overflow-auto w-100">
                <ul class="pagination pagination-sm"></ul>
            </nav>
        </div>
    </div>
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
<?= $this->section('javascript'); ?>
<script>
    let limit = 12;
    let currentPage = 1;
    let pembelianObatId = null;
    var placeholder = `
            <li class="list-group-item border-top-0 bg-body-tertiary pb-3 pt-3" style="cursor: wait;">
                <div class="d-flex">
                    <div class="align-self-center w-100">
                        <h5 class="card-title placeholder-glow">
                            <span class="placeholder w-100"></span>
                        </h5>
                        <h6 class="card-subtitle mb-2 placeholder-glow">
                            <span class="placeholder w-100" style="max-width: 200px;"></span>
                        </h6>
                        <hr class="mt-0">
                        <div class="card-text placeholder-glow">
                            <div style="font-size: 0.75em;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-1 row placeholder-glow">
                                            <div class="col-5 col-lg-3 fw-medium">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                            <div class="col placeholder-glow">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                        </div>
                                        <div class="mb-1 row placeholder-glow">
                                            <div class="col-5 col-lg-3 fw-medium">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                            <div class="col placeholder-glow">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-1 row placeholder-glow">
                                            <div class="col-5 col-lg-3 fw-medium">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                            <div class="col placeholder-glow">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                        </div>
                                        <div class="mb-1 row placeholder-glow">
                                            <div class="col-5 col-lg-3 fw-medium">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                            <div class="col placeholder-glow">
                                                <span class="placeholder w-100"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="placeholder w-100" style="max-width: 100px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <a class="btn btn-body bg-gradient  disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                    <a class="btn btn-danger bg-gradient  disabled placeholder" aria-disabled="true" style="width: 75px; height: 31px;"></a>
                </div>
            </li>
    `;

    async function fetchApotekerOptions(selectedApoteker = null) {
        // Show the spinner
        $('#loadingSpinner').show();
        try {
            // Panggil API dengan query string tanggal
            const response = await axios.get(`<?= base_url('pembelianobat/apotekerlist') ?>`);

            if (response.data.success) {
                const options = response.data.data;
                const select = $('#apotekerFilter');

                // Simpan nilai yang saat ini dipilih
                const currentSelection = selectedApoteker || select.val();

                // Hapus semua opsi kecuali opsi pertama (default)
                select.find('option:not(:first)').remove();

                // Urutkan opsi berdasarkan 'value' secara ascending
                options.sort((a, b) => b.value.localeCompare(a.value, 'en', {
                    numeric: true
                }));

                // Tambahkan opsi ke elemen select
                options.forEach(option => {
                    select.append(`<option value="${option.value}">${option.text}</option>`);
                });

                // Mengatur ulang pilihan sebelumnya
                if (currentSelection) {
                    select.val(currentSelection);
                }
            } else {
                showFailedToast('Gagal mendapatkan apoteker.');
            }
        } catch (error) {
            showFailedToast('Gagal mendapatkan apoteker.<br>' + error);
        } finally {
            // Hide the spinner when done
            $('#loadingSpinner').hide();
        }
    }

    async function fetchSupplierOptions() {
        try {
            const response = await axios.get('<?= base_url('obat/supplierlist') ?>');

            if (response.data.success) {
                const options = response.data.data;
                const select = $('#id_supplier');

                // Clear existing options except the first one
                select.find('option:not(:first)').remove();

                // Loop through the options and append them to the select element
                options.forEach(option => {
                    select.append(`<option value="${option.value}">${option.text}</option>`);
                });
            }
        } catch (error) {
            showFailedToast('Gagal mendapatkan supplier.<br>' + error);
        }
    }
    async function fetchPembelianObat() {
        const search = $('#searchInput').val();
        const offset = (currentPage - 1) * limit;
        const status = $('#statusFilter').val();
        const apoteker = $('#apotekerFilter').val();

        // Show the spinner
        $('#loadingSpinner').show();

        try {
            const response = await axios.get('<?= base_url('pembelianobat/pembelianobatlist') ?>', {
                params: {
                    search: search,
                    limit: limit,
                    offset: offset,
                    status: status,
                    apoteker: apoteker
                }
            });

            const data = response.data;
            $('#pembelianObatContainer').empty();
            $('#totalRecords').text(`(${data.total.toLocaleString('id-ID')})`);

            if (data.total === 0) {
                $('#paginationNav ul').empty();
                $('#pembelianObatContainer').append(
                    '<li class="list-group-item border-top-0 bg-body-tertiary pb-3 pt-3">' +
                    '    <h1 class="display-4 text-center text-muted" style="font-weight: 200;">Data Kosong</h1>' +
                    '</li>'
                );
            } else {
                data.pembelian_obat.forEach(function(pembelian_obat) {
                    const total_qty = parseInt(pembelian_obat.total_qty);
                    const total_biaya = parseInt(pembelian_obat.total_biaya);
                    const merek = pembelian_obat.supplier_merek ? `${pembelian_obat.supplier_merek}` : `<em>Tanpa merek</em>`;
                    const statusBadge = pembelian_obat.diterima == '1' ?
                        `<span class="badge bg-success bg-gradient">Diterima</span>` :
                        `<span class="badge bg-danger bg-gradient">Belum Diterima</span>`;
                    const pembelian_obatElement = `
            <li class="list-group-item border-top-0 bg-body-tertiary pb-3 pt-3">
                <div class="d-flex">
                    <div class="align-self-center w-100">
                        <h5 class="card-title">
                            [<span class="date" style="font-weight: 900;">${pembelian_obat.number}</span>] ${merek} • ${pembelian_obat.supplier_nama_supplier}
                        </h5>
                        <h6 class="card-subtitle mb-2">
                            ${pembelian_obat.apoteker}
                        </h6>
                        <hr class="mt-0">
                        <div class="card-text">
                            <div style="font-size: 0.75em;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-1 row">
                                            <div class="col-5 col-lg-4 fw-medium">ID</div>
                                            <div class="col date">
                                                ${pembelian_obat.id_pembelian_obat}
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-5 col-lg-4 fw-medium">Tgl dan Waktu</div>
                                            <div class="col date">
                                                ${pembelian_obat.tgl_pembelian}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-1 row">
                                            <div class="col-5 col-lg-4 fw-medium">Total Item</div>
                                            <div class="col date">
                                                ${total_qty.toLocaleString('id-ID')}
                                            </div>
                                        </div>
                                        <div class="mb-1 row">
                                            <div class="col-5 col-lg-4 fw-medium">Total Harga</div>
                                            <div class="col date">
                                                Rp${total_biaya.toLocaleString('id-ID')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${statusBadge}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-body btn-sm bg-gradient " onclick="window.location.href = '<?= base_url('pembelianobat/detailpembelianobat') ?>/${pembelian_obat.id_pembelian_obat}';">
                        <i class="fa-solid fa-circle-info"></i> Detail
                    </button>
                    <button type="button" class="btn btn-danger btn-sm bg-gradient  delete-btn" data-id="${pembelian_obat.id_pembelian_obat}" data-name="${pembelian_obat.supplier_nama_supplier}" data-date="${pembelian_obat.tgl_pembelian}">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            </li>
                `;

                    $('#pembelianObatContainer').append(pembelian_obatElement);
                });

                // Pagination logic with ellipsis for more than 3 pages
                const totalPages = Math.ceil(data.total / limit);
                $('#paginationNav ul').empty();

                if (currentPage > 1) {
                    $('#paginationNav ul').append(`
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="${currentPage - 1}">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    </li>
                `);
                }

                if (totalPages > 5) {
                    $('#paginationNav ul').append(`
                    <li class="page-item ${currentPage === 1 ? 'active' : ''}">
                        <a class="page-link bg-gradient date" href="#" data-page="1">1</a>
                    </li>
                `);

                    if (currentPage > 3) {
                        $('#paginationNav ul').append('<li class="page-item disabled"><span class="page-link bg-gradient">…</span></li>');
                    }

                    for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
                        $('#paginationNav ul').append(`
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link bg-gradient date" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                    }

                    if (currentPage < totalPages - 2) {
                        $('#paginationNav ul').append('<li class="page-item disabled"><span class="page-link bg-gradient">…</span></li>');
                    }

                    $('#paginationNav ul').append(`
                    <li class="page-item ${currentPage === totalPages ? 'active' : ''}">
                        <a class="page-link bg-gradient date" href="#" data-page="${totalPages}">${totalPages}</a>
                    </li>
                `);
                } else {
                    // Show all pages if total pages are 3 or fewer
                    for (let i = 1; i <= totalPages; i++) {
                        $('#paginationNav ul').append(`
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link bg-gradient date" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                    }
                }

                if (currentPage < totalPages) {
                    $('#paginationNav ul').append(`
                    <li class="page-item">
                        <a class="page-link bg-gradient date" href="#" data-page="${currentPage + 1}">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </li>
                `);
                }
            }
        } catch (error) {
            showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
            $('#pembelianObatContainer').empty();
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
            fetchPembelianObat();
        }
    });

    $('#statusFilter, #apotekerFilter, #tanggalFilter').on('change', function() {
        $('#pembelianObatContainer').empty();
        for (let i = 0; i < limit; i++) {
            $('#pembelianObatContainer').append(placeholder);
        }
        fetchPembelianObat();
    });

    $('#clearTglButton').on('click', function() {
        $('#tanggalFilter').val('');
        $('#pembelianObatContainer').empty();
        for (let i = 0; i < limit; i++) {
            $('#pembelianObatContainer').append(placeholder);
        }
        fetchPembelianObat();
    });

    function toggleSubmitButton() {
        var selectedValue = $('#id_supplier').val();
        if (selectedValue === null || selectedValue === "") {
            $('#submitButton').prop('disabled', true);
        } else {
            $('#submitButton').prop('disabled', false);
        }
    }
    $('#id_supplier').on('change.select2', function() {
        toggleSubmitButton();
    });
    $(document).ready(async function() {
        $('#id_supplier').select2({
            dropdownParent: $('#pembelianObatForm'),
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#searchInput').on('input', function() {
            currentPage = 1;
            fetchPembelianObat();
        });

        // Store the ID of the user to be deleted
        var pembelianObatId;
        var pembelianObatName;
        var pembelianObatDate;

        // Show delete confirmation modal
        $(document).on('click', '.delete-btn', function() {
            pembelianObatId = $(this).data('id');
            pembelianObatName = $(this).data('name');
            pembelianObatDate = $(this).data('date');
            $('[data-bs-toggle="tooltip"]').tooltip('hide');
            $('#deleteMessage').html(`Hapus pembelian dari "` + pembelianObatName + `"?`);
            $('#deleteSubmessage').html(`Tanggal Pembelian: ` + pembelianObatDate);
            $('#deleteModal').modal('show');
        });

        $('#confirmDeleteBtn').click(async function() {
            $('#deleteModal button').prop('disabled', true);
            $('#deleteMessage').addClass('mb-0').html('Mengapus, silakan tunggu...');
            $('#deleteSubmessage').hide();

            try {
                const response = await axios.delete(`<?= base_url('/pembelianobat/delete') ?>/${pembelianObatId}`);
                // Simpan nilai pilihan apoteker saat ini
                const selectedApoteker = $('#apotekerFilter').val();
                // Panggil fungsi untuk memperbarui opsi apoteker
                await fetchApotekerOptions(selectedApoteker);
                fetchPembelianObat();
                fetchSupplierOptions();
            } catch (error) {
                if (error.response.request.status === 422) {
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

        $('#pembelianObatForm').submit(async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            console.log("Form Data:", $(this).serialize());

            // Clear previous validation states
            $('#pembelianObatForm .is-invalid').removeClass('is-invalid');
            $('#pembelianObatForm .invalid-feedback').text('').hide();
            $('#submitButton').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Tambah
            `);

            // Disable form inputs
            $('#pembelianObatForm select').prop('disabled', true);

            try {
                const response = await axios.post(`<?= base_url('/pembelianobat/create') ?>`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    $('#id_supplier').val(null).trigger('change');
                    $('#pembelianObatForm .is-invalid').removeClass('is-invalid');
                    $('#pembelianObatForm .invalid-feedback').text('').hide();
                    $('#submitButton').prop('disabled', true);
                    // Simpan nilai pilihan apoteker saat ini
                    const selectedApoteker = $('#apotekerFilter').val();
                    // Panggil fungsi untuk memperbarui opsi apoteker
                    await fetchApotekerOptions(selectedApoteker);
                    fetchPembelianObat();
                    fetchSupplierOptions();
                } else {
                    console.log("Validation Errors:", response.data.errors);

                    // Clear previous validation states
                    $('#pembelianObatForm .is-invalid').removeClass('is-invalid');
                    $('#pembelianObatForm .invalid-feedback').text('').hide();

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
                $('#submitButton').prop('disabled', false)
            } finally {
                $('#submitButton').html(`
                    <i class="fa-solid fa-plus"></i> Tambah
                `);
                $('#pembelianObatForm select').prop('disabled', false);
            }
        });
        $('#refreshButton').on('click', async function() {
            // Simpan nilai pilihan apoteker saat ini
            const selectedApoteker = $('#apotekerFilter').val();
            // Panggil fungsi untuk memperbarui opsi apoteker
            await fetchApotekerOptions(selectedApoteker);
            fetchPembelianObat();
            fetchSupplierOptions();
        });

        await fetchApotekerOptions();
        fetchPembelianObat();
        fetchSupplierOptions();
        toggleSubmitButton();
    });
    // Show toast notification
    <?= $this->include('toast/index') ?>
</script>
<?= $this->endSection(); ?>