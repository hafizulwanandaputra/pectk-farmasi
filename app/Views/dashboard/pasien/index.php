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
        <fieldset class="border rounded-3 px-2 py-0 mb-3" id="tambahPasienForm">
            <legend class="float-none w-auto mb-0 px-1 fs-6 fw-bold">Masukkan Tanggal</legend>
            <div class="mb-2 input-group">
                <input type="date" id="tanggal" name="tanggal" class="form-control rounded-start-3">
                <button class="btn btn-success bg-gradient rounded-end-3" type="button" id="refreshButton" disabled><i class="fa-solid fa-sync"></i></button>
            </div>
        </fieldset>
        <div id="infoCard" class="row row-cols-1 row-cols-sm-2 g-2 mb-2" style="display: none;">
            <div class="col">
                <div class="card bg-body-tertiary w-100 rounded-3">
                    <div class="card-header w-100 text-truncate">Tanggal </div>
                    <div class="card-body placeholder-glow">
                        <h5 class="display-6 fw-medium date mb-0" id="tanggal2"><span class="placeholder w-100"></span></h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-body-tertiary w-100 rounded-3">
                    <div class="card-header w-100 text-truncate">Jumlah Pasien yang Berobat</div>
                    <div class="card-body placeholder-glow">
                        <h5 class="display-6 fw-medium date mb-0" id="lengthpasien"><span class="placeholder w-100"></span></h5>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-sm" style="width:100%; font-size: 9pt;">
                <thead>
                    <tr class="align-middle">
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">No</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 25%; min-width: 128px;">Nama</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Jenis Kelamin</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Nomor Rekam Medis</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Nomor Registrasi</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 25%; min-width: 128px;">Tempat dan Tanggal Lahir</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 0%;">Nomor Telepon</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 25%; min-width: 128px;">Alamat</th>
                        <th scope="col" class="bg-body-secondary border-secondary" style="border-bottom-width: 2px; width: 15%; min-width: 128px;">Dokter</th>
                    </tr>
                </thead>
                <tbody class="align-top" id="datapasien">
                    <tr>
                        <td colspan="9" class="text-center">Memuat data pasien rawat jalan...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>
<?= $this->section('javascript'); ?>
<script>
    // HTML untuk menunjukkan bahwa data pasien sedang dimuat
    const loading = `
    <tr>
        <td colspan="9" class="text-center">Memuat data pasien...</td>
    </tr>
`;

    // Fungsi untuk menghitung usia berdasarkan tanggal lahir
    function hitungUsia(tanggalLahir) {
        const lahir = new Date(tanggalLahir); // Mengubah tanggal lahir menjadi objek Date
        const sekarang = new Date(); // Mendapatkan tanggal sekarang
        let usia = sekarang.getFullYear() - lahir.getFullYear(); // Menghitung usia berdasarkan tahun

        // Menghitung selisih bulan dan hari
        const bulan = sekarang.getMonth() - lahir.getMonth();
        const hari = sekarang.getDate() - lahir.getDate();

        // Periksa apakah bulan/hari ulang tahun belum terlewati di tahun ini
        if (bulan < 0 || (bulan === 0 && hari < 0)) {
            usia--; // Kurangi usia jika ulang tahun belum terlewati
        }
        return usia; // Mengembalikan usia
    }

    // Fungsi untuk mengambil data pasien dari API
    async function fetchPasien() {
        $('#loadingSpinner').show(); // Menampilkan spinner loading

        try {
            // Ambil nilai tanggal dari input
            const tanggal = $('#tanggal').val();

            // Cek apakah tanggal diinput
            if (!tanggal) {
                $('#infoCard').hide(); // Sembunyikan infoCard
                $('#datapasien').empty(); // Kosongkan tabel pasien
                $('#refreshButton').prop('disabled', true); // Nonaktifkan tombol refresh
                $('#tanggal2').text(``); // Kosongkan tanggal tanggal
                $('#lengthpasien').text(``); // Kosongkan panjang pasien
                // Tampilkan pesan jika tidak ada data
                const emptyRow = `
                    <tr>
                        <td colspan="9" class="text-center"><strong>Silakan masukkan tanggal</strong><br>Data-data pasien rawat jalan ini diperoleh dari Sistem Informasi Manajemen Klinik Utama Mata Padang Eye Center Teluk Kuantan melalui  <em>Application Programming Interface</em> (API)</td>
                    </tr>
                `;
                $('#datapasien').append(emptyRow); // Menambahkan baris kosong ke tabel
                return; // Keluar dari fungsi
            }

            $('#infoCard').show();

            // Mengambil data pasien dari API berdasarkan tanggal
            const response = await axios.get(`<?= base_url('pasien/pasienapi') ?>?tanggal=${tanggal}`);
            const data = response.data.data; // Mendapatkan data pasien

            $('#datapasien').empty(); // Kosongkan tabel pasien
            $('#refreshButton').prop('disabled', false); // Aktifkan tombol refresh
            $('#tanggal2').text(tanggal); // Set text tanggal
            $('#lengthpasien').text(data.length); // Set panjang pasien

            // Cek apakah data pasien kosong
            if (data.length === 0) {
                // Tampilkan pesan jika tidak ada data
                const emptyRow = `
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada pasien yang berobat pada ${tanggal}</td>
                    </tr>
                `;
                $('#datapasien').append(emptyRow); // Menambahkan baris pesan ke tabel
            }

            // Mengurutkan data pasien berdasarkan nomor registrasi
            data.sort((a, b) => a.nomor_registrasi.localeCompare(b.nomor_registrasi, 'en', {
                numeric: true
            }));

            // Menambahkan setiap pasien ke tabel
            data.forEach(function(pasien, index) {
                // Mengkondisikan jenis kelamin
                const jenis_kelamin = pasien.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan";
                // Gunakan pesan jika tidak ada nomor telepon
                const telpon = pasien.telpon ? pasien.telpon : "<em>Tidak ada</em>";
                const usia = hitungUsia(pasien.tanggal_lahir); // Menghitung usia pasien

                // Membuat elemen baris untuk setiap pasien
                const pasienElement = `
                    <tr>
                        <td class="date text-nowrap text-center">${index + 1}</td>
                        <td>${pasien.nama_pasien}</td>
                        <td class="text-nowrap">${jenis_kelamin}</td>
                        <td class="date text-nowrap">${pasien.no_rm}</td>
                        <td class="date text-nowrap">${pasien.nomor_registrasi}</td>
                        <td>${pasien.tempat_lahir}<br><small class="date text-nowrap">${pasien.tanggal_lahir} • ${usia} tahun</small></td>
                        <td class="date text-nowrap">${telpon}</td>
                        <td>${pasien.alamat}</td>
                        <td>${pasien.dokter}</td>
                    </tr>
                `;
                $('#datapasien').append(pasienElement); // Menambahkan elemen pasien ke tabel
            });
        } catch (error) {
            // Menangani error jika permintaan gagal
            console.error(error.response.data.error); // Menampilkan error di konsol
            $('#tanggal2').html(`<i class="fa-solid fa-xmark"></i> Error`); // Menampilkan error pada text tanggal
            $('#lengthpasien').html(`<i class="fa-solid fa-xmark"></i> Error`); // Menampilkan error pada panjang pasien
            const errorRow = `
                <tr>
                    <td colspan="9" class="text-center">${error.response.data.error}</td>
                </tr>
            `;
            $('#datapasien').empty(); // Kosongkan tabel pasien
            $('#datapasien').append(errorRow); // Menambahkan baris error ke tabel
        } finally {
            // Sembunyikan spinner loading setelah selesai
            $('#loadingSpinner').hide();
        }
    }

    // Event listener ketika tanggal diubah
    $('#tanggal').on('change', function() {
        $('#datapasien').empty(); // Kosongkan tabel pasien
        $('#datapasien').append(loading); // Menampilkan loading indicator
        $('#tanggal2').html(`<span class="placeholder w-100"></span>`); // Menampilkan placeholder pada text tanggal
        $('#lengthpasien').html(`<span class="placeholder w-100"></span>`); // Menampilkan placeholder pada panjang pasien
        fetchPasien(); // Memanggil fungsi untuk mengambil data pasien
    });

    $(document).ready(function() {
        // Menangani event klik pada tombol refresh
        $('#refreshButton').on('click', function() {
            $('#datapasien').empty(); // Kosongkan tabel pasien
            $('#datapasien').append(loading); // Tampilkan loading indicator
            $('#tanggal2').html(`<span class="placeholder w-100"></span>`); // Tampilkan placeholder pada text tanggal
            $('#lengthpasien').html(`<span class="placeholder w-100"></span>`); // Tampilkan placeholder pada panjang pasien
            fetchPasien(); // Panggil fungsi untuk mengambil data pasien
        });

        // Panggil fungsi untuk mengambil data pasien saat dokumen siap
        fetchPasien();
    });

    function showSuccessToast(message) {
        var toastHTML = `<div id="toast" class="toast fade align-items-center text-bg-success border border-success rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
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
        var toastHTML = `<div id="toast" class="toast fade align-items-center text-bg-danger border border-danger rounded-3 transparent-blur" role="alert" aria-live="assertive" aria-atomic="true">
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