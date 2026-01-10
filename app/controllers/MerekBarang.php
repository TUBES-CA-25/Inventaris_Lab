<?php

/**
 * Controller MerekBarang
 * * Bertugas mengatur manajemen data Master Merek Barang (CRUD).
 * * Mengimplementasikan validasi duplikasi dan penanganan error relasi database.
 */
class MerekBarang extends Controller {
    
    /**
     * @var Merek_barang_model Instance model untuk efisiensi.
     */
    private $merekBarangModel;

    public function __construct()
    {
        // 1. Gatekeeper: Pastikan user sudah login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 2. Dependency Injection: Load model sekali saja
        $this->merekBarangModel = $this->model('Merek_barang_model');
    }
    
    /**
     * Menampilkan halaman utama daftar merek barang.
     */
    public function index() {
        $data['judul'] = 'Merek Barang';
        
        // Mengambil data dari property model yang sudah di-load
        $data['dataTampilMerekBarang'] = $this->merekBarangModel->getDataMerekBarang();
        
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Merek_barang/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Proses tambah data merek baru.
     */
    public function tambahMerekBarang(){
        // Langkah 1: Validasi Duplikasi (Nama atau Kode tidak boleh sama)
        if ($this->merekBarangModel->cekDataMerekBarang($_POST) > 0) {
            // PARAMETER FLASHER: Objek, Pesan, Aksi, Tipe
            Flasher::setFlash('Merek Barang', 'gagal', 'ditambahkan (Data Sudah Ada)', 'danger');
            header('Location: '. BASEURL . 'MerekBarang');
            exit;
        }

        // Langkah 2: Proses Simpan
        if($this->merekBarangModel->postDataMerekBarang($_POST) > 0){
            Flasher::setFlash('Merek Barang', 'berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('Merek Barang', 'gagal', 'ditambahkan', 'danger');
        }
        
        header('Location: '. BASEURL . 'MerekBarang');
        exit;
    }

    /**
     * Proses hapus data.
     * Menangani skenario jika data sedang digunakan oleh tabel lain.
     */
    public function hapus($id_merek_barang){
        // Panggil fungsi hapus di model yang mengembalikan kode status
        $status = $this->merekBarangModel->hapusMerekBarang($id_merek_barang);

        if($status > 0){
            // Sukses
            Flasher::setFlash('Merek Barang', 'berhasil', 'dihapus', 'success');
        } elseif ($status == -1) {
            // Gagal Khusus (Foreign Key Constraint)
            // Ini terjadi jika Merek ini dipakai oleh Barang di inventory.
            
            Flasher::setFlash('Gagal Dihapus!', 'Data sedang DIGUNAKAN', ' oleh barang lain. Hapus barang terkait dulu.', 'danger');
        } else {
            // Gagal Umum
            Flasher::setFlash('Merek Barang', 'gagal', 'dihapus', 'danger');
        }
        
        header('Location: '. BASEURL . 'MerekBarang');
        exit;
    }

    /**
     * Mengambil data spesifik untuk modal Edit (AJAX).
     */
    public function getUbah(){
        // Pastikan parameter dikirim via POST
        echo json_encode($this->merekBarangModel->getUbah($_POST['id_merek_barang']));
    }

    /**
     * Proses simpan perubahan data.
     */
    public function ubahMerekBarang(){
        // Langkah 1: Validasi Duplikasi
        if ($this->merekBarangModel->cekDataMerekBarang($_POST) > 0) {
            Flasher::setFlash('Merek Barang', 'gagal', 'diubah (Data Sudah Ada)', 'danger');
            header('Location: '. BASEURL . 'MerekBarang');
            exit;
        }
        
        // Langkah 2: Proses Update
        if($this->merekBarangModel->ubahMerekBarang($_POST) > 0){
            Flasher::setFlash('Merek Barang', 'berhasil', 'diubah', 'success');
        } else {
            // Jika tidak ada perubahan data (klik simpan tanpa edit)
            Flasher::setFlash('Merek Barang', 'tidak ada', 'yang diubah', 'warning');
        }
        
        header('Location: '. BASEURL . 'MerekBarang');
        exit;
    }

    /**
     * Fitur pencarian data.
     */
    public function cari(){
        $data['judul'] = 'Merek Barang';
        $data['dataTampilMerekBarang'] = $this->merekBarangModel->cariDataMerekBarang();
        
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Merek_barang/index', $data);
        $this->view('templates/footer');
    }
}