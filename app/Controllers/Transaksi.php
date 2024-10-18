<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;
use App\Models\LayananModel;
use App\Models\ResepModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Transaksi extends BaseController
{
    protected $TransaksiModel;
    protected $DetailTransaksiModel;
    public function __construct()
    {
        $this->TransaksiModel = new TransaksiModel();
        $this->DetailTransaksiModel = new DetailTransaksiModel();
    }

    public function index()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Menyiapkan data untuk tampilan halaman kasir
            $data = [
                'title' => 'Kasir - ' . $this->systemName, // Judul halaman
                'headertitle' => 'Kasir', // Judul header
                'agent' => $this->request->getUserAgent() // Mengambil informasi user agent
            ];
            return view('dashboard/transaksi/index', $data); // Mengembalikan tampilan halaman kasir
        } else {
            throw PageNotFoundException::forPageNotFound(); // Menampilkan halaman tidak ditemukan jika peran tidak valid
        }
    }

    public function listtransaksi()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil parameter dari permintaan GET
            $search = $this->request->getGet('search'); // Nilai pencarian
            $limit = $this->request->getGet('limit'); // Batas jumlah hasil
            $offset = $this->request->getGet('offset'); // Offset untuk pagination
            $status = $this->request->getGet('status'); // Status transaksi

            // Mengubah limit dan offset menjadi integer, jika tidak ada, set ke 0
            $limit = $limit ? intval($limit) : 0;
            $offset = $offset ? intval($offset) : 0;

            // Mengambil model transaksi
            $TransaksiModel = $this->TransaksiModel;

            // Memilih semua kolom dari tabel transaksi
            $TransaksiModel->select('transaksi.*');

            // Menerapkan filter status jika ada
            if ($status === '1') {
                $TransaksiModel->where('lunas', 1); // Status lunas
            } elseif ($status === '0') {
                $TransaksiModel->where('lunas', 0); // Status belum lunas
            }

            // Menerapkan filter pencarian berdasarkan nama pasien, kasir, atau tanggal transaksi
            if ($search) {
                $TransaksiModel
                    ->groupStart()
                    ->like('nama_pasien', $search) // Pencarian berdasarkan nama pasien
                    ->orLike('kasir', $search) // Pencarian berdasarkan nama kasir
                    ->orLike('tgl_transaksi', $search) // Pencarian berdasarkan tanggal transaksi
                    ->groupEnd();
            }

            // Menghitung total hasil tanpa filter
            $total = $TransaksiModel->countAllResults(false);

            // Mengambil hasil transaksi dengan pagination
            $Transaksi = $TransaksiModel
                ->orderBy('id_transaksi', 'DESC') // Mengurutkan berdasarkan id_transaksi secara menurun
                ->findAll($limit, $offset); // Mengambil data dengan batas dan offset

            // Menghitung nomor awal untuk halaman saat ini
            $startNumber = $offset + 1;

            // Mengolah setiap transaksi dan menghitung total_pembayaran
            $dataTransaksi = array_map(function ($data, $index) use ($startNumber) {
                $data['number'] = $startNumber + $index; // Menambahkan nomor urut
                $db = db_connect(); // Menghubungkan ke database

                // Menghitung total pembayaran dari detail_transaksi
                $builder = $db->table('detail_transaksi');
                $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
                $builder->where('id_transaksi', $data['id_transaksi']);
                $result = $builder->get()->getRow(); // Mengambil hasil dari query

                $total_pembayaran = $result->total_pembayaran; // Mengambil total pembayaran

                // Memperbarui tabel transaksi dengan total_pembayaran
                $transaksiBuilder = $db->table('transaksi');
                $transaksiBuilder->where('id_transaksi', $data['id_transaksi']);
                $transaksiBuilder->update([
                    'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran
                ]);
                return $data; // Mengembalikan data transaksi yang telah diproses
            }, $Transaksi, array_keys($Transaksi));

            // Mengembalikan respon JSON dengan data transaksi dan total hasil
            return $this->response->setJSON([
                'transaksi' => $dataTransaksi, // Data transaksi
                'total' => $total // Total hasil
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan', // Pesan jika peran tidak valid
            ]);
        }
    }

    public function pasienlist()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            $client = new Client(); // Membuat klien HTTP Guzzle baru

            try {
                // Mengirim permintaan GET ke API
                $response = $client->request('GET', env('API-URL') . date('Y-m-d'), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-key' => env('X-KEY') // Mengatur header API
                    ],
                ]);

                // Mendekode JSON dan menangani potensi error
                $data = json_decode($response->getBody()->getContents(), true);

                $options = [];
                // Menyusun opsi dari data pasien yang diterima
                foreach ($data as $row) {
                    $options[] = [
                        'value' => $row['nomor_registrasi'],
                        'text' => $row['nama_pasien'] . ' (' . $row['no_rm'] . ' - ' . $row['nomor_registrasi'] . ')' // Menyusun teks yang ditampilkan
                    ];
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
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan', // Pesan jika peran tidak valid
            ]);
        }
    }

    public function transaksi($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil data transaksi berdasarkan id
            $data = $this->TransaksiModel->find($id);
            return $this->response->setJSON($data); // Mengembalikan data dalam format JSON
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan', // Pesan jika peran tidak valid
            ]);
        }
    }

    public function create()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Melakukan validasi
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'nomor_registrasi' => 'required', // Nomor registrasi wajib diisi
            ]);

            // Memeriksa apakah validasi berhasil
            if (!$this->validate($validation->getRules())) {
                return $this->response->setJSON(['success' => false, 'message' => NULL, 'errors' => $validation->getErrors()]); // Mengembalikan kesalahan validasi
            }

            // Mengambil nomor registrasi dari permintaan POST
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
                $resepData = null;
                foreach ($dataFromApi as $patient) {
                    if ($patient['nomor_registrasi'] == $nomorRegistrasi) {
                        $resepData = $patient; // Menyimpan data pasien jika ditemukan
                        break;
                    }
                }

                // Jika data pasien tidak ditemukan
                if (!$resepData) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Data pasien tidak ditemukan', 'errors' => NULL]);
                }

                // Mendapatkan tanggal saat ini
                $date = new \DateTime();
                $tanggal = $date->format('d'); // Hari (2 digit)
                $bulan = $date->format('m'); // Bulan (2 digit)
                $tahun = $date->format('y'); // Tahun (2 digit)

                // Mengambil nomor registrasi terakhir untuk di-increment
                $lastNoReg = $this->TransaksiModel->getLastNoReg($tahun, $bulan, $tanggal);
                $lastNumber = $lastNoReg ? intval(substr($lastNoReg, -4)) : 0; // Mendapatkan nomor terakhir
                $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Menyiapkan nomor berikutnya

                // Memformat nomor kwitansi
                $no_kwitansi = sprintf('TRJ%s%s%s-%s', $tanggal, $bulan, $tahun, $nextNumber);

                // Menyimpan data transaksi
                $data = [
                    'nomor_registrasi' => $nomorRegistrasi, // Nomor registrasi
                    'no_rm' => $resepData['no_rm'], // Nomor rekam medis
                    'nama_pasien' => $resepData['nama_pasien'], // Nama pasien
                    'alamat' => $resepData['alamat'], // Alamat pasien
                    'telpon' => $resepData['telpon'], // Nomor telepon pasien
                    'jenis_kelamin' => $resepData['jenis_kelamin'], // Jenis kelamin pasien
                    'tempat_lahir' => $resepData['tempat_lahir'], // Tempat lahir pasien
                    'tanggal_lahir' => $resepData['tanggal_lahir'], // Tanggal lahir pasien
                    'kasir' => session()->get('fullname'), // Nama kasir dari session
                    'no_kwitansi' => $no_kwitansi, // Nomor kwitansi
                    'tgl_transaksi' => date('Y-m-d H:i:s'), // Tanggal dan waktu transaksi
                    'total_pembayaran' => 0, // Total pembayaran awal
                    'metode_pembayaran' => '', // Metode pembayaran (kosong pada awalnya)
                    'lunas' => 0, // Status lunas (0 berarti belum lunas)
                ];
                $this->TransaksiModel->save($data); // Menyimpan data transaksi ke database
                return $this->response->setJSON(['success' => true, 'message' => 'Transaksi berhasil ditambahkan']); // Mengembalikan respon sukses
            } catch (\Exception $e) {
                // Menangani kesalahan saat mengambil data
                return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage(), 'errors' => NULL]);
            }
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan', // Pesan jika peran tidak valid
            ]);
        }
    }

    public function delete($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            $db = db_connect(); // Menghubungkan ke database

            // Mencari semua `id_resep` yang terkait dengan transaksi yang akan dihapus
            $query = $db->query('SELECT DISTINCT id_resep FROM detail_transaksi WHERE id_transaksi = ?', [$id]);
            $results = $query->getResult(); // Mengambil hasil query

            if (!empty($results)) {
                // Loop melalui setiap `id_resep` terkait dan memperbarui statusnya menjadi 0
                foreach ($results as $row) {
                    $db->query('UPDATE resep SET status = 0 WHERE id_resep = ?', [$row->id_resep]);
                }
            }

            // Menghapus transaksi
            $this->TransaksiModel->delete($id);

            // Reset auto increment untuk tabel transaksi dan detail_transaksi
            $db->query('ALTER TABLE `transaksi` auto_increment = 1');
            $db->query('ALTER TABLE `detail_transaksi` auto_increment = 1');

            return $this->response->setJSON(['message' => 'Transaksi berhasil dihapus']); // Mengembalikan respon sukses
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan', // Pesan jika peran tidak valid
            ]);
        }
    }

    // DETAIL TRANSAKSI
    public function detailtransaksi($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil data transaksi berdasarkan ID
            $transaksi = $this->TransaksiModel->find($id);
            $LayananModel = new LayananModel();
            // Mengambil jenis layanan yang dikelompokkan
            $layanan = $LayananModel->select('jenis_layanan')->groupBy('jenis_layanan')->findAll();

            // Memeriksa apakah transaksi ditemukan
            if (!empty($transaksi)) {
                // Menyiapkan data untuk tampilan
                $data = [
                    'transaksi' => $transaksi,
                    'layanan' => $layanan,
                    'title' => 'Detail Transaksi ' . $transaksi['no_kwitansi'] . ' - ' . $this->systemName,
                    'headertitle' => 'Detail Transaksi',
                    'agent' => $this->request->getUserAgent()
                ];
                // Mengembalikan tampilan detail transaksi
                return view('dashboard/transaksi/details', $data);
            } else {
                // Jika transaksi tidak ditemukan, lempar pengecualian
                throw PageNotFoundException::forPageNotFound();
            }
        } else {
            // Jika peran tidak valid, lempar pengecualian
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function detaillayananlist($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil daftar layanan berdasarkan ID transaksi
            $layanan = $this->DetailTransaksiModel
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Tindakan')
                ->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi', 'inner')
                ->join('layanan', 'layanan.id_layanan = detail_transaksi.id_layanan', 'inner')
                ->orderBy('id_detail_transaksi', 'ASC')
                ->findAll();

            // Array untuk menyimpan hasil terstruktur
            $result = [];

            // Memetakan setiap transaksi
            foreach ($layanan as $row) {
                // Jika transaksi ini belum ada dalam array $result, tambahkan
                if (!isset($result[$row['id_detail_transaksi']])) {
                    $result[$row['id_detail_transaksi']] = [
                        'id_detail_transaksi' => $row['id_detail_transaksi'],
                        'id_layanan' => $row['id_layanan'],
                        'id_transaksi' => $row['id_transaksi'],
                        'qty_transaksi' => $row['qty_transaksi'],
                        'harga_transaksi' => $row['harga_transaksi'],
                        'diskon' => $row['diskon'],
                        'lunas' => $row['lunas'],
                        'layanan' => [
                            'id_layanan' => $row['id_layanan'],
                            'nama_layanan' => $row['nama_layanan'],
                            'jenis_layanan' => $row['jenis_layanan'],
                            'tarif' => $row['tarif'],
                            'keterangan' => $row['keterangan'],
                        ],
                    ];
                }
            }

            // Mengembalikan hasil dalam bentuk JSON
            return $this->response->setJSON(array_values($result));
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function detailobatalkeslist($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil daftar obat dan alkes berdasarkan ID transaksi
            $obatalkes = $this->DetailTransaksiModel
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Obat dan Alkes')
                ->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi', 'inner')
                ->join('resep', 'resep.id_resep = detail_transaksi.id_resep', 'inner')
                ->join('detail_resep', 'resep.id_resep = detail_resep.id_resep', 'inner')
                ->join('obat', 'detail_resep.id_obat = obat.id_obat', 'inner')
                ->orderBy('id_detail_transaksi', 'ASC')
                ->findAll();

            // Array untuk menyimpan hasil terstruktur
            $result = [];

            // Memetakan setiap transaksi
            foreach ($obatalkes as $row) {
                $ppn = $row['ppn'];
                $mark_up = $row['mark_up'];
                $harga_obat = $row['harga_obat'];

                // Hitung PPN terlebih dahulu
                $jumlah_ppn = ($harga_obat * $ppn) / 100;
                $total_harga_ppn = $harga_obat + $jumlah_ppn;

                // Setelah itu, terapkan mark-up
                $jumlah_mark_up = ($total_harga_ppn * $mark_up) / 100;
                $total_harga = $total_harga_ppn + $jumlah_mark_up;

                // Jika transaksi ini belum ada dalam array $result, tambahkan
                if (!isset($result[$row['id_detail_transaksi']])) {
                    $result[$row['id_detail_transaksi']] = [
                        'id_detail_transaksi' => $row['id_detail_transaksi'],
                        'id_resep' => $row['id_resep'],
                        'id_transaksi' => $row['id_transaksi'],
                        'qty_transaksi' => $row['qty_transaksi'],
                        'harga_transaksi' => $row['harga_transaksi'],
                        'diskon' => $row['diskon'],
                        'lunas' => $row['lunas'],
                        'resep' => [
                            'id_resep' => $row['id_resep'],
                            'dokter' => $row['dokter'],
                            'tanggal_resep' => $row['tanggal_resep'],
                            'jumlah_resep' => $row['jumlah_resep'],
                            'total_biaya' => $row['total_biaya'],
                            'status' => $row['status'],
                            'detail_resep' => []
                        ],
                    ];
                }

                // Tambahkan detail_resep ke transaksi
                $result[$row['id_detail_transaksi']]['resep']['detail_resep'][] = [
                    'id_detail_resep' => $row['id_detail_resep'],
                    'id_resep' => $row['id_resep'],
                    'id_obat' => $row['id_obat'],
                    'jumlah' => $row['jumlah'],
                    'harga_satuan' => $row['harga_satuan'],
                    'obat' => [
                        [
                            'id_obat' => $row['id_obat'],
                            'id_supplier' => $row['id_supplier'],
                            'nama_obat' => $row['nama_obat'],
                            'kategori_obat' => $row['kategori_obat'],
                            'bentuk_obat' => $row['bentuk_obat'],
                            'harga_obat' => $row['harga_obat'],
                            'harga_jual' => $total_harga,
                            'signa' => $row['signa'],
                            'catatan' => $row['catatan'],
                            'cara_pakai' => $row['cara_pakai'],
                            'jumlah_masuk' => $row['jumlah_masuk'],
                            'jumlah_keluar' => $row['jumlah_keluar'],
                            'updated_at' => $row['updated_at']
                        ]
                    ],
                ];
            }

            // Mengembalikan hasil dalam bentuk JSON
            return $this->response->setJSON(array_values($result));
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function detailtransaksiitem($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil data detail transaksi berdasarkan ID
            $data = $this->DetailTransaksiModel
                ->where('id_detail_transaksi', $id)
                ->orderBy('id_detail_transaksi', 'ASC')
                ->find($id);

            // Mengembalikan data dalam bentuk JSON
            return $this->response->setJSON($data);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function layananlist($id_transaksi, $jenis_layanan = null)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            $LayananModel = new LayananModel();
            $DetailTransaksiModel = new DetailTransaksiModel();

            // Filter layanan berdasarkan jenis_layanan jika parameter diberikan
            if ($jenis_layanan) {
                $LayananModel->where('jenis_layanan', $jenis_layanan);
            }

            // Mengambil semua layanan yang telah difilter
            $results = $LayananModel
                ->orderBy('layanan.id_layanan', 'ASC')
                ->findAll();

            $options = [];
            // Memetakan hasil layanan ke dalam format yang diinginkan
            foreach ($results as $row) {
                $tarif = (int) $row['tarif']; // Mengonversi tarif ke integer
                $tarif_terformat = number_format($tarif, 0, ',', '.'); // Memformat tarif

                // Memeriksa apakah layanan sudah digunakan dalam transaksi
                $isUsed = $DetailTransaksiModel->where('id_layanan', $row['id_layanan'])
                    ->where('id_transaksi', $id_transaksi)
                    ->first();

                // Jika layanan belum digunakan, tambahkan ke opsi
                if (!$isUsed) {
                    $options[] = [
                        'value' => $row['id_layanan'], // ID layanan
                        'text' => $row['nama_layanan'] . ' (Rp' . $tarif_terformat . ')' // Nama layanan dengan tarif terformat
                    ];
                }
            }

            // Mengembalikan opsi layanan dalam bentuk JSON
            return $this->response->setJSON($options);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function reseplist($id_transaksi, $nomor_registrasi)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            $ResepModel = new ResepModel();
            $DetailTransaksiModel = new DetailTransaksiModel();

            // Mengambil resep berdasarkan nomor registrasi dengan kondisi tertentu
            $results = $ResepModel
                ->where('nomor_registrasi', $nomor_registrasi)
                ->where('status', 0) // Mengambil resep yang statusnya 0
                ->where('total_biaya >', 0) // Mengambil resep dengan total biaya lebih dari 0
                ->orderBy('resep.id_resep', 'DESC')->findAll();

            $options = [];
            // Memetakan hasil resep ke dalam format yang diinginkan
            foreach ($results as $row) {
                $total_biaya = (int) $row['total_biaya']; // Mengonversi total biaya ke integer
                $total_biaya_terformat = number_format($total_biaya, 0, ',', '.'); // Memformat total biaya

                // Memeriksa apakah resep sudah digunakan dalam transaksi
                $isUsed = $DetailTransaksiModel->where('id_resep', $row['id_resep'])
                    ->where('id_transaksi', $id_transaksi)
                    ->first();

                // Jika resep belum digunakan, tambahkan ke opsi
                if (!$isUsed) {
                    $options[] = [
                        'value' => $row['id_resep'], // ID resep
                        'text' => $row['tanggal_resep'] . ' (Rp' . $total_biaya_terformat . ')' // Tanggal resep dengan total biaya terformat
                    ];
                }
            }

            // Mengembalikan opsi resep dalam bentuk JSON
            return $this->response->setJSON([
                'success' => true,
                'data' => $options,
            ]);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function tambahlayanan($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'id_layanan' => 'required', // ID layanan harus diisi
                'qty_transaksi' => 'required|numeric|greater_than[0]', // Kuantitas harus diisi, berupa angka, dan lebih dari 0
                'diskon_layanan' => 'required|numeric|greater_than_equal_to[0]|less_than[100]', // Diskon harus diisi, berupa angka, antara 0 dan 100
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                // Mengembalikan kesalahan validasi jika tidak valid
                return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
            }

            $LayananModel = new LayananModel();
            // Mengambil data layanan berdasarkan ID yang diberikan
            $layanan = $LayananModel->find($this->request->getPost('id_layanan'));

            // Menyimpan data transaksi layanan
            $data = [
                'id_resep' => NULL,
                'id_layanan' => $this->request->getPost('id_layanan'),
                'id_transaksi' => $id,
                'jenis_transaksi' => 'Tindakan',
                'qty_transaksi' => $this->request->getPost('qty_transaksi'),
                'harga_transaksi' => $layanan['tarif'], // Menggunakan tarif dari layanan
                'diskon' => $this->request->getPost('diskon_layanan'),
            ];
            // Menyimpan data ke DetailTransaksiModel
            $this->DetailTransaksiModel->save($data);

            $db = db_connect();

            // Menghitung total pembayaran
            $builder = $db->table('detail_transaksi');
            $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
            $builder->where('id_transaksi', $id);
            $result = $builder->get()->getRow();

            $total_pembayaran = $result->total_pembayaran; // Total pembayaran yang dihitung

            // Memperbarui tabel transaksi
            $transaksiBuilder = $db->table('transaksi');
            $transaksiBuilder->where('id_transaksi', $id);
            $transaksiBuilder->update([
                'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran di tabel transaksi
            ]);

            // Mengembalikan respons sukses
            return $this->response->setJSON(['success' => true, 'message' => 'Item transaksi berhasil ditambahkan']);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function tambahobatalkes($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'id_resep' => 'required', // ID resep harus diisi
                'diskon_obatalkes' => 'required|numeric|greater_than_equal_to[0]|less_than[100]', // Diskon harus diisi, berupa angka, antara 0 dan 100
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                // Mengembalikan kesalahan validasi jika tidak valid
                return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
            }

            $ResepModel = new ResepModel();
            // Mengambil data resep berdasarkan ID yang diberikan
            $resep = $ResepModel->find($this->request->getPost('id_resep'));

            // Menyimpan data transaksi obat dan alkes
            $data = [
                'id_resep' => $this->request->getPost('id_resep'),
                'id_layanan' => NULL,
                'id_transaksi' => $id,
                'jenis_transaksi' => 'Obat dan Alkes',
                'qty_transaksi' => 1, // Kuantitas untuk obat dan alkes ditetapkan 1
                'harga_transaksi' => $resep['total_biaya'], // Menggunakan total biaya dari resep
                'diskon' => $this->request->getPost('diskon_obatalkes'),
            ];
            // Menyimpan data ke DetailTransaksiModel
            $this->DetailTransaksiModel->save($data);

            $db = db_connect();

            // Menghitung total pembayaran
            $builder = $db->table('detail_transaksi');
            $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
            $builder->where('id_transaksi', $id);
            $result = $builder->get()->getRow();

            $total_pembayaran = $result->total_pembayaran; // Total pembayaran yang dihitung

            // Memperbarui tabel transaksi
            $transaksiBuilder = $db->table('transaksi');
            $transaksiBuilder->where('id_transaksi', $id);
            $transaksiBuilder->update([
                'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran di tabel transaksi
            ]);

            // Mengembalikan respons sukses
            return $this->response->setJSON(['success' => true, 'message' => 'Item transaksi berhasil ditambahkan']);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function perbaruilayanan($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'qty_transaksi_edit' => 'required|numeric|greater_than[0]', // Kuantitas harus diisi, berupa angka, dan lebih dari 0
                'diskon_layanan_edit' => 'required|numeric|greater_than_equal_to[0]|less_than[100]', // Diskon harus diisi, berupa angka, antara 0 dan 100
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                // Mengembalikan kesalahan validasi jika tidak valid
                return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
            }

            // Mengambil detail transaksi berdasarkan ID yang diberikan
            $detail_transaksi = $this->DetailTransaksiModel->find($this->request->getPost('id_detail_transaksi'));

            // Menyimpan data yang diperbarui
            $data = [
                'id_detail_transaksi' => $this->request->getPost('id_detail_transaksi'),
                'id_resep' => NULL,
                'id_layanan' => $detail_transaksi['id_layanan'], // Menggunakan ID layanan yang ada
                'id_transaksi' => $id,
                'jenis_transaksi' => $detail_transaksi['jenis_transaksi'],
                'qty_transaksi' => $this->request->getPost('qty_transaksi_edit'), // Menggunakan kuantitas yang diperbarui
                'harga_transaksi' => $detail_transaksi['harga_transaksi'],
                'diskon' => $this->request->getPost('diskon_layanan_edit'), // Menggunakan diskon yang diperbarui
            ];
            // Menyimpan data ke DetailTransaksiModel
            $this->DetailTransaksiModel->save($data);

            $db = db_connect();

            // Menghitung total pembayaran
            $builder = $db->table('detail_transaksi');
            $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
            $builder->where('id_transaksi', $id);
            $result = $builder->get()->getRow();

            $total_pembayaran = $result->total_pembayaran; // Total pembayaran yang dihitung

            // Memperbarui tabel transaksi
            $transaksiBuilder = $db->table('transaksi');
            $transaksiBuilder->where('id_transaksi', $id);
            $transaksiBuilder->update([
                'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran di tabel transaksi
            ]);

            // Mengembalikan respons sukses
            return $this->response->setJSON(['success' => true, 'message' => 'Item transaksi berhasil diperbarui']);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function perbaruiobatalkes($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'diskon_obatalkes_edit' => 'required|numeric|greater_than_equal_to[0]|less_than[100]', // Diskon harus diisi, berupa angka, antara 0 dan 100
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                // Mengembalikan kesalahan validasi jika tidak valid
                return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
            }

            // Mengambil detail transaksi berdasarkan ID yang diberikan
            $detail_transaksi = $this->DetailTransaksiModel->find($this->request->getPost('id_detail_transaksi'));

            // Menyimpan data yang diperbarui
            $data = [
                'id_detail_transaksi' => $this->request->getPost('id_detail_transaksi'),
                'id_resep' => $detail_transaksi['id_resep'], // Menggunakan ID resep yang ada
                'id_layanan' => NULL,
                'id_transaksi' => $id,
                'jenis_transaksi' => $detail_transaksi['jenis_transaksi'],
                'qty_transaksi' => $detail_transaksi['qty_transaksi'], // Menggunakan kuantitas yang ada
                'harga_transaksi' => $detail_transaksi['harga_transaksi'],
                'diskon' => $this->request->getPost('diskon_obatalkes_edit'), // Menggunakan diskon yang diperbarui
            ];
            // Menyimpan data ke DetailTransaksiModel
            $this->DetailTransaksiModel->save($data);

            $db = db_connect();

            // Menghitung total pembayaran
            $builder = $db->table('detail_transaksi');
            $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
            $builder->where('id_transaksi', $id);
            $result = $builder->get()->getRow();

            $total_pembayaran = $result->total_pembayaran; // Total pembayaran yang dihitung

            // Memperbarui tabel transaksi
            $transaksiBuilder = $db->table('transaksi');
            $transaksiBuilder->where('id_transaksi', $id);
            $transaksiBuilder->update([
                'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran di tabel transaksi
            ]);

            // Mengembalikan respons sukses
            return $this->response->setJSON(['success' => true, 'message' => 'Item transaksi berhasil diperbarui']);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function hapusdetailtransaksi($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            $db = db_connect();

            // Mencari detail pembelian obat sebelum penghapusan untuk mendapatkan id_transaksi
            $detail = $this->DetailTransaksiModel->find($id);

            $id_transaksi = $detail['id_transaksi']; // Mengambil id_transaksi dari detail yang ditemukan

            // Menghapus detail pembelian obat
            $this->DetailTransaksiModel->delete($id);

            // Reset auto_increment (opsional, tergantung kebutuhan)
            $db->query('ALTER TABLE `detail_resep` auto_increment = 1');

            // Menghitung total pembayaran
            $builder = $db->table('detail_transaksi');
            $builder->select('SUM((harga_transaksi * qty_transaksi) * (1 - (diskon / 100))) as total_pembayaran');
            $builder->where('id_transaksi', $id_transaksi);
            $result = $builder->get()->getRow();

            $total_pembayaran = $result->total_pembayaran; // Total pembayaran yang dihitung

            // Memperbarui tabel transaksi
            $transaksiBuilder = $db->table('transaksi');
            $transaksiBuilder->where('id_transaksi', $id_transaksi);
            $transaksiBuilder->update([
                'total_pembayaran' => $total_pembayaran, // Memperbarui total pembayaran di tabel transaksi
            ]);

            // Mengembalikan respons sukses
            return $this->response->setJSON(['message' => 'Item transaksi berhasil dihapus']);
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function process($id_transaksi)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Validasi input
            $validation = \Config\Services::validation();
            // Menetapkan aturan validasi dasar
            $validation->setRules([
                'terima_uang' => 'required|numeric|greater_than[0]', // Uang yang diterima harus diisi, berupa angka, dan lebih dari 0
                'metode_pembayaran' => 'required', // Metode pembayaran harus diisi
            ]);

            // Memeriksa validasi
            if (!$this->validate($validation->getRules())) {
                // Mengembalikan kesalahan validasi jika tidak valid
                return $this->response->setJSON(['success' => false, 'message' => NULL, 'errors' => $validation->getErrors()]);
            }

            $terima_uang = $this->request->getPost('terima_uang'); // Mengambil jumlah uang yang diterima

            $db = db_connect();
            $db->transBegin();  // Memulai transaksi

            // Mengambil total pembayaran dari tabel transaksi
            $transaksi = $db->table('transaksi')
                ->select('total_pembayaran')
                ->where('id_transaksi', $id_transaksi)
                ->get()
                ->getRow();

            $total_pembayaran = $transaksi->total_pembayaran; // Total pembayaran yang diambil

            // Memeriksa apakah uang yang diterima kurang dari total pembayaran
            if ($terima_uang < $total_pembayaran) {
                return $this->response->setJSON(['success' => false, 'message' => 'Uang yang diterima kurang dari total pembayaran', 'errors' => NULL]);
            }

            // Menghitung uang kembali jika uang yang diterima lebih besar dari total pembayaran
            $uang_kembali = $terima_uang > $total_pembayaran ? $terima_uang - $total_pembayaran : 0;

            // Memperbarui transaksi
            $transaksi = $db->table('transaksi');
            $transaksi->where('id_transaksi', $id_transaksi);
            $transaksi->update([
                'terima_uang' => $terima_uang, // Memperbarui jumlah uang yang diterima
                'metode_pembayaran' => $this->request->getPost('metode_pembayaran'), // Memperbarui metode pembayaran
                'uang_kembali' => $uang_kembali, // Memperbarui uang kembali
                'lunas' => 1, // Menandai transaksi sebagai lunas
            ]);

            // Mengambil detail transaksi
            $detailtransaksi = $db->table('detail_transaksi');
            $detailtransaksi->where('id_transaksi', $id_transaksi);
            $details = $detailtransaksi->get()->getResultArray(); // Mengambil semua detail transaksi

            // Memperbarui status resep jika ada
            if ($details) {
                foreach ($details as $detail) {
                    if ($detail['id_resep'] !== null) { // Memeriksa apakah ada ID resep
                        $resep = $db->table('resep');
                        $resep->where('id_resep', $detail['id_resep']); // Memastikan mencocokkan berdasarkan id_resep
                        $resep->update([
                            'status' => 1, // Memperbarui status resep menjadi selesai
                        ]);
                    }
                }
            }

            // Memeriksa status transaksi
            if ($db->transStatus() === false) {
                $db->transRollback();  // Rollback jika ada masalah
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal memproses transaksi', 'errors' => NULL]);
            } else {
                $db->transCommit();  // Commit transaksi jika semuanya baik-baik saja
                return $this->response->setJSON(['success' => true, 'message' => 'Transaksi berhasil diproses. Silakan cetak struk transaksi.']);
            }
        } else {
            // Jika peran tidak valid, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function struk($id)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil data transaksi berdasarkan ID
            $transaksi = $this->TransaksiModel->find($id);
            // Mengambil detail layanan dari transaksi
            $layanan = $this->DetailTransaksiModel
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Tindakan') // Mengambil hanya jenis transaksi 'Tindakan'
                ->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi', 'inner')
                ->join('layanan', 'layanan.id_layanan = detail_transaksi.id_layanan', 'inner')
                ->orderBy('id_detail_transaksi', 'ASC')
                ->findAll();

            // Array untuk menyimpan hasil terstruktur layanan
            $result_layanan = [];

            // Memetakan setiap transaksi layanan
            foreach ($layanan as $row) {
                if (!isset($result_layanan[$row['id_detail_transaksi']])) {
                    // Menyimpan detail layanan ke array jika belum ada
                    $result_layanan[$row['id_detail_transaksi']] = [
                        'id_detail_transaksi' => $row['id_detail_transaksi'],
                        'id_layanan' => $row['id_layanan'],
                        'id_transaksi' => $row['id_transaksi'],
                        'qty_transaksi' => $row['qty_transaksi'],
                        'harga_transaksi' => $row['harga_transaksi'],
                        'diskon' => $row['diskon'],
                        'lunas' => $row['lunas'],
                        'layanan' => [
                            'id_layanan' => $row['id_layanan'],
                            'nama_layanan' => $row['nama_layanan'],
                            'jenis_layanan' => $row['jenis_layanan'],
                            'tarif' => $row['tarif'],
                            'keterangan' => $row['keterangan'],
                        ],
                    ];
                }
            }

            // Menghitung total harga layanan
            $total_layanan = $this->DetailTransaksiModel
                ->selectSum('((harga_transaksi * qty_transaksi) - ((harga_transaksi * qty_transaksi) * diskon / 100))', 'total_harga')
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Tindakan')
                ->get()->getRowArray();

            // Mengambil detail obat dan alat kesehatan
            $obatalkes = $this->DetailTransaksiModel
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Obat dan Alkes') // Mengambil hanya jenis transaksi 'Obat dan Alkes'
                ->join('transaksi', 'transaksi.id_transaksi = detail_transaksi.id_transaksi', 'inner')
                ->join('resep', 'resep.id_resep = detail_transaksi.id_resep', 'inner')
                ->join('detail_resep', 'resep.id_resep = detail_resep.id_resep', 'inner')
                ->join('obat', 'detail_resep.id_obat = obat.id_obat', 'inner')
                ->orderBy('id_detail_transaksi', 'ASC')
                ->findAll();

            // Array untuk menyimpan hasil terstruktur obat dan alkes
            $result_obatalkes = [];

            // Memetakan setiap transaksi obat dan alkes
            foreach ($obatalkes as $row) {
                $ppn = $row['ppn'];
                $harga_obat = $row['harga_obat'];
                $jumlah_ppn = ($harga_obat * $ppn) / 100; // Menghitung PPN
                $total_harga = $harga_obat + $jumlah_ppn; // Total harga termasuk PPN

                if (!isset($result_obatalkes[$row['id_detail_transaksi']])) {
                    // Menyimpan detail obat ke array jika belum ada
                    $result_obatalkes[$row['id_detail_transaksi']] = [
                        'id_detail_transaksi' => $row['id_detail_transaksi'],
                        'id_resep' => $row['id_resep'],
                        'id_transaksi' => $row['id_transaksi'],
                        'qty_transaksi' => $row['qty_transaksi'],
                        'harga_transaksi' => $row['harga_transaksi'],
                        'diskon' => $row['diskon'],
                        'lunas' => $row['lunas'],
                        'resep' => [
                            'id_resep' => $row['id_resep'],
                            'dokter' => $row['dokter'],
                            'tanggal_resep' => $row['tanggal_resep'],
                            'jumlah_resep' => $row['jumlah_resep'],
                            'total_biaya' => $row['total_biaya'],
                            'status' => $row['status'],
                            'detail_resep' => [] // Menyimpan detail resep
                        ],
                    ];
                }

                // Menambahkan detail_resep ke transaksi
                $result_obatalkes[$row['id_detail_transaksi']]['resep']['detail_resep'][] = [
                    'id_detail_resep' => $row['id_detail_resep'],
                    'id_resep' => $row['id_resep'],
                    'id_obat' => $row['id_obat'],
                    'jumlah' => $row['jumlah'],
                    'harga_satuan' => $row['harga_satuan'],
                    'obat' => [
                        [
                            'id_obat' => $row['id_obat'],
                            'id_supplier' => $row['id_supplier'],
                            'nama_obat' => $row['nama_obat'],
                            'kategori_obat' => $row['kategori_obat'],
                            'bentuk_obat' => $row['bentuk_obat'],
                            'harga_obat' => $row['harga_obat'],
                            'harga_jual' => $total_harga, // Harga jual termasuk PPN
                            'signa' => $row['signa'],
                            'catatan' => $row['catatan'],
                            'cara_pakai' => $row['cara_pakai'],
                            'jumlah' => $row['jumlah'],
                            'harga_satuan' => $row['harga_satuan'],
                        ]
                    ],
                ];
            }

            // Menghitung total harga obat dan alkes
            $total_obatalkes = $this->DetailTransaksiModel
                ->selectSum('((harga_transaksi * qty_transaksi) - ((harga_transaksi * qty_transaksi) * diskon / 100))', 'total_harga')
                ->where('detail_transaksi.id_transaksi', $id)
                ->where('detail_transaksi.jenis_transaksi', 'Obat dan Alkes')
                ->get()->getRowArray();

            // Memeriksa apakah transaksi valid dan lunas
            if (!empty($transaksi) && $transaksi['lunas'] == 1) {
                // Menyiapkan data untuk ditampilkan
                $data = [
                    'transaksi' => $transaksi,
                    'layanan' => array_values($result_layanan), // Mengubah array hasil layanan menjadi indexed array
                    'obatalkes' => array_values($result_obatalkes), // Mengubah array hasil obat menjadi indexed array
                    'total_layanan' => $total_layanan['total_harga'], // Total harga layanan
                    'total_obatalkes' => $total_obatalkes['total_harga'], // Total harga obat
                    'title' => 'Detail Transaksi ' . $id . ' - ' . $this->systemName // Judul halaman
                ];

                // Menghasilkan dan menampilkan struk transaksi dalam format PDF
                $dompdf = new Dompdf();
                $html = view('dashboard/transaksi/struk', $data);
                $dompdf->loadHtml($html);
                $dompdf->render();
                $dompdf->stream('kwitansi-id-' . $transaksi['id_transaksi'] . '-' . $transaksi['no_kwitansi'] . '-' . $transaksi['tgl_transaksi'] . '-' . urlencode($transaksi['nama_pasien']) . '.pdf', [
                    'Attachment' => FALSE // Mengunduh PDF atau membuka di browser
                ]);
            } else {
                throw PageNotFoundException::forPageNotFound(); // Jika transaksi tidak valid, lempar exception
            }
        } else {
            throw PageNotFoundException::forPageNotFound(); // Jika peran tidak valid, lempar exception
        }
    }

    // LAPORAN TRANSAKSI
    public function dailyreportinit()
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Menyiapkan data untuk tampilan halaman laporan
            $data = [
                'title' => 'Laporan Transaksi Harian - ' . $this->systemName, // Judul halaman
                'headertitle' => 'Laporan Transaksi Harian', // Judul header
                'agent' => $this->request->getUserAgent() // Mengambil informasi user agent
            ];
            return view('dashboard/transaksi/report/daily', $data); // Mengembalikan tampilan halaman laporan
        } else {
            throw PageNotFoundException::forPageNotFound(); // Menampilkan halaman tidak ditemukan jika peran tidak valid
        }
    }

    public function dailyreport($tgl_transaksi)
    {
        // Memeriksa peran pengguna, hanya 'Admin' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil tanggal dari query string
            $tanggal = $tgl_transaksi;

            // Memeriksa apakah tanggal diisi
            if (!$tanggal) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'Tanggal harus diisi',
                ]);
            }

            // Mengambil Data Transaksi dari Model
            $data = $this->TransaksiModel->where('lunas', 1)->like('tgl_transaksi', $tgl_transaksi)->findAll();
            $total_all = $this->TransaksiModel
                ->where('lunas', 1)
                ->like('tgl_transaksi', $tgl_transaksi)
                ->selectSum('total_pembayaran')
                ->get()
                ->getRow()
                ->total_pembayaran;

            // Mengembalikan respons JSON dengan data pasien
            return $this->response->setJSON([
                'data' => $data,
                'total_all' => $total_all
            ]);
        } else {
            // Jika peran tidak dikenali, kembalikan status 404
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Halaman tidak ditemukan',
            ]);
        }
    }

    public function dailyreportexcel($tgl_transaksi)
    {
        // Memeriksa peran pengguna, hanya 'Admin' atau 'Kasir' yang diizinkan
        if (session()->get('role') == 'Admin' || session()->get('role') == 'Kasir') {
            // Mengambil Data Transaksi dari Model
            $transaksi = $this->TransaksiModel->where('lunas', 1)->like('tgl_transaksi', $tgl_transaksi)->findAll();
            $total_all = $this->TransaksiModel
                ->where('lunas', 1)
                ->like('tgl_transaksi', $tgl_transaksi)
                ->selectSum('total_pembayaran')
                ->get()
                ->getRow()
                ->total_pembayaran;

            // Memeriksa apakah detail pembelian obat kosong
            if (empty($transaksi)) {
                throw PageNotFoundException::forPageNotFound();
            } else {
                // Membuat nama file berdasarkan tanggal pembelian
                $filename = $tgl_transaksi . '-transaksi';
                $tanggal = Time::parse($tgl_transaksi);
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Menambahkan informasi header di spreadsheet
                $sheet->setCellValue('A1', 'KLINIK UTAMA MATA PADANG EYE CENTER TELUK KUANTAN');
                $sheet->setCellValue('A2', 'Jl. Rusdi S. Abrus No. 35 LK III Sinambek, Kelurahan Sungai Jering, Kecamatan Kuantan Tengah, Kabupaten Kuantan Singingi, Riau.');
                $sheet->setCellValue('A3', 'LAPORAN TRANSAKSI HARIAN');

                // Menambahkan informasi tanggal dan supplier
                $sheet->setCellValue('A4', 'Hari/Tanggal:');
                $sheet->setCellValue('C4', $tanggal->toLocalizedString('d MMMM yyyy'));

                // Menambahkan header tabel detail pembelian
                $sheet->setCellValue('A5', 'No.');
                $sheet->setCellValue('B5', 'Nomor Kwitansi');
                $sheet->setCellValue('D5', 'Total Harga');

                // Mengatur tata letak dan gaya untuk header
                $spreadsheet->getActiveSheet()->mergeCells('A1:D1');
                $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
                $spreadsheet->getActiveSheet()->mergeCells('A3:D3');
                $spreadsheet->getActiveSheet()->mergeCells('B5:C5');
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
                $spreadsheet->getActiveSheet()->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $spreadsheet->getDefaultStyle()->getFont()->setName('Helvetica');
                $spreadsheet->getDefaultStyle()->getFont()->setSize(8);

                // Mengisi data detail pembelian obat ke dalam spreadsheet
                $column = 6;
                foreach ($transaksi as $list) {
                    $sheet->setCellValue('A' . $column, ($column - 5));
                    $sheet->setCellValue('B' . $column, $list['no_kwitansi']);
                    $sheet->setCellValue('D' . $column, $list['total_pembayaran']);
                    // Mengatur format grand total
                    $sheet->getStyle('D' . $column)->getNumberFormat()->setFormatCode('_\Rp * #,##0_-;[Red]_\Rp * -#,##0_-;_-_\Rp * \"-\"_-;_-@_-');
                    // Menggabungkan kolom Nomor Kwitansi
                    $spreadsheet->getActiveSheet()->mergeCells('B' . ($column) . ':C' . ($column));
                    // Mengatur gaya teks
                    $sheet->getStyle('A' . $column . ':D' . $column)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('A' . $column)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A' . $column . ':D' . $column)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                    $column++;
                }

                // Menambahkan total pemasukan di bawah tabel
                $sheet->setCellValue('A' . ($column), 'Total Pemasukan');
                $spreadsheet->getActiveSheet()->mergeCells('A' . ($column) . ':C' . ($column));
                $sheet->setCellValue('D' . ($column), $total_all);
                // Mengatur format untuk total pemasukan
                $sheet->getStyle('D' . ($column))->getNumberFormat()->setFormatCode('_\Rp * #,##0_-;[Red]_\Rp * -#,##0_-;_-_\Rp * \"-\"_-;_-@_-');

                // Menambahkan bagian tanda tangan
                $sheet->setCellValue('D' . ($column + 2), 'PLACEHOLDER');
                $sheet->setCellValue('D' . ($column + 7), '(_________________________)');

                // Mengatur gaya teks untuk header dan total
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2')->getFont()->setSize(6);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C4:C9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . ($column))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('D' . ($column + 2) . ':D' . ($column + 7))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Mengatur gaya font untuk header dan total
                $sheet->getStyle('A1:A9')->getFont()->setBold(TRUE);
                $sheet->getStyle('A5:G5')->getFont()->setBold(TRUE);
                $sheet->getStyle('A' . ($column) . ':D' . ($column))->getFont()->setBold(TRUE);

                // Menambahkan border untuk header dan tabel
                $headerBorder1 = [
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000']
                        ]
                    ]
                ];
                $sheet->getStyle('A2:D2')->applyFromArray($headerBorder1);
                $tableBorder = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000']
                        ]
                    ]
                ];
                $sheet->getStyle('A5:D' . ($column))->applyFromArray($tableBorder);

                // Mengatur lebar kolom
                $sheet->getColumnDimension('A')->setWidth(50, 'px');
                $sheet->getColumnDimension('B')->setWidth(240, 'px');
                $sheet->getColumnDimension('C')->setWidth(240, 'px');
                $sheet->getColumnDimension('D')->setWidth(240, 'px');

                // Menyimpan file spreadsheet dan mengirimkan ke browser
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet.sheet');
                header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit();
            }
        } else {
            // Menghasilkan exception jika peran tidak diizinkan
            throw PageNotFoundException::forPageNotFound();
        }
    }
}
