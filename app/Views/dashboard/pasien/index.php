<?= $this->extend('dashboard/templates/dashboard'); ?>
<?= $this->section('title'); ?>
<div class="d-flex justify-content-start align-items-center">
    <span class="fw-medium fs-5 flex-fill text-truncate"><?= $headertitle; ?> <span id="total_rajal" class="date"></span></span>
    <div id="loadingSpinner" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div style="min-width: 1px; max-width: 1px;"></div>
<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-3 px-md-4">
    <div class="d-xxl-flex justify-content-center">
        <div class="no-fluid-content">
            <div class="sticky-top" style="z-index: 99;">
                <ul class="list-group shadow-sm rounded-top-0 rounded-bottom-3 mb-2">
                    <li class="list-group-item border-top-0 bg-body-tertiary">
                        <div class="input-group input-group-sm">
                            <input type="date" id="tanggal" name="tanggal" class="form-control rounded-start-3">
                            <button class="btn btn-danger bg-gradient" type="button" id="clearTglButton" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Bersihkan Tanggal"><i class="fa-solid fa-xmark"></i></button>
                            <button class="btn btn-success bg-gradient rounded-end-3" type="button" id="refreshButton" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Segarkan" disabled><i class="fa-solid fa-sync"></i></button>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="alert alert-info rounded-3 mb-2" role="alert">
                <div class="d-flex align-items-start">
                    <div style="width: 12px; text-align: center;">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="w-100 ms-3">
                        Data-data pasien rawat jalan ini diperoleh dari <em>Application Programming Interface</em> (API) <a href="https://pectk.padangeyecenter.com/klinik" class="alert-link" target="_blank">Sistem Informasi Manajemen Klinik Utama Mata Padang Eye Center Teluk Kuantan</a>
                    </div>
                </div>
            </div>

            <div class="accordion mb-3" id="datapasien" style="--bs-accordion-border-radius: var(--bs-border-radius-lg); --bs-accordion-inner-border-radius: calc(var(--bs-border-radius-lg) - (var(--bs-border-width)));">
                <div class="accordion-item shadow-sm p-3 p-3">
                    <h2 class="text-center text-muted mb-0" style="font-weight: 300;">Memuat data pasien rawat jalan...</h2>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>
<?= $this->section('javascript'); ?>
<script>
    // HTML untuk menunjukkan bahwa data pasien sedang dimuat
    const loading = `
        <div class="accordion-item shadow-sm p-3 p-3">
            <h2 class="text-center text-muted mb-0" style="font-weight: 300;">Memuat data pasien rawat jalan...</h2>
        </div>
    `;

    // Fungsi untuk menghitung usia dan sisa bulan berdasarkan tanggal lahir
    function hitungUsia(tanggalLahir, tanggalRegistrasi) {
        const lahir = new Date(tanggalLahir); // Mengubah tanggal lahir menjadi objek Date
        const sekarang = new Date(tanggalRegistrasi); // Mengubah tanggal registrasi menjadi objek Date

        // Menghitung usia dalam tahun
        let usia = sekarang.getFullYear() - lahir.getFullYear();

        // Menghitung selisih bulan
        let bulan = sekarang.getMonth() - lahir.getMonth();

        // Menghitung selisih hari untuk memastikan bulan tidak negatif
        const hari = sekarang.getDate() - lahir.getDate();

        // Periksa apakah bulan/hari ulang tahun belum terlewati di tahun ini
        if (bulan < 0 || (bulan === 0 && hari < 0)) {
            usia--; // Kurangi usia jika ulang tahun belum terlewati
            bulan += 12; // Tambahkan 12 bulan jika bulan menjadi negatif
        }

        // Jika hari di bulan ini belum cukup, kurangi bulan
        if (hari < 0) {
            bulan--;
        }

        // Pastikan bulan berada dalam rentang 0-11
        if (bulan < 0) {
            bulan += 12;
        }

        return {
            usia,
            bulan
        }; // Mengembalikan usia dan sisa bulan
    }

    // Fungsi untuk mengambil data pasien dari API
    async function fetchPasien() {
        $('#loadingSpinner').show(); // Menampilkan spinner loading

        try {
            // Ambil nilai tanggal dari input
            const tanggal = $('#tanggal').val();

            // Cek apakah tanggal diinput
            if (!tanggal) {
                $('#datapasien').empty(); // Kosongkan tabel pasien
                $('#refreshButton').prop('disabled', true); // Nonaktifkan tombol refresh
                const emptyRow = `
                    <div class="accordion-item shadow-sm p-3 p-3">
                        <h2 class="text-center text-muted mb-0" style="font-weight: 300;">Silakan masukkan tanggal</h2>
                    </div>
                `;
                $('#datapasien').append(emptyRow); // Menambahkan baris kosong ke tabel
                $('#total_rajal').text(''); // Kosongkan total
                return; // Keluar dari fungsi
            }

            // Mengambil data pasien dari API berdasarkan tanggal
            const response = await axios.get(`<?= base_url('pasien/pasienapi') ?>?tanggal=${tanggal}`);
            const data = response.data.data; // Mendapatkan data pasien

            $('#datapasien').empty(); // Kosongkan tabel pasien
            $('#refreshButton').prop('disabled', false); // Aktifkan tombol refresh
            $('#total_rajal').text(`(${data.length})`); // Jumlah data

            // Cek apakah data pasien kosong
            if (data.length === 0) {
                // Tampilkan pesan jika tidak ada data
                const emptyRow = `
                    <div class="accordion-item shadow-sm p-3 p-3">
                        <h2 class="text-center text-muted mb-0" style="font-weight: 300;">Tidak ada pasien yang berobat pada ${tanggal}</h2>
                    </div>
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
                let jenis_kelamin = pasien.jenis_kelamin;
                if (jenis_kelamin === 'L') {
                    jenis_kelamin = `<span class="badge text-black bg-gradient text-nowrap" style="background-color: SkyBlue"><i class="fa-solid fa-mars"></i> LAKI-LAKI</span>`;
                } else if (jenis_kelamin === 'P') {
                    jenis_kelamin = `<span class="badge text-black bg-gradient text-nowrap" style="background-color: Pink"><i class="fa-solid fa-venus"></i> PEREMPUAN</span>`;
                }
                // Gunakan pesan jika tidak ada nomor telepon
                const telpon = pasien.telpon ? pasien.telpon : "<em>Tidak ada</em>";
                const usia = hitungUsia(pasien.tanggal_lahir, pasien.tanggal_registrasi); // Menghitung usia pasien

                // Membuat elemen baris untuk setiap pasien
                const pasienElement = `
                <div class="accordion-item shadow-sm">
                    <div class="accordion-header">
                        <button class="accordion-button px-3 py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index + 1}" aria-expanded="false" aria-controls="collapse-${index + 1}">
                            <div class="pe-3">
                                <h5 class="card-title">[<span class="date" style="font-weight: 900;">${index + 1}</span>] ${pasien.nama_pasien}</h5>
                                <h6 class="card-subtitle text-muted">${pasien.dokter}</h6>
                                <p class="card-text text-muted"><small class="date">${pasien.nomor_registrasi} ${jenis_kelamin}</small></p>
                            </div>
                        </button>
                    </div>
                    <div id="collapse-${index + 1}" class="accordion-collapse collapse" data-bs-parent="#datapasien">
                        <div class="accordion-body px-3 py-2">
                            <small class="date">
                                Nomor Rekam Medis: ${pasien.no_rm}<br>
                                Tempat Lahir: ${pasien.tempat_lahir}<br>
                                Tanggal Lahir: ${pasien.tanggal_lahir}<br>
                                Usia: ${usia.usia} tahun ${usia.bulan} bulan<br>
                                Alamat: ${pasien.alamat}<br>
                                Telepon: ${telpon}
                            </small>
                        </div>
                    </div>
                </div>
                `;
                $('#datapasien').append(pasienElement); // Menambahkan elemen pasien ke tabel
            });
        } catch (error) {
            // Menangani error jika permintaan gagal
            console.error(error.response.data.error); // Menampilkan error di konsol
            const errorRow = `
                <div class="accordion-item shadow-sm p-3 p-3">
                    <h2 class="text-center text-danger mb-0" style="font-weight: 300;">${error.response.data.error}</h2>
                </div>
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
        fetchPasien(); // Memanggil fungsi untuk mengambil data pasien
    });

    $(document).ready(function() {
        // Menangani event klik pada tombol bersihkan
        $('#clearTglButton').on('click', function() {
            $('#tanggal').val(''); // Kosongkan tanggal
            $('#datapasien').empty(); // Kosongkan tabel pasien
            $('#datapasien').append(loading); // Menampilkan loading indicator
            fetchPasien(); // Memanggil fungsi untuk mengambil data pasien
        });
        // Menangani event klik pada tombol refresh
        $('#refreshButton').on('click', function() {
            fetchPasien(); // Panggil fungsi untuk mengambil data pasien
        });

        // Panggil fungsi untuk mengambil data pasien saat dokumen siap
        fetchPasien();
    });

    <?= $this->include('toast/index') ?>
</script>
<?= $this->endSection(); ?>