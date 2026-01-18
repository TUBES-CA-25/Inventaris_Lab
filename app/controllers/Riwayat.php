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

        if ($is_mahasiswa) {
            // Mode User Biasa
            $data['riwayat'] = $riwayatModel->getRiwayatByUser($id_user_login);
            $data['is_admin'] = false;
            $data['active_tab'] = 'me';
        } else {
            // Mode Admin/Staff
            $data['is_admin'] = true;

            if ($filter == 'me') {
                $data['riwayat'] = $riwayatModel->getRiwayatByUser($id_user_login);
                $data['active_tab'] = 'me';
            } else {
                $data['riwayat'] = $riwayatModel->getAllRiwayat();
                $data['active_tab'] = 'all';
            }
        }

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $currentUser;

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Riwayat/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id_peminjaman)
    {
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