<?php

/**
 * Controller Beranda (Dashboard Utama)
 * * Menampilkan statistik ringkasan sistem dan informasi profil pengguna.
 * * Mengimplementasikan optimasi performa dengan meminimalisir pemanggilan database.
 */
class Beranda extends Controller {

    public function __construct()
    {
        // Keamanan: Gatekeeper
        // Memastikan hanya user yang sudah login yang bisa mengakses halaman ini.
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }
    
    /**
     * Menampilkan halaman dashboard.
     * Mengambil seluruh data statistik dalam satu kali proses untuk kecepatan loading.
     */
    public function index() {
        $data['judul'] = 'Beranda';

        // ------------------------------------------------------------------
        // BAGIAN 1: Mengambil Data Statistik (Optimasi Performa)
        // ------------------------------------------------------------------
        
        // Instansiasi Model SEKALI SAJA di awal untuk menghemat memori
        $berandaModel = $this->model('Beranda_model');

        // Menggunakan method 'getAllCounts' yang mengambil 5 jenis data hitungan
        // hanya dalam 1 kali request ke database (Single Query).
        $stats = $berandaModel->getAllCounts();

        // Mapping hasil query ke variabel $data agar bisa dibaca oleh View
        $data['jumlah_jenis_barang']  = $stats['jml_jenis'];
        $data['jumlah_peminjaman']    = $stats['jml_peminjaman'];
        $data['jumlah_merek_barang']  = $stats['jml_merek'];
        
        // Catatan: Mengambil jumlah barang dari tabel fisik (trx_barang)
        // jauh lebih cepat daripada mengambil dari View (detail_barang).
        $data['jumlah_detail_barang'] = $stats['jml_barang']; 
        
        $data['jumlah_pengembalian']  = $stats['jml_pengembalian'];
        
        // ------------------------------------------------------------------
        // BAGIAN 2: Data User & Layout
        // ------------------------------------------------------------------
        
        $data['id_user'] = $_SESSION['id_user'];
        
        // Mengambil detail profil user yang sedang login
        $data['profile'] = $this->model("User_model")->profile($data);

        // Render View
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Beranda/index', $data);
        $this->view('templates/footer');
    }
}