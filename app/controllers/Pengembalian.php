<?php

class Pengembalian extends Controller {

    public function __construct() {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        if ($_SESSION['id_role'] != 3 && $_SESSION['id_role'] != 4) {
            header('Location: ' . BASEURL . 'Beranda');
            exit;
        }
    }
    
    public function index() {
        $data['judul'] = 'Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        // Ambil SEMUA data peminjaman untuk semua role
        $data['riwayat'] = $this->model('Pengembalian_model')->getAllRiwayatForPetugas();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data); 
        $this->view('Pengembalian/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id) {
        $data['judul'] = 'Detail Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        // Ambil data satu baris spesifik untuk detail
        $data['detail'] = $this->model('Pengembalian_model')->getRiwayatById($id);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data); 
        $this->view('Pengembalian/detail', $data);
        $this->view('templates/footer');
    }
}