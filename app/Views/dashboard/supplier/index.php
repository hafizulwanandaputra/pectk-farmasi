<?= $this->extend('dashboard/templates/dashboard'); ?>
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
    <div class="mb-2">
        <table id="tabel" class="table table-sm table-hover" style="width:100%; font-size: 9pt;">
            <thead>
                <tr class="align-middle">
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">No</th>
                    <th scope="col" class="bg-body-secondary border-secondary text-nowrap" style="border-bottom-width: 2px;">Tindakan</th>
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">Nama</th>
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">Merek</th>
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">Alamat</th>
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">Nomor Telepon</th>
                    <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px;">Jumlah Obat</th>
                </tr>
            </thead>
            <tbody class="align-top">
            </tbody>
        </table>
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
    <div class="modal fade" id="supplierModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable rounded-3">
            <form id="supplierForm" enctype="multipart/form-data" class="modal-content bg-body-tertiary shadow-lg transparent-blur">
                <div class="modal-header justify-content-between pt-2 pb-2" style="border-bottom: 1px solid var(--bs-border-color-translucent);">
                    <h6 class="pe-2 modal-title fs-6 text-truncate" id="supplierModalLabel" style="font-weight: bold;"></h6>
                    <button id="closeBtn" type="button" class="btn btn-danger btn-sm bg-gradient ps-0 pe-0 pt-0 pb-0 rounded-3" data-bs-dismiss="modal" aria-label="Close"><span data-feather="x" class="mb-0" style="width: 30px; height: 30px;"></span></button>
                </div>
                <div class="modal-body py-2">
                    <input type="hidden" id="id_supplier" name="id_supplier">
                    <div class="form-floating mb-1 mt-1">
                        <input type="text" class="form-control" autocomplete="off" dir="auto" placeholder="nama_supplier" id="nama_supplier" name="nama_supplier">
                        <label for="nama_supplier">Nama*</label>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-floating mb-1 mt-1">
                        <input type="text" class="form-control" autocomplete="off" dir="auto" placeholder="merek" id="merek" name="merek">
                        <label for="merek">Merek*</label>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-floating mb-1 mt-1">
                        <input type="text" class="form-control" autocomplete="off" dir="auto" placeholder="alamat_supplier" id="alamat_supplier" name="alamat_supplier">
                        <label for="alamat_pasien">Alamat*</label>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-floating mb-1 mt-1">
                        <input type="number" class="form-control" autocomplete="off" dir="auto" placeholder="kontak_supplier" id="kontak_supplier" name="kontak_supplier">
                        <label for="kontak_supplier">Nomor Telepon</label>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end pt-2 pb-2" style="border-top: 1px solid var(--bs-border-color-translucent);">
                    <button type="submit" id="submitButton" class="btn btn-primary bg-gradient rounded-3">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </form>
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
    $(document).ready(function() {
        var table = $('#tabel').DataTable({
            "oLanguage": {
                "sDecimal": ",",
                "sEmptyTable": 'Tidak ada supplier. Klik "Tambah Supplier" untuk menambahkan supplier.',
                "sInfo": "Menampilkan _START_ hingga _END_ dari _TOTAL_ supplier",
                "sInfoEmpty": "Menampilkan 0 hingga 0 dari 0 supplier",
                "sInfoFiltered": "(di-filter dari _MAX_ supplier)",
                "sInfoPostFix": "",
                "sThousands": ".",
                "sLengthMenu": "Tampilkan _MENU_ supplier",
                "sLoadingRecords": "Memuat...",
                "sProcessing": "",
                "sSearch": "Cari:",
                "sZeroRecords": "Supplier yang Anda cari tidak ditemukan",
                "oAria": {
                    "sOrderable": "Urutkan menurut kolom ini",
                    "sOrderableReverse": "Urutkan terbalik kolom ini"
                },
                "oPaginate": {
                    "sFirst": '<i class="fa-solid fa-angles-left"></i>',
                    "sLast": '<i class="fa-solid fa-angles-right"></i>',
                    "sPrevious": '<i class="fa-solid fa-angle-left"></i>',
                    "sNext": '<i class="fa-solid fa-angle-right"></i>'
                }
            },
            'dom': "<'d-lg-flex justify-content-lg-between align-items-lg-center mb-0'<'text-md-center text-lg-start'i><'d-md-flex justify-content-md-center d-lg-block'f>>" +
                "<'d-lg-flex justify-content-lg-between align-items-lg-center'<'text-md-center text-lg-start mt-2'l><'mt-2 mb-2 mb-lg-0'B>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'d-lg-flex justify-content-lg-between align-items-lg-center'<'text-md-center text-lg-start'><'d-md-flex justify-content-md-center d-lg-block'p>>",
            'initComplete': function(settings, json) {
                $("#tabel").wrap("<div class='overflow-auto position-relative datatables-height'></div>");
                $('.dataTables_filter input[type="search"]').css({
                    'width': '220px'
                });
                $('.dataTables_info').css({
                    'padding-top': '0',
                    'font-variant-numeric': 'tabular-nums'
                });
            },
            "drawCallback": function() {
                $(".pagination").wrap("<div class='overflow-auto'></div>");
                $(".pagination").addClass("pagination-sm");
                $('.pagination-sm').css({
                    '--bs-pagination-border-radius': 'var(--bs-border-radius-lg)'
                });
                $(".page-item .page-link").addClass("bg-gradient");
                $(".form-control").addClass("rounded-3");
                $(".form-select").addClass("rounded-3");
            },
            'buttons': [{
                // Tombol Refresh
                action: function(e, dt, node, config) {
                    dt.ajax.reload(null, false);
                },
                text: '<i class="fa-solid fa-arrows-rotate"></i> Refresh',
                className: 'btn-success btn-sm bg-gradient rounded-start-3',
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            }, {
                // Tombol Tambah Supplier
                text: '<i class="fa-solid fa-plus"></i> Tambah Supplier',
                className: 'btn-primary btn-sm bg-gradient rounded-end-3',
                attr: {
                    id: 'addSupplierBtn'
                },
                init: function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                },
            }],
            "search": {
                "caseInsensitive": true
            },
            'pageLength': 25,
            'lengthMenu': [
                [25, 50, 100, 250, 500],
                [25, 50, 100, 250, 500]
            ],
            "autoWidth": true,
            "processing": false,
            "serverSide": true,
            "ajax": {
                // URL endpoint untuk melakukan permintaan AJAX
                "url": "<?= base_url('/supplier/supplierlist') ?>",
                "type": "POST", // Metode HTTP yang digunakan untuk permintaan (POST)
                "data": function(d) {
                    // Menambahkan parameter tambahan pada data yang dikirim
                    d.search = {
                        "value": $('.dataTables_filter input[type="search"]').val() // Mengambil nilai input pencarian
                    };
                },
                beforeSend: function() {
                    // Menampilkan spinner loading sebelum permintaan dikirim
                    $('#loadingSpinner').show(); // Menampilkan elemen spinner loading
                },
                complete: function() {
                    // Menyembunyikan spinner loading setelah permintaan selesai
                    $('#loadingSpinner').hide(); // Menyembunyikan elemen spinner loading
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Menyembunyikan spinner loading jika terjadi kesalahan
                    $('#loadingSpinner').hide(); // Menyembunyikan elemen spinner loading
                    // Menampilkan toast error Bootstrap ketika permintaan AJAX gagal
                    showFailedToast('Gagal memuat data. Silakan coba lagi.'); // Menampilkan pesan kesalahan
                }
            },
            columns: [{
                    data: 'no',
                    render: function(data, type, row) {
                        return `<span class="date" style="display: block; text-align: center;">${data}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<div class="btn-group" role="group">
                                    <button class="btn btn-outline-body text-nowrap bg-gradient rounded-start-3 edit-btn" style="--bs-btn-padding-y: 0.15rem; --bs-btn-padding-x: 0.5rem; --bs-btn-font-size: 9pt;" data-id="${row.id_supplier}" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button class="btn btn-outline-danger text-nowrap bg-gradient rounded-end-3 delete-btn" style="--bs-btn-padding-y: 0.15rem; --bs-btn-padding-x: 0.5rem; --bs-btn-font-size: 9pt;" data-id="${row.id_supplier}" data-name="${row.nama_supplier}" data-bs-toggle="tooltip" data-bs-title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </div>`;
                    }
                },
                {
                    data: 'nama_supplier',
                    render: function(data, type, row) {
                        return `<span class="text-nowrap">${data}</span>`;
                    }
                },
                {
                    data: 'merek',
                    render: function(data, type, row) {
                        return `<span class="text-nowrap">${data}</span>`;
                    }
                },
                {
                    data: 'alamat_supplier',
                    render: function(data, type, row) {
                        return `<span class="text-nowrap">${data}</span>`;
                    }
                },
                {
                    data: 'kontak_supplier',
                    render: function(data, type, row) {
                        return `<span class="date text-nowrap">${data}</span>`;
                    }
                },
                {
                    data: 'jumlah_obat',
                    render: function(data, type, row) {
                        return `<div class="date text-nowrap" style="display: block; text-align: right;">${data}</div>`;
                    }
                },
            ],
            "order": [
                [2, 'asc']
            ],
            "columnDefs": [{
                "target": [1],
                "orderable": false
            }, {
                "target": [0, 1, 2, 5, 6],
                "width": "0%"
            }, {
                "target": [3, 4],
                "width": "50%"
            }]
        });

        // Menginisialisasi tooltip untuk elemen dengan atribut data-bs-toggle="tooltip"
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Memperbarui tooltip setiap kali tabel digambar ulang
        table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Tampilkan modal tambah supplier
        $('#addSupplierBtn').click(function() {
            $('#supplierModalLabel').text('Tambah Supplier');
            $('#supplierModal').modal('show');
        });

        // Fokuskan input nama supplier saat modal ditampilkan
        $('#supplierModal').on('shown.bs.modal', function() {
            $('#nama_supplier').trigger('focus');
        });

        // Fungsi untuk menangani tombol edit supplier
        $(document).on('click', '.edit-btn', async function() {
            const $this = $(this);
            const id = $(this).data('id'); // Ambil ID supplier
            $('[data-bs-toggle="tooltip"]').tooltip('hide'); // Sembunyikan tooltip
            $this.prop('disabled', true).html(`<span class="spinner-border" style="width: 11px; height: 11px;" aria-hidden="true"></span>`);

            try {
                const response = await axios.get(`<?= base_url('/supplier/supplier') ?>/${id}`);
                $('#supplierModalLabel').text('Edit Supplier');
                $('#id_supplier').val(response.data.id_supplier);
                $('#nama_supplier').val(response.data.nama_supplier);
                $('#merek').val(response.data.merek);
                $('#alamat_supplier').val(response.data.alamat_supplier);
                $('#kontak_supplier').val(response.data.kontak_supplier);
                $('#supplierModal').modal('show');
            } catch (error) {
                showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
            } finally {
                $this.prop('disabled', false).html(`<i class="fa-solid fa-pen-to-square"></i>`);
            }
        });

        // Variabel untuk menyimpan ID dan nama supplier yang akan dihapus
        var supplierId;
        var supplierName;

        // Tampilkan modal konfirmasi hapus supplier
        $(document).on('click', '.delete-btn', function() {
            supplierId = $(this).data('id');
            supplierName = $(this).data('name');
            $('[data-bs-toggle="tooltip"]').tooltip('hide');
            $('#deleteMessage').html(`Hapus "` + supplierName + `"?`);
            $('#deleteSubmessage').html(`Supplier tidak dapat dihapus jika ada obat yang berasal dari supplier ini!`);
            $('#deleteModal').modal('show');
        });

        // Fungsi untuk mengonfirmasi penghapusan supplier
        $('#confirmDeleteBtn').click(async function() {
            $('#deleteModal button').prop('disabled', true);
            $('#deleteMessage').addClass('mb-0').html('Menghapus, silakan tunggu...');
            $('#deleteSubmessage').hide();

            try {
                await axios.delete(`<?= base_url('/supplier/delete') ?>/${supplierId}`);
                table.ajax.reload(null, false); // Refresh data setelah penghapusan
            } catch (error) {
                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.<br>' + error;
                if (error.response && error.response.data && error.response.data.error) {
                    errorMessage = 'Tidak dapat menghapus data ini karena sedang digunakan.';
                }
                showFailedToast(errorMessage);
            } finally {
                $('#deleteModal').modal('hide');
                $('#deleteMessage').removeClass('mb-0');
                $('#deleteSubmessage').show();
                $('#deleteModal button').prop('disabled', false);
            }
        });

        // Fungsi untuk menangani submit form supplier
        $('#supplierForm').submit(async function(e) {
            e.preventDefault();

            const url = $('#id_supplier').val() ? '<?= base_url('/supplier/update') ?>' : '<?= base_url('/supplier/create') ?>';
            const formData = new FormData(this);
            console.log("Form URL:", url);
            console.log("Form Data:", $(this).serialize());

            // Hapus status validasi sebelumnya
            $('#supplierForm .is-invalid').removeClass('is-invalid');
            $('#supplierForm .invalid-feedback').text('').hide();
            $('#submitButton').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span role="status">Memproses, silakan tunggu...</span>
            `);

            // Nonaktifkan input form sementara
            $('#supplierForm input, #supplierForm select, #closeBtn').prop('disabled', true);

            try {
                const response = await axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    $('#supplierModal').modal('hide');
                    table.ajax.reload(null, false); // Refresh data setelah operasi berhasil
                } else {
                    console.log("Validation Errors:", response.data.errors);

                    // Tampilkan pesan kesalahan validasi
                    for (const field in response.data.errors) {
                        if (response.data.errors.hasOwnProperty(field)) {
                            const fieldElement = $('#' + field);
                            const feedbackElement = fieldElement.siblings('.invalid-feedback');

                            if (fieldElement.length > 0 && feedbackElement.length > 0) {
                                fieldElement.addClass('is-invalid');
                                feedbackElement.text(response.data.errors[field]).show();

                                // Hapus pesan kesalahan saat input berubah
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
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                `);
                $('#supplierForm input, #supplierForm select, #closeBtn').prop('disabled', false);
            }
        });

        // Reset form dan status validasi saat modal ditutup
        $('#supplierModal').on('hidden.bs.modal', function() {
            $('#supplierForm')[0].reset();
            $('#id_supplier').val('');
            $('#nama_supplier').val('');
            $('#merek').val('');
            $('#alamat_supplier').val('');
            $('#kontak_supplier').val('');
            $('#supplierForm .is-invalid').removeClass('is-invalid');
            $('#supplierForm .invalid-feedback').text('').hide();
        });

        <?= $this->include('toast/index') ?>
    });
</script>
<?= $this->endSection(); ?>