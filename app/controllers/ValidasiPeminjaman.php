<?php
class ValidasiPeminjaman extends Controller
{
    public function index()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        $data['judul'] = 'Validasi Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $data['peminjaman'] = $this->model('Peminjaman_model')->getPeminjamanByFilters('', 'diproses');

        foreach ($data['peminjaman'] as &$peminjaman) {
            $peminjaman['tanggal_pengajuan'] = date('d-m-Y', strtotime($peminjaman['tanggal_pengajuan']));
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('ValidasiPeminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Validasi Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Panggil model yang sudah diupdate tadi
        $data['peminjaman'] = $this->model('Peminjaman_model')->getPeminjamanById($id);

        // Cek jika data tidak ditemukan (misal ID salah)
        if (!$data['peminjaman']) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('ValidasiPeminjaman/DetailPeminjaman', $data); // Load view baru
        $this->view('templates/footer');
    }
}
