<?php

class Riwayat extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }

    public function index($filter = 'all')
    {
        $data['judul'] = 'Riwayat Peminjaman';
        $riwayatModel = $this->model('Riwayat_model');
        $userModel = $this->model('User_model');

        // Ambil data user login
        $dataUser['id_user'] = $_SESSION['id_user'];
        $currentUser = $userModel->profile($dataUser);

        $id_user_login = $_SESSION['id_user'];
        $id_role_login = $currentUser['id_role'];

        // Role 7 = Mahasiswa/User Biasa
        $is_mahasiswa = ($id_role_login == 7);

        // Variabel untuk menampung hasil statistik
        $stats = [];

        if ($is_mahasiswa) {
            // Mode User Biasa: Ambil Data & Statistik Diri Sendiri
            $data['riwayat'] = $riwayatModel->getRiwayatByUser($id_user_login);
            $stats = $riwayatModel->getStatistik($id_user_login);
            
            $data['is_admin'] = false;
            $data['active_tab'] = 'me';
        } else {
            // Mode Admin/Staff
            $data['is_admin'] = true;

            if ($filter == 'me') {
                // Admin melihat riwayat sendiri
                $data['riwayat'] = $riwayatModel->getRiwayatByUser($id_user_login);
                $stats = $riwayatModel->getStatistik($id_user_login);
                $data['active_tab'] = 'me';
            } else {
                // Admin melihat semua riwayat
                $data['riwayat'] = $riwayatModel->getAllRiwayat();
                $stats = $riwayatModel->getStatistik(null); // Parameter null = hitung semua
                $data['active_tab'] = 'all';
            }
        }

        // Masukkan hasil statistik ke $data agar bisa dibaca di View (index.php)
        // Menggunakan operator null coalescing (?? 0) untuk mencegah error jika data kosong
        $data['total_disetujui'] = $stats['total_disetujui'] ?? 0;
        $data['total_diproses']  = $stats['total_diproses'] ?? 0;
        $data['total_ditolak']   = $stats['total_ditolak'] ?? 0;
        $data['total_kembali']   = $stats['total_kembali'] ?? 0;

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $currentUser;

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Riwayat/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }
        $data['info_peminjaman'] = $this->model('Peminjaman_model')->getPeminjamanById($id_peminjaman);
        $rawDetailBarang = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman);

        // Formatting data barang
        foreach ($rawDetailBarang as &$item) {
            $rawFoto = $item['foto_barang'] ?? '';

            if (!empty($rawFoto)) {
                $namaFile = basename($rawFoto);
                $item['foto_url_ready'] = BASEURL . 'img/foto-barang/' . $namaFile;
            } else {
                $item['foto_url_ready'] = 'https://via.placeholder.com/150?text=No+Img';
            }

            $item['spesifikasi_barang'] = $item['spesifikasi_barang'] ?? '-';
        }

        $data['detail_barang'] = $rawDetailBarang;
        $data['judul'] = 'Detail Peminjaman';

        $this->view('templates/header', $data);
        // $this->view('templates/sidebar', $data);
        $this->view('Riwayat/detail', $data);
        $this->view('templates/footer');
    }

    public function cetakPdf($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
             echo "ID tidak valid."; exit;
        }
        $model = $this->model('Peminjaman_model');
        $info = $model->getPeminjamanById($id_peminjaman);

        $fileName = $info['file_surat'] ?? null;
        $folderPath = __DIR__ . '/../../public/files/surat-peminjaman/';
        $fullPath = $folderPath . $fileName;

        if (!empty($fileName) && file_exists($fullPath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($fullPath));
            header('Accept-Ranges: bytes');
            
            readfile($fullPath);
            exit;
        } else {
            echo "<h3>File surat belum tersedia atau tidak ditemukan di server.</h3>";
        }
    }
}