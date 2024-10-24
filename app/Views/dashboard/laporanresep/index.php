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
        <nav>
            <div class="nav nav-tabs justify-content-center mb-2" id="nav-tab" role="tablist">
                <button class="nav-link active" id="resepharian-container-tab" data-bs-toggle="tab" data-bs-target="#resepharian-container" type="button" role="tab" aria-controls="resepharian-container" aria-selected="true">Harian</button>
                <button class="nav-link" id="resepbulanan-container-tab" data-bs-toggle="tab" data-bs-target="#resepbulanan-container" type="button" role="tab" aria-controls="resepbulanan-container" aria-selected="false">Bulanan</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane show active" id="resepharian-container" role="tabpanel" aria-labelledby="resepharian-container-tab" tabindex="0">
                <fieldset class="border rounded-3 px-2 py-0 mb-3">
                    <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Masukkan Tanggal</legend>
                    <div class="mb-2 input-group">
                        <input type="date" id="tanggal" name="tanggal" class="form-control rounded-start-3">
                        <button class="btn btn-success bg-gradient rounded-end-3" type="button" id="refreshButton1" disabled><i class="fa-solid fa-sync"></i></button>
                    </div>
                </fieldset>
                <fieldset class="border rounded-3 px-2 py-0 mb-3" id="dokter-harian" style="display: none;">
                    <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Daftar Dokter</legend>
                    <div class="form-check">
                        <?php foreach ($daftarDokter as $dokter) : ?>
                            <label class="form-check-label">
                                <input class="form-check-input dokter-checkbox-1" type="checkbox" value="<?= $dokter['dokter'] ?>" name="dokter[]">
                                <?= $dokter['dokter']; ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="width:100%; font-size: 9pt;">
                        <thead>
                            <tr class="align-middle">
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">No</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 50%;">Dokter</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 50%;">Nama Obat</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Harga Satuan</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Obat Keluar</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody class="align-top" id="resepharian">
                            <tr>
                                <td colspan="6" class="text-center">Memuat data resep...</td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th scope="col" class="bg-body-secondary border-secondary text-end" style="border-bottom-width: 0; border-top-width: 2px;" colspan="4">Total Keseluruhan</th>
                                <th scope="col" class="bg-body-secondary border-secondary text-end date" style="border-bottom-width: 0; border-top-width: 2px;" id="total_keluar_harian"></th>
                                <th scope="col" class="bg-body-secondary border-secondary text-end date" style="border-bottom-width: 0; border-top-width: 2px;" id="total_harga_harian"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="reportBtns1" style="display: none;">
                    <hr>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                        <button class="btn btn-success rounded-3 bg-gradient" type="button" id="reportBtn1" onclick="downloadReport1()"><i class="fa-solid fa-file-excel"></i> Buat Laporan (Excel)</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="resepbulanan-container" role="tabpanel" aria-labelledby="resepbulanan-container-tab" tabindex="0">
                <fieldset class="border rounded-3 px-2 py-0 mb-3">
                    <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Masukkan Bulan</legend>
                    <div class="mb-2 input-group">
                        <input type="month" id="bulan" name="bulan" class="form-control rounded-start-3">
                        <button class="btn btn-success bg-gradient rounded-end-3" type="button" id="refreshButton2" disabled><i class="fa-solid fa-sync"></i></button>
                    </div>
                </fieldset>
                <fieldset class="border rounded-3 px-2 py-0 mb-3" id="dokter-bulanan" style="display: none;">
                    <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Daftar Dokter</legend>
                    <div class="form-check">
                        <?php foreach ($daftarDokter as $dokter) : ?>
                            <label class="form-check-label">
                                <input class="form-check-input dokter-checkbox-2" type="checkbox" value="<?= $dokter['dokter'] ?>" name="dokter[]">
                                <?= $dokter['dokter']; ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="width:100%; font-size: 9pt;">
                        <thead>
                            <tr class="align-middle">
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">No</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Tanggal</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 50%;">Dokter</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 50%;">Nama Obat</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Harga Satuan</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Obat Keluar</th>
                                <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody class="align-top" id="resepbulanan">
                            <tr>
                                <td colspan="7" class="text-center">Memuat data resep...</td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th scope="col" class="bg-body-secondary border-secondary text-end" style="border-bottom-width: 0; border-top-width: 2px;" colspan="5">Total Keseluruhan</th>
                                <th scope="col" class="bg-body-secondary border-secondary text-end date" style="border-bottom-width: 0; border-top-width: 2px;" id="total_keluar_bulanan"></th>
                                <th scope="col" class="bg-body-secondary border-secondary text-end date" style="border-bottom-width: 0; border-top-width: 2px;" id="total_harga_bulanan"></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="reportBtns2" style="display: none;">
                    <hr>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                        <button class="btn btn-success rounded-3 bg-gradient" type="button" id="reportBtn2" onclick="downloadReport2()"><i class="fa-solid fa-file-excel"></i> Buat Laporan (Excel)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>
<?= $this->section('javascript'); ?>
<script>
    // LAPORAN HARIAN
    async function downloadReport1() {
        $('#loadingSpinner').show(); // Menampilkan spinner

        // Mengambil semua checkbox yang dipilih
        const selectedDoctors = [];
        $('.dokter-checkbox-1:checked').each(function() {
            selectedDoctors.push($(this).val());
        });

        // Membangun query string
        const queryString = $.param({
            dokter: selectedDoctors
        });

        try {
            // Ambil nilai tanggal dari input
            const tanggal = $('#tanggal').val();
            // Mengambil file dari server
            const response = await axios.get(`<?= base_url('laporanresep/exportdailyexcel') ?>/${tanggal}?${queryString}`, {
                responseType: 'blob' // Mendapatkan data sebagai blob
            });

            // Mendapatkan nama file dari header Content-Disposition
            const disposition = response.headers['content-disposition'];
            const filename = disposition ? disposition.split('filename=')[1].split(';')[0].replace(/"/g, '') : '.xlsx';

            // Membuat URL unduhan
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const a = document.createElement('a');
            a.href = url;
            a.download = filename; // Menggunakan nama file dari header
            document.body.appendChild(a);
            a.click();
            a.remove();

            window.URL.revokeObjectURL(url); // Membebaskan URL yang dibuat
        } catch (error) {
            showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
        } finally {
            $('#loadingSpinner').hide(); // Menyembunyikan spinner setelah unduhan selesai
        }
    }

    // LAPORAN BULANAN
    async function downloadReport2() {
        $('#loadingSpinner').show(); // Menampilkan spinner

        // Mengambil semua checkbox yang dipilih
        const selectedDoctors = [];
        $('.dokter-checkbox-2:checked').each(function() {
            selectedDoctors.push($(this).val());
        });

        // Membangun query string
        const queryString = $.param({
            dokter: selectedDoctors
        });

        try {
            // Ambil nilai bulan dari input
            const bulan = $('#bulan').val();
            // Mengambil file dari server
            const response = await axios.get(`<?= base_url('laporanresep/exportmonthlyexcel') ?>/${bulan}?${queryString}`, {
                responseType: 'blob' // Mendapatkan data sebagai blob
            });

            // Mendapatkan nama file dari header Content-Disposition
            const disposition = response.headers['content-disposition'];
            const filename = disposition ? disposition.split('filename=')[1].split(';')[0].replace(/"/g, '') : '.xlsx';

            // Membuat URL unduhan
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const a = document.createElement('a');
            a.href = url;
            a.download = filename; // Menggunakan nama file dari header
            document.body.appendChild(a);
            a.click();
            a.remove();

            window.URL.revokeObjectURL(url); // Membebaskan URL yang dibuat
        } catch (error) {
            showFailedToast('Terjadi kesalahan. Silakan coba lagi.<br>' + error);
        } finally {
            $('#loadingSpinner').hide(); // Menyembunyikan spinner setelah unduhan selesai
        }
    }
    // HTML untuk menunjukkan bahwa data transaksi sedang dimuat
    const loading1 = `
        <tr>
            <td colspan="6" class="text-center">Memuat data resep...</td>
        </tr>
    `;
    const loading2 = `
        <tr>
            <td colspan="7" class="text-center">Memuat data resep...</td>
        </tr>
    `;

    // Fungsi untuk mengambil data resep harian dari tabel resep
    async function fetchResep1() {
        $('#loadingSpinner').show(); // Menampilkan spinner loading

        // Mengambil semua checkbox yang dipilih
        const selectedDoctors = [];
        $('.dokter-checkbox-1:checked').each(function() {
            selectedDoctors.push($(this).val());
        });

        // Membangun query string
        const queryString = $.param({
            dokter: selectedDoctors
        });

        try {
            // Ambil nilai tanggal dari input
            const tanggal = $('#tanggal').val();

            // Cek apakah tanggal diinput
            if (!tanggal) {
                $('#dokter-harian').hide(); // Sembunyikan kotak centang dokter
                $('#reportBtns1').hide(); // Sembunyikan tombol buat laporan
                $('#resepharian').empty(); // Kosongkan tabel resep
                $('#refreshButton1').prop('disabled', true); // Nonaktifkan tombol refresh
                // Tampilkan pesan jika tidak ada data
                const emptyRow = `
                    <tr>
                        <td colspan="6" class="text-center">Silakan masukkan tanggal</td>
                    </tr>
                `;
                $('#resepharian').append(emptyRow); // Menambahkan baris kosong ke tabel
                return; // Keluar dari fungsi
            }

            // Mengambil data resep dari API berdasarkan tanggal
            const response = await axios.get(`<?= base_url('laporanresep/exportdaily') ?>/${tanggal}?${queryString}`);
            const data = response.data.laporanresep; // Mendapatkan data resep
            // Mendapatkan total keseluruhan
            const total_keluar_keseluruhan = response.data.total_keluar_keseluruhan;
            const total_harga_keseluruhan = response.data.total_harga_keseluruhan;

            $('#dokter-harian').show(); // Tampilkan kotak centang dokter
            $('#reportBtns1').show(); // Tampilkan tombol buat laporan
            $('#resepharian').empty(); // Kosongkan tabel resep
            $('#refreshButton1').prop('disabled', false); // Aktifkan tombol refresh

            // Cek apakah data resep kosong
            if (data.length === 0) {
                // Tampilkan pesan jika tidak ada data
                $('#reportBtns1').hide(); // Sembunyikan tombol buat laporan
                const emptyRow = `
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada resep yang digunakan pada ${tanggal}</td>
                    </tr>
                `;
                $('#resepharian').append(emptyRow); // Menambahkan baris pesan ke tabel
            }

            // Menambahkan setiap resep ke tabel
            data.forEach(function(resep, index) {
                // Nilai NULL = Resep Luar
                const dokter = resep.dokter == null ? `Resep Luar` : resep.dokter;
                // Menjadikan angka-angka yang diperoleh sebagai integer
                const total_keluar = parseInt(resep.total_keluar);
                const harga_satuan = parseInt(resep.harga_satuan);
                const total_harga = parseInt(resep.total_harga);

                // Baris pertama untuk informasi utama resep
                const resepElement = `
                    <tr>
                        <td class="date text-nowrap text-center">${index + 1}</td>
                        <td>${dokter}</td>
                        <td>${resep.nama_obat}</td>
                        <td class="date text-end">Rp${harga_satuan.toLocaleString('id-ID')}</td>
                        <td class="date text-end">${total_keluar.toLocaleString('id-ID')}</td>
                        <td class="date text-end">Rp${total_harga.toLocaleString('id-ID')}</td>
                    </tr>
                `;

                // Menambahkan elemen resep ke tabel
                $('#resepharian').append(resepElement);
            });

            // Menambahkan total keluar keseluruhan
            if (isNaN(total_keluar_keseluruhan) || total_keluar_keseluruhan === 0) {
                $('#total_keluar_harian').text('0');
            } else {
                $('#total_keluar_harian').text(`${total_keluar_keseluruhan.toLocaleString('id-ID')}`);
            }

            // Menambahkan total harga keseluruhan
            if (isNaN(total_harga_keseluruhan) || total_harga_keseluruhan === 0) {
                $('#total_harga_harian').text('Rp0');
            } else {
                $('#total_harga_harian').text(`Rp${total_harga_keseluruhan.toLocaleString('id-ID')}`);
            }
        } catch (error) {
            // Menangani error jika permintaan gagal
            console.error(error); // Menampilkan error di konsol
            const errorRow = `
                <tr>
                    <td colspan="5" class="text-center">${error}</td>
                </tr>
            `;
            $('#resepharian').empty(); // Kosongkan tabel resep
            $('#resepharian').append(errorRow); // Menambahkan baris error ke tabel
        } finally {
            // Sembunyikan spinner loading setelah selesai
            $('#loadingSpinner').hide();
        }
    }

    // Event listener ketika tanggal diubah
    $('#tanggal').on('change', function() {
        $('#resepharian').empty(); // Kosongkan tabel resep
        $('#resepharian').append(loading1); // Menampilkan loading indicator
        fetchResep1(); // Memanggil fungsi untuk mengambil data resep
    });

    // Event listener ketika kotak centang diubah
    $('.dokter-checkbox-1').on('change', function() {
        $('#resepharian').empty(); // Kosongkan tabel resep
        $('#resepharian').append(loading1); // Menampilkan loading indicator
        fetchResep1(); // Memanggil fungsi untuk mengambil data resep
    });

    // Fungsi untuk mengambil data resep harian dari tabel resep
    async function fetchResep2() {
        $('#loadingSpinner').show(); // Menampilkan spinner loading

        // Mengambil semua checkbox yang dipilih
        const selectedDoctors = [];
        $('.dokter-checkbox-2:checked').each(function() {
            selectedDoctors.push($(this).val());
        });

        // Membangun query string
        const queryString = $.param({
            dokter: selectedDoctors
        });

        try {
            // Ambil nilai bulan dari input
            const bulan = $('#bulan').val();

            // Cek apakah bulan diinput
            if (!bulan) {
                $('#dokter-bulanan').hide(); // Sembunyikan kotak centang dokter
                $('#reportBtns2').hide(); // Sembunyikan tombol buat laporan
                $('#resepbulanan').empty(); // Kosongkan tabel resep
                $('#refreshButton2').prop('disabled', true); // Nonaktifkan tombol refresh
                // Tampilkan pesan jika tidak ada data
                const emptyRow = `
                    <tr>
                        <td colspan="7" class="text-center">Silakan masukkan bulan</td>
                    </tr>
                `;
                $('#resepbulanan').append(emptyRow); // Menambahkan baris kosong ke tabel
                return; // Keluar dari fungsi
            }

            // Mengambil data resep dari API berdasarkan bulan
            const response = await axios.get(`<?= base_url('laporanresep/exportmonthly') ?>/${bulan}?${queryString}`);
            const data = response.data.laporanresep; // Mendapatkan data resep
            // Mendapatkan total keseluruhan
            const total_keluar_keseluruhan = response.data.total_keluar_keseluruhan;
            const total_harga_keseluruhan = response.data.total_harga_keseluruhan;

            $('#dokter-bulanan').show(); // Tampilkan kotak centang dokter
            $('#reportBtns2').show(); // Tampilkan tombol buat laporan
            $('#resepbulanan').empty(); // Kosongkan tabel resep
            $('#refreshButton2').prop('disabled', false); // Aktifkan tombol refresh

            // Cek apakah data resep kosong
            if (data.length === 0) {
                // Tampilkan pesan jika tidak ada data
                $('#reportBtns2').hide(); // Sembunyikan tombol buat laporan
                const emptyRow = `
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada resep yang digunakan pada ${bulan}</td>
                    </tr>
                `;
                $('#resepbulanan').append(emptyRow); // Menambahkan baris pesan ke tabel
            }

            // Menambahkan setiap resep ke tabel
            data.forEach(function(resep, index) {
                // Nilai NULL = Resep Luar
                const dokter = resep.dokter == null ? `Resep Luar` : resep.dokter;
                // Menjadikan angka-angka yang diperoleh sebagai integer
                const total_keluar = parseInt(resep.total_keluar);
                const harga_satuan = parseInt(resep.harga_satuan);
                const total_harga = parseInt(resep.total_harga);

                // Baris pertama untuk informasi utama resep
                const resepElement = `
                    <tr>
                        <td class="date text-nowrap text-center">${index + 1}</td>
                        <td class="date text-nowrap">${resep.tanggal}</td>
                        <td>${dokter}</td>
                        <td>${resep.nama_obat}</td>
                        <td class="date text-end">Rp${harga_satuan.toLocaleString('id-ID')}</td>
                        <td class="date text-end">${total_keluar.toLocaleString('id-ID')}</td>
                        <td class="date text-end">Rp${total_harga.toLocaleString('id-ID')}</td>
                    </tr>
                `;

                // Menambahkan elemen resep ke tabel
                $('#resepbulanan').append(resepElement);
            });

            // Menambahkan total keluar keseluruhan
            if (isNaN(total_keluar_keseluruhan) || total_keluar_keseluruhan === 0) {
                $('#total_keluar_bulanan').text('0');
            } else {
                $('#total_keluar_bulanan').text(`${total_keluar_keseluruhan.toLocaleString('id-ID')}`);
            }

            // Menambahkan total harga keseluruhan
            if (isNaN(total_harga_keseluruhan) || total_harga_keseluruhan === 0) {
                $('#total_harga_bulanan').text('Rp0');
            } else {
                $('#total_harga_bulanan').text(`Rp${total_harga_keseluruhan.toLocaleString('id-ID')}`);
            }
        } catch (error) {
            // Menangani error jika permintaan gagal
            console.error(error); // Menampilkan error di konsol
            const errorRow = `
                <tr>
                    <td colspan="7" class="text-center">${error}</td>
                </tr>
            `;
            $('#resepbulanan').empty(); // Kosongkan tabel resep
            $('#resepbulanan').append(errorRow); // Menambahkan baris error ke tabel
        } finally {
            // Sembunyikan spinner loading setelah selesai
            $('#loadingSpinner').hide();
        }
    }

    // Event listener ketika bulan diubah
    $('#bulan').on('change', function() {
        $('#resepbulanan').empty(); // Kosongkan tabel resep
        $('#resepbulanan').append(loading2); // Menampilkan loading indicator
        fetchResep2(); // Memanggil fungsi untuk mengambil data resep
    });

    // Event listener ketika kotak centang diubah
    $('.dokter-checkbox-2').on('change', function() {
        $('#resepbulanan').empty(); // Kosongkan tabel resep
        $('#resepbulanan').append(loading2); // Menampilkan loading indicator
        fetchResep2(); // Memanggil fungsi untuk mengambil data resep
    });

    $(document).ready(function() {
        // Menangani event klik pada tombol refresh
        $('#refreshButton1').on('click', function() {
            $('#resepharian').empty(); // Kosongkan tabel resep
            $('#resepharian').append(loading1); // Tampilkan loading indicator
            fetchResep1(); // Panggil fungsi untuk mengambil data resep
        });
        // Menangani event klik pada tombol refresh
        $('#refreshButton2').on('click', function() {
            $('#resepbulanan').empty(); // Kosongkan tabel resep
            $('#resepbulanan').append(loading2); // Tampilkan loading indicator
            fetchResep2(); // Panggil fungsi untuk mengambil data resep
        });

        // Panggil fungsi untuk mengambil data transaksi saat dokumen siap
        fetchResep1();
        fetchResep2();
    });

    <?= $this->include('toast/index') ?>
</script>
<?= $this->endSection(); ?>