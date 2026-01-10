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

    public function index()
    {
        $data['judul'] = 'Riwayat Peminjaman';

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model('User_model')->profile($data);

        $role = $_SESSION['id_role'];

        $nama_user = $data['profile']['nama_user'];

        if (in_array($role, ['1', '2', '3', '4'])) {
            $data['riwayat'] = $this->model('Riwayat_model')->getAllRiwayat();
        } else {
            $data['riwayat'] = $this->model('Riwayat_model')->getRiwayatByUser($nama_user);
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Riwayat/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id_peminjaman)
    {
        $data['info_peminjaman'] = $this->model('Peminjaman_model')->getPeminjamanById($id_peminjaman);

        $data['detail_barang'] = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman);

        $data['judul'] = 'Detail Peminjaman';

        $this->view('templates/header', $data);
        $this->view('Riwayat/detail', $data);
        $this->view('templates/footer');
    }
}