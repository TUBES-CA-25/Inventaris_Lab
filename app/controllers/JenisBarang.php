<?php

/**
 * Controller JenisBarang
 * * Bertugas mengatur manajemen data Master Jenis Barang (CRUD).
 * Termasuk validasi duplikasi dan penanganan error saat penghapusan data relasional.
 */
class JenisBarang extends Controller {

    /**
     * @var Jenis_barang_model Menyimpan instance model agar tidak perlu dipanggil berulang kali.
     */
    private $jenisBarangModel;

    public function __construct()
    {
        // 1. Keamanan: Cek apakah user sudah login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        
        // 2. Efisiensi: Inisialisasi model sekali saja saat class dimuat
        $this->jenisBarangModel = $this->model('Jenis_barang_model');
    }

    /**
     * Menampilkan halaman utama daftar jenis barang.
     */
    public function index() {
        $data['judul'] = 'Jenis Barang';
        $data['dataTampilJenisBarang'] = $this->jenisBarangModel->getDataJenisBarang();
        
        // Data pendukung untuk layout (Sidebar/Header)
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Jenis_barang/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Menangani proses penambahan data baru.
     * Melakukan validasi duplikasi sebelum insert.
     */
    public function tambahJenisBarang(){
        // Langkah 1: Cek Duplikasi (Nama atau Kode Sub tidak boleh sama)
        if ($this->jenisBarangModel->cekDataJenisBarang($_POST) > 0) {
            // PARAMETER FLASHER: Objek, Pesan, Aksi, Tipe
            Flasher::setFlash('Jenis Barang', 'gagal', 'ditambahkan (Data Sudah Ada)', 'danger');
            header('Location: '. BASEURL . 'JenisBarang');
            exit;
        }

        // Langkah 2: Proses Simpan ke Database
        if($this->jenisBarangModel->postDataJenisBarang($_POST) > 0){
            Flasher::setFlash('Jenis Barang', 'berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('Jenis Barang', 'gagal', 'ditambahkan', 'danger');
        }
        header('Location: '. BASEURL . 'JenisBarang');
        exit;
    }

    /**
     * Menangani penghapusan data.
     * Menerima return value khusus (-1) dari model jika terjadi konflik relasi.
     * * @param int $id_jenis_barang ID data yang akan dihapus
     */
    public function hapus($id_jenis_barang){
        // Panggil fungsi hapus dari model
        $status = $this->jenisBarangModel->hapusJenisBarang($id_jenis_barang);

        if ($status > 0) {
            // KASUS 1: Berhasil dihapus (Normal)
            Flasher::setFlash('Jenis Barang', 'berhasil', 'dihapus', 'success');

        } elseif ($status == -1) {
            // KASUS 2: Gagal karena Foreign Key (Data sedang dipakai di tabel Barang)
            // Memberikan pesan spesifik agar user paham kenapa gagal.
            Flasher::setFlash('Gagal Dihapus!', 'Data sedang DIGUNAKAN', ' pada menu lain. Hapus data terkait dulu.', 'danger');
        
        } else {
            // KASUS 3: Gagal umum (Query error atau ID tidak ditemukan)
            Flasher::setFlash('Jenis Barang', 'gagal', 'dihapus', 'danger');
        }

        header('Location: '. BASEURL . 'JenisBarang');
        exit;
    }

    /**
     * Mengambil data spesifik untuk kebutuhan Edit via Modal (AJAX).
     * Output berupa JSON.
     */
    public function getUbah(){
        echo json_encode($this->jenisBarangModel->getUbah($_POST['id_jenis_barang']));
    }

    /**
     * Menangani proses update/perubahan data.
     * Melakukan validasi duplikasi sebelum update.
     */
    public function ubahJenisBarang(){
        // Langkah 1: Cek Duplikasi
        // (Memastikan data baru tidak bentrok dengan data lain, kecuali dirinya sendiri)
        if ($this->jenisBarangModel->cekDataJenisBarang($_POST) > 0) {
            Flasher::setFlash('Jenis Barang', 'gagal', 'diubah (Data Sudah Ada)', 'danger');
            header('Location: '. BASEURL . 'JenisBarang');
            exit;
        }

        // Langkah 2: Proses Update
        if($this->jenisBarangModel->ubahJenisBarang($_POST) > 0){
             Flasher::setFlash('Jenis Barang', 'berhasil', 'diubah', 'success');
        } else {
             // Jika user menekan tombol simpan tapi tidak mengubah data apapun
             Flasher::setFlash('Jenis Barang', 'tidak ada', 'yang diubah', 'warning');
        }
        header('Location: '. BASEURL . 'JenisBarang');
        exit;
    }

    /**
     * Fitur Pencarian Data.
     */
    public function cari(){
        $data['judul'] = 'Jenis Barang';
        $data['dataTampilJenisBarang'] = $this->jenisBarangModel->cariDataJenisBarang();
        
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Jenis_barang/index', $data);
        $this->view('templates/footer');
    }
}