<?php
class Beranda extends Controller {

    public function __construct()
    {
        // Cek apakah ada session login?
        if (!isset($_SESSION['login'])) {
            // Jika tidak ada, paksa pindah ke halaman Login
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }
    
    public function index() {

        $data['judul'] = 'Beranda';

         // Memanggil model untuk mendapatkan jumlah data dari setiap tabel
            $data['jumlah_jenis_barang'] = $this->model('Beranda_model')->getCount('mst_jenis_barang');
            $data['jumlah_peminjaman'] = $this->model('Beranda_model')->getCount('trx_peminjaman');
            $data['jumlah_merek_barang'] = $this->model('Beranda_model')->getCount('mst_merek_barang');
            $data['jumlah_detail_barang'] = $this->model('Beranda_model')->getCount('detail_barang');
            $data['jumlah_pengembalian'] = $this->model('Beranda_model')->getCount('trx_pengembalian');
        
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        // $userModel = $this->model('User_model');
        // $data['dataTampilUser']= $userModel->profile();
        // Memanggil view transaksi barang
        $this->view('templates/header', data: $data);
        $this->view('templates/sidebar', data: $data);
        $this->view('Beranda/index', $data);
        $this->view('templates/footer');
    }
}