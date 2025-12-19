<?php

/**
 * Controller Pengembalian
 * * Mengelola proses pengembalian barang dan pemantauan status (Tepat waktu/Terlambat).
 */
class Pengembalian extends Controller
{
    /**
     * @var Pengembalian_model Instance model agar hemat memori.
     */
    private $pengembalianModel;

    public function __construct()
    {
        // 1. Gatekeeper: Cek Login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 2. Load Model
        $this->pengembalianModel = $this->model('Pengembalian_model');
    }

    /**
     * Menampilkan daftar transaksi yang perlu dikembalikan atau sudah dikembalikan.
     */
    public function index()
    {
        $data['judul'] = 'Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        
        // Load data profil user & daftar pengembalian
        $data['profile'] = $this->model("User_model")->profile($data);
        $data['pengembalian'] = $this->pengembalianModel->getAllPengembalian();

        // Tidak perlu logic session flash manual di sini, 
        // karena Flasher::flash() di View akan menanganinya otomatis.

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Mengambil data untuk modal Edit (AJAX).
     */
    public function getUbah()
    {
        // Validasi input ID
        if (empty($_POST['id_pengembalian'])) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }
        
        echo json_encode($this->pengembalianModel->getUbahPengembalian($_POST['id_pengembalian']));
    }

    /**
     * Memproses update status pengembalian.
     * Sistem akan otomatis menentukan apakah 'Tepat Waktu' atau 'Bermasalah'.
     */
    public function ubahPengembalian() {
        if ($this->pengembalianModel->updatePengembalian($_POST) > 0) {
            Flasher::setFlash('Data Pengembalian', 'berhasil', 'diperbarui', 'success');
        } else {
            // Bisa jadi gagal query atau tidak ada perubahan data
            Flasher::setFlash('Data Pengembalian', 'tidak ada', 'yang diubah', 'warning');
        }
        
        header('Location: ' . BASEURL . 'Pengembalian');
        exit;
    }
}