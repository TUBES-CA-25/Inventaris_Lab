<?php

/**
 * Controller Peminjaman
 * * Mengelola transaksi peminjaman barang.
 * * Fitur: List, Filter, Tambah, Hapus, Ubah, Detail.
 */
class Peminjaman extends Controller {

    /**
     * @var Peminjaman_model Instance model untuk efisiensi memori.
     */
    private $peminjamanModel;

    public function __construct() {
        // 1. Gatekeeper: Cek Login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 2. Load Model Utama Sekali Saja
        $this->peminjamanModel = $this->model('Peminjaman_model');
    }
    
    /**
     * Menampilkan daftar peminjaman dengan fitur Filter (Jenis & Status).
     */
    public function index() {
        $data['judul'] = 'Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        
        // Load Profile User
        $data['profile'] = $this->model("User_model")->profile($data);
    
        // Ambil daftar sub_barang untuk Dropdown Filter
        $data['sub_barang'] = $this->peminjamanModel->getSubBarang();
    
        // -----------------------------------------------------------
        // LOGIKA FILTER (Disimpan dalam SESSION)
        // -----------------------------------------------------------
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set Filter Sub Barang
            if (!empty($_POST['sub_barang'])) {
                $_SESSION['filter_sub_barang'] = $_POST['sub_barang'];
            } else {
                unset($_SESSION['filter_sub_barang']); // Reset jika kosong
            }

            // Set Filter Status
            if (!empty($_POST['status'])) {
                $_SESSION['filter_status'] = $_POST['status'];
            } else {
                unset($_SESSION['filter_status']); // Reset jika kosong
            }
        }
    
        // Ambil data berdasarkan Filter yang tersimpan di Session
        // Menggunakan Null Coalescing Operator (??) untuk nilai default string kosong
        $filterBarang = $_SESSION['filter_sub_barang'] ?? '';
        $filterStatus = $_SESSION['filter_status'] ?? '';

        $data['peminjaman'] = $this->peminjamanModel->getPeminjamanByFilters($filterBarang, $filterStatus);
    
        // Format tanggal agar enak dibaca user (d-m-Y)
        // Menggunakan reference (&) untuk memodifikasi array asli secara langsung
        foreach ($data['peminjaman'] as &$row) {
            $row['tanggal_pengajuan'] = date('d-m-Y', strtotime($row['tanggal_pengajuan']));
        }
    
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }
    
    /**
     * Menampilkan detail peminjaman spesifik.
     */
    public function detail($id_peminjaman) {
        $data['judul'] = 'Detail Peminjaman';
        $data['dataTampilPeminjaman'] = $this->peminjamanModel->getDetailDataPeminjaman($id_peminjaman);

        // Pastikan view yang dipanggil benar (di kode asli Anda memanggil DetailBarang/index, 
        // pastikan ini disengaja atau seharusnya Peminjaman/detail)
        $this->view('templates/header', $data);
        $this->view('DetailBarang/index', $data); 
        $this->view('templates/footer');
    }

    /**
     * Proses tambah peminjaman baru.
     */
    public function tambahPeminjaman() {
        // Validasi Field Wajib
        $requiredKeys = ['nama_peminjam','judul_kegiatan', 'tanggal_peminjaman', 'tanggal_pengembalian', 'id_jenis_barang', 'jumlah_peminjaman', 'keterangan_peminjaman'];

        foreach ($requiredKeys as $key) {
            if (empty($_POST[$key])) {
                Flasher::setFlash('Gagal', 'Input data tidak lengkap.', ' Pastikan semua field terisi.', 'danger');
                header('Location: ' . BASEURL . 'Peminjaman');
                exit;
            }
        }

        // Validasi Logika Tanggal
        if (strtotime($_POST['tanggal_pengembalian']) < strtotime($_POST['tanggal_peminjaman'])) {
            Flasher::setFlash('Gagal', 'Tanggal Invalid.', ' Tanggal pengembalian tidak boleh lebih awal dari peminjaman.', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        // Set Default Status jika kosong
        if (empty($_POST['status'])) {
            $_POST['status'] = 'Diproses';
        }

        // Proses Insert
        if ($this->peminjamanModel->postDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('Peminjaman', 'berhasil', 'diajukan/ditambahkan', 'success');
        } else {
            Flasher::setFlash('Peminjaman', 'gagal', 'ditambahkan', 'danger');
        }

        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }

    /**
     * Proses hapus peminjaman.
     * Dilengkapi penanganan error Foreign Key.
     */
    public function hapusPeminjaman($id_peminjaman) {
        if (empty($id_peminjaman)) {
            Flasher::setFlash('Error', 'ID tidak valid.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        $status = $this->peminjamanModel->hapusDataPeminjaman($id_peminjaman);

        if ($status > 0) {
            Flasher::setFlash('Data Peminjaman', 'berhasil', 'dihapus', 'success');
        } elseif ($status == -1) {
            // Pesan Khusus jika data sudah masuk transaksi Pengembalian
            Flasher::setFlash('Gagal Dihapus!', 'Data Terkunci.', ' Transaksi ini mungkin sudah memiliki data pengembalian.', 'danger');
        } else {
            Flasher::setFlash('Data Peminjaman', 'gagal', 'dihapus', 'danger');
        }

        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }

    /**
     * Mengambil data untuk Modal Edit (AJAX).
     */
    public function getUbah() {
        if (empty($_POST['id_peminjaman'])) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }
        echo json_encode($this->peminjamanModel->getDetailDataPeminjaman($_POST['id_peminjaman']));
    }
    
    /**
     * Proses simpan perubahan data.
     */
    public function ubahPeminjaman() {
        if ($this->peminjamanModel->ubahDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('Data Peminjaman', 'berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('Data Peminjaman', 'tidak ada', 'yang diubah', 'warning');
        }
        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }
}