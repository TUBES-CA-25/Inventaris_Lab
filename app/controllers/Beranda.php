<?php

/**
 * Controller Beranda (Dashboard Utama)
 * * Menampilkan statistik ringkasan sistem dan informasi profil pengguna.
 * * Mengimplementasikan optimasi performa dengan meminimalisir pemanggilan database.
 */
class Beranda extends Controller {

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }
    
    public function index() {
        $data['judul'] = 'Beranda';

        $berandaModel = $this->model('Beranda_model');
        $stats = $berandaModel->getAllCounts();

        $data['jumlah_jenis_barang']  = $stats['jml_jenis'];
        $data['jumlah_peminjaman']    = $stats['jml_peminjaman'];
        $data['jumlah_merek_barang']  = $stats['jml_merek'];
        $data['jumlah_detail_barang'] = $stats['jml_barang']; 
        
        $data['jumlah_pengembalian']  = $stats['jml_pengembalian'];
        $data['id_user'] = $_SESSION['id_user'];

        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Beranda/index', $data);
        $this->view('templates/footer');
    }
}