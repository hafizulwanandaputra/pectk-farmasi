<?php

namespace App\Controllers;

use App\Models\ResepModel;
use App\Models\DetailResepModel;
use App\Models\ObatModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Resep extends BaseController
{
    protected $ResepModel;
    protected $DetailResepModel;
    public function __construct()
    {
        $this->ResepModel = new ResepModel();
        $this->DetailResepModel = new DetailResepModel();
    }

    public function index()
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Menyusun data yang akan dikirim ke tampilan
            $data = [
                'title' => 'Resep Dokter - ' . $this->systemName, // Judul halaman
                'headertitle' => 'Resep Dokter', // Judul header
                'agent' => $this->request->getUserAgent() // Mengambil user agent
            ];
            return view('dashboard/resep/index', $data); // Mengembalikan tampilan resep
        } else {
            // Menghasilkan exception jika peran tidak diizinkan
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function listresep()
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Mengambil parameter pencarian, limit, offset, dan status dari query string
            $search = $this->request->getGet('search');
            $limit = $this->request->getGet('limit');
            $offset = $this->request->getGet('offset');
            $status = $this->request->getGet('status');

            // Menentukan limit dan offset
            $limit = $limit ? intval($limit) : 0;
            $offset = $offset ? intval($offset) : 0;

            $ResepModel = $this->ResepModel;

            // Mengatur query untuk pemanggilan data berdasarkan peran
            if (session()->get('role') != 'Dokter') {
                $ResepModel->select('resep.*'); // Mengambil semua kolom dari tabel resep
            } else {
                // Hanya mengambil resep yang dibuat oleh dokter yang sedang login
                $ResepModel->select('resep.*')->where('resep.dokter', session()->get('fullname'));
            }

            // Menerapkan filter status jika disediakan
            if ($status === '1') {
                $ResepModel->where('status', 1); // Mengambil resep dengan status aktif
            } elseif ($status === '0') {
                $ResepModel->where('status', 0); // Mengambil resep dengan status non-aktif
            }

            // Menerapkan filter pencarian berdasarkan nama pasien, dokter, atau tanggal resep
            if ($search) {
                $ResepModel->groupStart()
                    ->like('nama_pasien', $search)
                    ->orLike('dokter', $search)
                    ->orLike('tanggal_resep', $search)
                    ->groupEnd();
            }

            // Menambahkan filter untuk resep di mana nomor_registrasi, no_rm, dan dokter adalah bukan NULL
            $ResepModel->groupStart()
                ->where('nomor_registrasi IS NOT NULL')
                ->where('no_rm IS NOT NULL')
                ->where('telpon IS NOT NULL')
                ->where('tempat_lahir IS NOT NULL')
                ->where('dokter IS NOT NULL')
                ->groupEnd();

            // Menghitung total hasil pencarian
            $total = $ResepModel->countAllResults(false);

            // Mendapatkan hasil yang sudah dipaginasi
            $Resep = $ResepModel->orderBy('id_resep', 'DESC')->findAll($limit, $offset);

            // Menghitung nomor urut untuk halaman saat ini
            $startNumber = $offset + 1;

            // Menambahkan nomor urut ke setiap resep
            $dataResep = array_map(function ($data, $index) use ($startNumber) {
                $data['number'] = $startNumber + $index; // Menetapkan nomor urut
                return $data; // Mengembalikan data yang telah ditambahkan nomor urut
            }, $Resep, array_keys($Resep));

            // Mengembalikan data resep dalam format JSON
            return $this->response->setJSON([
                'resep' => $dataResep,
                'total' => $total // Mengembalikan total hasil
            ]);
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function pasienlist()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {

            $client = new Client(); // Membuat klien HTTP Guzzle baru

            try {
                // Mendapatkan informasi dokter dari sesi
                $dokterLogin = session()->get('fullname'); // Atau dari model jika data lebih kompleks
                // Mengirim permintaan GET ke API
                $response = $client->request('GET', env('API-URL') . date('Y-m-d'), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-key' => env('X-KEY') // Mengatur header API
                    ],
                ]);

                // Mendekode JSON dan menangani potensi error
                $data = json_decode($response->getBody()->getContents(), true);

                // Kondisikan jika yang login itu dokter
                if (session()->get('role') == 'Dokter') {
                    // Filter data pasien berdasarkan dokter login
                    $filteredData = array_filter($data, function ($pasien) use ($dokterLogin) {
                        // Misalkan nama dokter dicocokkan dengan kolom 'dokter' dari API
                        return isset($pasien['dokter']) && $pasien['dokter'] === $dokterLogin;
                    });
                    $options = [];
                    // Menyusun opsi dari data pasien yang diterima
                    foreach ($filteredData as $row) {
                        $options[] = [
                            'value' => $row['nomor_registrasi'],
                            'text' => $row['nama_pasien'] . ' (' . $row['no_rm'] . ' - ' . $row['nomor_registrasi'] . ')' // Menyusun teks yang ditampilkan
                        ];
                    }
                } else {
                    $options = [];
                    // Menyusun opsi dari data pasien yang diterima
                    foreach ($data as $row) {
                        $options[] = [
                            'value' => $row['nomor_registrasi'],
                            'text' => $row['nama_pasien'] . ' (' . $row['no_rm'] . ' - ' . $row['nomor_registrasi'] . ')' // Menyusun teks yang ditampilkan
                        ];
                    }
                }

                // Mengembalikan data pasien dalam format JSON
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $options,
                ]);
            } catch (RequestException $e) {
                // Menangani error saat permintaan API
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => 'Gagal mengambil data pasien: ' . $e->getMessage(),
                ]);
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function resep($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Mengambil data resep berdasarkan ID
            if (session()->get('role') != 'Dokter') {
                $data = $this->ResepModel
                    ->where('nomor_registrasi IS NOT NULL')
                    ->where('no_rm IS NOT NULL')
                    ->where('telpon IS NOT NULL')
                    ->where('tempat_lahir IS NOT NULL')
                    ->where('dokter IS NOT NULL')
                    ->find($id); // Mengambil resep tanpa filter dokter
            } else {
                $data = $this->ResepModel
                    ->where('resep.dokter', session()->get('fullname')) // Mengambil resep hanya untuk dokter yang sedang login
                    ->where('nomor_registrasi IS NOT NULL')
                    ->where('no_rm IS NOT NULL')
                    ->where('telpon IS NOT NULL')
                    ->where('tempat_lahir IS NOT NULL')
                    ->where('dokter IS NOT NULL')
                    ->find($id);
            }
            return $this->response->setJSON($data); // Mengembalikan data resep dalam format JSON
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function create()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'nomor_registrasi' => 'required', // Nomor registrasi harus diisi
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                return $this->response->setJSON(['success' => false, 'message' => NULL, 'errors' => $validation->getErrors()]);
            }

            // Mengambil nomor registrasi dan tanggal dari permintaan POST
            $nomorRegistrasi = $this->request->getPost('nomor_registrasi');

            // Mengambil data dari API eksternal menggunakan Guzzle
            $client = new Client();
            try {
                // Mengirim permintaan GET ke API
                $response = $client->request('GET', env('API-URL') . date('Y-m-d'), [
                    'headers' => [
                        'x-key' => env('X-KEY'), // Mengatur header API
                    ],
                ]);

                // Mendekode JSON yang diterima dari API
                $dataFromApi = json_decode($response->getBody(), true);

                // Memeriksa apakah data mengandung nomor registrasi yang diminta
                $patientData = null;
                foreach ($dataFromApi as $patient) {
                    if ($patient['nomor_registrasi'] == $nomorRegistrasi) {
                        $patientData = $patient; // Menyimpan data pasien jika ditemukan
                        break;
                    }
                }

                // Jika data pasien tidak ditemukan
                if (!$patientData) {
                    return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Data pasien tidak ditemukan', 'errors' => NULL]);
                }

                // Menyiapkan data untuk disimpan
                $data = [
                    'nomor_registrasi' => $nomorRegistrasi,
                    'no_rm' => $patientData['no_rm'],
                    'nama_pasien' => $patientData['nama_pasien'],
                    'alamat' => $patientData['alamat'],
                    'telpon' => $patientData['telpon'],
                    'jenis_kelamin' => $patientData['jenis_kelamin'],
                    'tempat_lahir' => $patientData['tempat_lahir'],
                    'tanggal_lahir' => $patientData['tanggal_lahir'],
                    'dokter' => session()->get('fullname'), // Menyimpan nama dokter yang sedang login
                    'tanggal_resep' => date('Y-m-d H:i:s'), // Menyimpan tanggal resep saat ini
                    'jumlah_resep' => 0,
                    'total_biaya' => 0,
                    'status' => 0,
                ];

                // Menyimpan data resep ke dalam model
                $this->ResepModel->save($data);
                return $this->response->setJSON(['success' => true, 'message' => 'Resep berhasil ditambahkan']);
            } catch (\Exception $e) {
                // Menangani kesalahan saat mengambil data
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage(), 'errors' => NULL]);
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function delete($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            $db = db_connect(); // Menghubungkan ke database

            // Mengambil resep
            $resep = $this->ResepModel->find($id);

            if ($resep['status'] == 0) {
                // Mengambil semua id_obat dan jumlah dari detail_resep yang terkait dengan resep yang dihapus
                $detailResep = $db->query("SELECT id_obat, jumlah FROM detail_resep WHERE id_resep = ?", [$id])->getResultArray();

                // Mengurangi jumlah_keluar pada tabel obat
                foreach ($detailResep as $detail) {
                    $id_obat = $detail['id_obat'];
                    $jumlah = $detail['jumlah'];

                    // Mengambil jumlah_keluar dari tabel obat
                    $obat = $db->query("SELECT jumlah_keluar FROM obat WHERE id_obat = ?", [$id_obat])->getRowArray();

                    if ($obat) {
                        // Mengurangi jumlah_keluar
                        $new_jumlah_keluar = $obat['jumlah_keluar'] - $jumlah;

                        // Memastikan jumlah_keluar tidak negatif
                        if ($new_jumlah_keluar < 0) {
                            $new_jumlah_keluar = 0;
                        }

                        // Memperbarui jumlah_keluar di tabel obat
                        $db->query("UPDATE obat SET jumlah_keluar = ? WHERE id_obat = ?", [$new_jumlah_keluar, $id_obat]);
                    }
                }

                // Melanjutkan penghapusan resep
                $transaksiDetail = $db->query("SELECT id_transaksi FROM detail_transaksi WHERE id_resep = ?", [$id])->getRow();

                // Menghapus resep dan detail terkait
                $this->ResepModel->where('status', 0)->delete($id);
                $db->query('ALTER TABLE `resep` auto_increment = 1'); // Mengatur ulang auto increment pada tabel resep
                $db->query('ALTER TABLE `detail_resep` auto_increment = 1'); // Mengatur ulang auto increment pada tabel detail resep

                // Jika ada transaksi terkait, hitung ulang total_pembayaran
                if ($transaksiDetail) {
                    $id_transaksi = $transaksiDetail->id_transaksi;

                    // Hitung ulang total_pembayaran berdasarkan detail transaksi yang tersisa
                    $result = $db->query("
                SELECT SUM(harga_satuan) as total_pembayaran 
                FROM detail_transaksi 
                WHERE id_transaksi = ?", [$id_transaksi])->getRow();

                    $total_pembayaran = $result->total_pembayaran ?? 0;

                    // Memperbarui tabel transaksi dengan total_pembayaran yang baru
                    $db->query("
                UPDATE transaksi 
                SET total_pembayaran = ? 
                WHERE id_transaksi = ?", [$total_pembayaran, $id_transaksi]);
                }

                return $this->response->setJSON(['message' => 'Resep berhasil dihapus']); // Mengembalikan pesan sukses
            } else {
                return $this->response->setStatusCode(422)->setJSON(['message' => 'Resep ini tidak bisa dihapus karena sudah ditransaksikan']);
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    // DETAIL RESEP
    public function detailresep($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Jika peran bukan 'Dokter', ambil resep berdasarkan ID
            if (session()->get('role') != 'Dokter') {
                $resep = $this->ResepModel
                    ->where('nomor_registrasi IS NOT NULL')
                    ->where('no_rm IS NOT NULL')
                    ->where('telpon IS NOT NULL')
                    ->where('tempat_lahir IS NOT NULL')
                    ->where('dokter IS NOT NULL')
                    ->find($id);
            } else {
                // Jika peran 'Dokter', ambil resep yang dibuat oleh dokter yang sedang login
                $resep = $this->ResepModel
                    ->where('resep.dokter', session()->get('fullname'))
                    ->where('nomor_registrasi IS NOT NULL')
                    ->where('no_rm IS NOT NULL')
                    ->where('telpon IS NOT NULL')
                    ->where('tempat_lahir IS NOT NULL')
                    ->where('dokter IS NOT NULL')
                    ->find($id);
            }

            // Memeriksa apakah resep tidak kosong
            if (!empty($resep)) {
                // Menyiapkan data untuk tampilan
                $data = [
                    'resep' => $resep,
                    'title' => 'Detail Resep Dokter ' . $resep['nama_pasien'] . ' (' . $id . ') - ' . $this->systemName,
                    'headertitle' => 'Detail Resep Dokter',
                    'agent' => $this->request->getUserAgent() // Menyimpan informasi tentang user agent
                ];
                // Mengembalikan tampilan detail resep
                return view('dashboard/resep/details', $data);
            } else {
                // Menampilkan halaman tidak ditemukan jika resep tidak ditemukan
                throw PageNotFoundException::forPageNotFound();
            }
        } else {
            // Menampilkan halaman tidak ditemukan jika peran tidak diizinkan
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function detailreseplist($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Mengambil detail resep berdasarkan id_resep yang diberikan
            $data = $this->DetailResepModel
                ->where('detail_resep.id_resep', $id)
                ->join('resep', 'resep.id_resep = detail_resep.id_resep', 'inner') // Bergabung dengan tabel resep
                ->join('obat', 'obat.id_obat = detail_resep.id_obat', 'inner') // Bergabung dengan tabel obat
                ->orderBy('id_detail_resep', 'ASC') // Mengurutkan berdasarkan id_detail_resep
                ->findAll();

            // Mengembalikan data dalam format JSON
            return $this->response->setJSON($data);
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function detailresepitem($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin', 'Dokter', atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter' || session()->get('role') == 'Apoteker') {
            // Mengambil detail resep berdasarkan id_detail_resep yang diberikan
            $data = $this->DetailResepModel
                ->where('id_detail_resep', $id)
                ->join('obat', 'obat.id_obat = detail_resep.id_obat', 'inner') // Bergabung dengan tabel obat
                ->orderBy('id_detail_resep', 'ASC') // Mengurutkan berdasarkan id_detail_resep
                ->find($id); // Mengambil data berdasarkan id

            // Mengembalikan data dalam format JSON
            return $this->response->setJSON($data);
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function obatlist($id_resep)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            $ObatModel = new ObatModel(); // Membuat instance model Obat
            $DetailResepModel = new DetailResepModel(); // Membuat instance model DetailResep

            // Mengambil semua obat dari tabel obat dan mengurutkannya
            $results = $ObatModel->orderBy('nama_obat', 'DESC')->findAll();

            $options = []; // Menyiapkan array untuk opsi obat
            foreach ($results as $row) {
                $ppn = (int) $row['ppn']; // Mengambil nilai PPN
                $mark_up = (int) $row['mark_up']; // Mengambil nilai mark-up
                $harga_obat = (int) $row['harga_obat']; // Mengambil harga obat

                // Menghitung PPN terlebih dahulu
                $jumlah_ppn = ($harga_obat * $ppn) / 100;
                $total_harga_ppn = $harga_obat + $jumlah_ppn;

                // Setelah itu, terapkan mark-up
                $jumlah_mark_up = ($total_harga_ppn * $mark_up) / 100;
                $total_harga = $total_harga_ppn + $jumlah_mark_up;

                // Bulatkan ke ratusan terdekat ke atas
                $harga_bulat = ceil($total_harga / 100) * 100;

                // Memformat harga yang telah dibulatkan
                $harga_obat_terformat = number_format($harga_bulat, 0, ',', '.');

                // Cek apakah id_resep sudah ada di tabel detail_resep dengan id_resep yang sama
                $isUsed = $DetailResepModel->where('id_obat', $row['id_obat'])
                    ->where('id_resep', $id_resep) // Pastikan sesuai dengan id_resep yang sedang digunakan
                    ->first();

                // Jika belum ada pada pembelian yang sama, tambahkan ke options
                if (($row['jumlah_masuk'] - $row['jumlah_keluar']) > 0 && !$isUsed) {
                    $options[] = [
                        'value' => $row['id_obat'], // Menyimpan id_obat
                        'text' => $row['nama_obat'] . ' (' . $row['kategori_obat'] . ' • ' . $row['bentuk_obat'] . ' • Rp' . $harga_obat_terformat . ' • ' . ($row['jumlah_masuk'] - $row['jumlah_keluar']) . ')' // Menyimpan informasi obat
                    ];
                }
            }

            // Mengembalikan data dalam format JSON
            return $this->response->setJSON([
                'success' => true,
                'data' => $options,
            ]);
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function tambahdetailresep($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'id_obat' => 'required', // id_obat harus diisi
                'signa' => 'required', // signa harus diisi
                'catatan' => 'required', // catatan harus diisi
                'cara_pakai' => 'required', // cara pakai harus diisi
                'jumlah' => 'required|numeric|greater_than[0]', // jumlah harus diisi, numerik, dan lebih besar dari 0
            ]);

            // Memeriksa apakah validasi gagal
            if (!$this->validate($validation->getRules())) {
                return $this->response->setJSON(['success' => false, 'message' => NULL, 'errors' => $validation->getErrors()]);
            }

            // Memulai transaksi database
            $db = db_connect();
            $db->transBegin();

            // Mengambil data obat berdasarkan id_obat yang diberikan
            $builderObat = $db->table('obat');
            $obat = $builderObat->where('id_obat', $this->request->getPost('id_obat'))->get()->getRowArray();

            // Mengambil nilai PPN, mark-up, dan harga obat
            $ppn = $obat['ppn'];
            $mark_up = $obat['mark_up'];
            $harga_obat = $obat['harga_obat'];

            // Hitung PPN terlebih dahulu
            $jumlah_ppn = ($harga_obat * $ppn) / 100;
            $total_harga_ppn = $harga_obat + $jumlah_ppn;

            // Setelah itu, terapkan mark-up
            $jumlah_mark_up = ($total_harga_ppn * $mark_up) / 100;
            $total_harga = $total_harga_ppn + $jumlah_mark_up;

            // Bulatkan ke ratusan terdekat ke atas
            $harga_bulat = ceil($total_harga / 100) * 100;

            // Memformat harga yang telah dibulatkan
            $harga_obat_terformat = number_format($harga_bulat, 0, ',', '.');

            // Simpan data detail resep
            $data = [
                'id_resep' => $id,
                'id_obat' => $this->request->getPost('id_obat'),
                'signa' => $this->request->getPost('signa'),
                'catatan' => $this->request->getPost('catatan'),
                'cara_pakai' => $this->request->getPost('cara_pakai'),
                'jumlah' => $this->request->getPost('jumlah'),
                'harga_satuan' => $harga_obat_terformat,
            ];
            $this->DetailResepModel->save($data);

            // Mengambil data resep
            $resepb = $db->table('resep');
            $resepb->where('id_resep', $id);
            $resep = $resepb->get()->getRowArray();

            // Jika status resep adalah transaksi sudah diproses, gagalkan operasi
            if ($resep['status'] == 1) {
                $db->transRollback();
                return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Tidak bisa dilakukan karena transaksi yang menggunakan resep ini sudah diproses', 'errors' => NULL]);
            }

            // Mengupdate jumlah keluar obat
            $new_jumlah_keluar = $obat['jumlah_keluar'] + $this->request->getPost('jumlah');
            $builderObat->where('id_obat', $this->request->getPost('id_obat'))->update(['jumlah_keluar' => $new_jumlah_keluar]);

            // Memeriksa apakah jumlah keluar melebihi stok
            if ($new_jumlah_keluar > $obat['jumlah_masuk']) {
                $db->transRollback();
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Jumlah obat melebihi stok', 'errors' => NULL]);
            }

            // Menghitung jumlah resep
            $builder = $db->table('detail_resep');
            $builder->select('SUM(jumlah) as jumlah_resep, SUM(jumlah * harga_satuan) as total_biaya');
            $builder->where('id_resep', $id);
            $result = $builder->get()->getRow();

            $jumlah_resep = $result->jumlah_resep; // Mengambil jumlah resep
            $total_biaya = $result->total_biaya; // Mengambil total biaya

            // Memperbarui tabel resep
            $resepBuilder = $db->table('resep');
            $resepBuilder->where('id_resep', $id);
            $resepBuilder->update([
                'jumlah_resep' => $jumlah_resep,
                'total_biaya' => $total_biaya,
            ]);

            // Memeriksa status transaksi
            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memproses pemberian resep', 'errors' => NULL]);
            } else {
                $db->transCommit();
                return $this->response->setJSON(['success' => true, 'message' => 'Item resep berhasil ditambahkan']);
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function perbaruidetailresep($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'signa_edit' => 'required', // signa_edit harus diisi
                'catatan_edit' => 'required', // catatan_edit harus diisi
                'cara_pakai_edit' => 'required', // cara_pakai_edit harus diisi
                'jumlah_edit' => 'required|numeric|greater_than[0]', // jumlah_edit harus diisi, numerik, dan lebih besar dari 0
            ]);

            // Memeriksa apakah validasi gagal
            if (!$this->validate($validation->getRules())) {
                return $this->response->setJSON(['success' => false, 'message' => NULL, 'errors' => $validation->getErrors()]);
            }

            // Memulai transaksi database
            $db = db_connect();
            $db->transBegin();

            // Mengambil detail resep berdasarkan id_detail_resep yang diberikan
            $detail_resep = $this->DetailResepModel->find($this->request->getPost('id_detail_resep'));
            $builderObat = $db->table('obat');
            $obat = $builderObat->where('id_obat', $detail_resep['id_obat'])->get()->getRowArray();

            // Simpan data detail resep yang diperbarui
            $data = [
                'id_detail_resep' => $this->request->getPost('id_detail_resep'),
                'id_resep' => $id,
                'id_obat' => $detail_resep['id_obat'],
                'signa' => $this->request->getPost('signa_edit'),
                'catatan' => $this->request->getPost('catatan_edit'),
                'cara_pakai' => $this->request->getPost('cara_pakai_edit'),
                'jumlah' => $this->request->getPost('jumlah_edit'),
                'harga_satuan' => $detail_resep['harga_satuan'], // Menyimpan harga satuan yang sama
            ];
            $this->DetailResepModel->save($data);

            // Mengambil data resep
            $resepb = $db->table('resep');
            $resepb->where('id_resep', $id);
            $resep = $resepb->get()->getRowArray();

            // Jika status resep adalah transaksi sudah diproses, gagalkan operasi
            if ($resep['status'] == 1) {
                $db->transRollback();
                return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Tidak bisa dilakukan karena transaksi yang menggunakan resep ini sudah diproses', 'errors' => NULL]);
            }

            // Mengupdate jumlah keluar obat
            $new_jumlah_keluar = $obat['jumlah_keluar'] - $detail_resep['jumlah'] + $this->request->getPost('jumlah_edit');
            $builderObat->where('id_obat', $detail_resep['id_obat'])->update(['jumlah_keluar' => $new_jumlah_keluar]);

            // Memeriksa apakah jumlah keluar melebihi stok
            if ($new_jumlah_keluar > $obat['jumlah_masuk']) {
                $db->transRollback();
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Jumlah obat melebihi stok', 'errors' => NULL]);
            }

            // Menghitung jumlah resep
            $builder = $db->table('detail_resep');
            $builder->select('SUM(jumlah) as jumlah_resep, SUM(jumlah * harga_satuan) as total_biaya');
            $builder->where('id_resep', $id);
            $result = $builder->get()->getRow();

            $jumlah_resep = $result->jumlah_resep; // Mengambil jumlah resep
            $total_biaya = $result->total_biaya; // Mengambil total biaya

            // Memperbarui tabel resep
            $resepBuilder = $db->table('resep');
            $resepBuilder->where('id_resep', $id);
            $resepBuilder->update([
                'jumlah_resep' => $jumlah_resep,
                'total_biaya' => $total_biaya,
            ]);

            // Memperbarui detail_transaksi dengan harga_transaksi yang baru
            $harga_transaksi = $detail_resep['jumlah'] * $detail_resep['harga_satuan'];

            $detailTransaksiBuilder = $db->table('detail_transaksi');
            $detailTransaksiBuilder->where('id_resep', $id);
            $detailTransaksiBuilder->update([
                'harga_transaksi' => $harga_transaksi
            ]);

            // Memeriksa status transaksi
            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memproses pemberian resep', 'errors' => NULL]);
            } else {
                $db->transCommit();
                return $this->response->setJSON(['success' => true, 'message' => 'Item resep berhasil diperbarui']);
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function hapusdetailresep($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Dokter' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Dokter') {
            // Menghubungkan ke database
            $db = db_connect();

            // Mengambil detail resep berdasarkan id_detail_resep yang diberikan
            $builderDetail = $db->table('detail_resep');
            $detail = $builderDetail->where('id_detail_resep', $id)->get()->getRowArray();

            if ($detail) {
                $id_resep = $detail['id_resep']; // Mengambil id resep dari detail
                $id_obat = $detail['id_obat']; // Mengambil id obat dari detail
                $jumlah_obat = $detail['jumlah']; // Mengambil jumlah obat dari detail

                // Mengambil data resep
                $resepb = $db->table('resep');
                $resepb->where('id_resep', $id_resep);
                $resep = $resepb->get()->getRowArray();

                // Jika status resep adalah transaksi sudah diproses, gagalkan operasi
                if ($resep['status'] == 1) {
                    return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Tidak bisa dilakukan karena transaksi yang menggunakan resep ini sudah diproses', 'errors' => NULL]);
                }

                // Mengambil jumlah_keluar saat ini dari tabel obat
                $builderObat = $db->table('obat');
                $obat = $builderObat->where('id_obat', $id_obat)->get()->getRowArray();

                if ($obat) {
                    // Memperbarui jumlah_keluar di tabel obat (mengurangi stok berdasarkan detail yang dihapus)
                    $new_jumlah_keluar = $obat['jumlah_keluar'] - $jumlah_obat;
                    if ($new_jumlah_keluar < 0) {
                        $new_jumlah_keluar = 0; // Jika jumlah keluar negatif, set menjadi 0
                    }
                    $builderObat->where('id_obat', $id_obat)->update(['jumlah_keluar' => $new_jumlah_keluar]);

                    // Menghapus detail resep
                    $builderDetail->where('id_detail_resep', $id)->delete();

                    // Mengatur ulang auto_increment (opsional, tidak biasanya direkomendasikan di produksi)
                    $db->query('ALTER TABLE `detail_resep` auto_increment = 1');

                    // Menghitung jumlah_resep dan total_biaya untuk resep
                    $builder = $db->table('detail_resep');
                    $builder->select('SUM(jumlah) as jumlah_resep, SUM(jumlah * harga_satuan) as total_biaya');
                    $builder->where('id_resep', $id_resep);
                    $result = $builder->get()->getRow();

                    $jumlah_resep = $result->jumlah_resep ?? 0;  // Menangani null jika tidak ada baris yang tersisa
                    $total_biaya = $result->total_biaya ?? 0;

                    // Memperbarui tabel resep
                    $resepBuilder = $db->table('resep');
                    $resepBuilder->where('id_resep', $id_resep);
                    $resepBuilder->update([
                        'jumlah_resep' => $jumlah_resep,
                        'total_biaya' => $total_biaya,
                    ]);

                    // Menghapus catatan detail_transaksi yang terkait
                    $builderTransaksiDetail = $db->table('detail_transaksi');
                    $builderTransaksiDetail->where('id_resep', $id_resep)->delete();

                    return $this->response->setJSON(['message' => 'Item resep berhasil dihapus']);
                }
            }

            return $this->response->setJSON(['message' => 'Detail resep tidak ditemukan'], 404);
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function etiketdalam($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Apoteker') {
            // Mengambil data resep berdasarkan id dan status
            $resep = $this->ResepModel
                ->where('status', 0)
                ->find($id);
            // Mengambil detail resep yang berkaitan dengan bentuk obat Tablet/Kapsul dan Sirup
            $detail_resep = $this->DetailResepModel
                ->where('detail_resep.id_resep', $id)
                ->groupStart()
                ->where('obat.bentuk_obat', 'Tablet/Kapsul')
                ->orWhere('obat.bentuk_obat', 'Sirup')
                ->groupEnd()
                ->join('resep', 'resep.id_resep = detail_resep.id_resep', 'inner')
                ->join('obat', 'obat.id_obat = detail_resep.id_obat', 'inner')
                ->orderBy('id_detail_resep', 'ASC')
                ->findAll();

            // Memeriksa apakah detail resep tidak kosong dan status resep sama dengan 0
            if (!empty($detail_resep) && $resep['status'] == 0) {
                // Menyiapkan data untuk cetakan
                $data = [
                    'resep' => $resep,
                    'detail_resep' => $detail_resep,
                    'title' => 'Etiket Resep ' . $id . ' - ' . $this->systemName
                ];
                // Menghasilkan PDF menggunakan Dompdf
                $dompdf = new Dompdf();
                $html = view('dashboard/resep/etiket', $data);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream('resep-obat-dalam-id-' . $resep['nomor_registrasi'] . '-' . urlencode($resep['nama_pasien']) . '-' . urlencode($resep['dokter']) . '-' . $resep['tanggal_resep'] . '.pdf', [
                    'Attachment' => FALSE // Menghasilkan PDF tanpa mengunduh
                ]);
            } else {
                throw PageNotFoundException::forPageNotFound(); // Jika detail resep kosong atau status tidak sesuai
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function etiketluar($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Apoteker' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Apoteker') {
            // Mengambil data resep berdasarkan id dan status
            $resep = $this->ResepModel
                ->where('status', 0)
                ->find($id);
            // Mengambil detail resep yang berkaitan dengan bentuk obat Tetes dan Salep
            $detail_resep = $this->DetailResepModel
                ->where('detail_resep.id_resep', $id)
                ->groupStart()
                ->where('obat.bentuk_obat', 'Tetes')
                ->orWhere('obat.bentuk_obat', 'Salep')
                ->groupEnd()
                ->join('resep', 'resep.id_resep = detail_resep.id_resep', 'inner')
                ->join('obat', 'obat.id_obat = detail_resep.id_obat', 'inner')
                ->orderBy('id_detail_resep', 'ASC')
                ->findAll();

            // Memeriksa apakah detail resep tidak kosong dan status resep sama dengan 0
            if (!empty($detail_resep) && $resep['status'] == 0) {
                // Menyiapkan data untuk cetakan
                $data = [
                    'resep' => $resep,
                    'detail_resep' => $detail_resep,
                    'title' => 'Etiket Resep ' . $id . ' - ' . $this->systemName
                ];
                // Menghasilkan PDF menggunakan Dompdf
                $dompdf = new Dompdf();
                $html = view('dashboard/resep/etiket', $data);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream('resep-obat-luar-id-' . $resep['nomor_registrasi'] . '-' . urlencode($resep['nama_pasien']) . '-' . urlencode($resep['dokter']) . '-' . $resep['tanggal_resep'] . '.pdf', [
                    'Attachment' => FALSE // Menghasilkan PDF tanpa mengunduh
                ]);
            } else {
                throw PageNotFoundException::forPageNotFound(); // Jika detail resep kosong atau status tidak sesuai
            }
        } else {
            // Mengembalikan status 404 jika peran tidak diizinkan
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }
}
