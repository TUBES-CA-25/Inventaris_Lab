<?php
class Peminjaman extends Controller {
    public function index() {
        if (!isset($_SESSION)) {
            session_start();
        }
    
        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    
        $data['judul'] = 'Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
    
        $TambahPeminjamanModel = $this->model('Peminjaman_model');
    
        // Ambil daftar sub_barang dari database
        $data['sub_barang'] = $TambahPeminjamanModel->getSubBarang();
    
        // Cek apakah ada filter yang dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['sub_barang'])) {
                $_SESSION['selected_sub_barang'] = $_POST['sub_barang']; // Simpan pilihan sub_barang di sesi
            } else {
                unset($_SESSION['selected_sub_barang']); // Hapus filter jika "Pilih Jenis Barang" dipilih
            }

            if (!empty($_POST['status'])) {
                $_SESSION['selected_status'] = $_POST['status']; // Simpan pilihan status di sesi
            } else {
                unset($_SESSION['selected_status']); // Hapus filter jika status tidak dipilih
            }
        }
    
        // Gunakan filter jika ada
        if (!empty($_SESSION['selected_sub_barang']) || !empty($_SESSION['selected_status'])) {
            $data['peminjaman'] = $TambahPeminjamanModel->getPeminjamanByFilters(
                $_SESSION['selected_sub_barang'] ?? '', 
                $_SESSION['selected_status'] ?? ''
            );
        } else {
            $data['peminjaman'] = $TambahPeminjamanModel->getPeminjamanBarang(); // Semua data jika tidak ada filter
        }
    
        // Format tanggal
        foreach ($data['peminjaman'] as &$peminjaman) {
            $peminjaman['tanggal_pengajuan'] = date('d-m-Y', strtotime($peminjaman['tanggal_pengajuan']));
        }
    
        $this->view('templates/header', data: $data);
        $this->view('templates/sidebar', data: $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }

    
    
    public function detail($id_peminjaman) {
        $data['judul'] = 'Peminjaman';
        
        // Mengambil data kondisi barang dari model
        $DetailPeminjaman = $this->model('Peminjaman_model');
        $data['dataTampilPeminjaman'] = $DetailPeminjaman->getDetailDataPeminjaman($id_peminjaman);

        // Memanggil view transaksi barang
        $this->view('templates/header', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }
    public function tambahPeminjaman() {
        $requiredKeys = ['nama_peminjam','judul_kegiatan', 'tanggal_peminjaman', 'tanggal_pengembalian', 'id_jenis_barang', 'jumlah_peminjaman', 'keterangan_peminjaman','status'];

        // Validate the required fields
        foreach ($requiredKeys as $key) {
            if (!isset($_POST[$key]) || empty($_POST[$key])) {
                Flasher::setFlash('Input data tidak lengkap. Pastikan semua field terisi.', 'gagal', '', 'danger');
                header('Location: ' . BASEURL . 'Peminjaman');
                exit;
            }
        }

        // Check if tanggal_pengembalian is earlier than tanggal_peminjaman
        if (strtotime($_POST['tanggal_pengembalian']) < strtotime($_POST['tanggal_peminjaman'])) {
            Flasher::setFlash('Tanggal pengembalian tidak boleh lebih awal dari tanggal peminjaman.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        // Set status to default 'diproses' if not provided
        if (!isset($_POST['status'])) {
            $_POST['status'] = 'diproses';
        }

        // Insert data into the database
        if ($this->model('Peminjaman_model')->postDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('Peminjaman berhasil ditambahkan.', 'success', '', 'success');
        } else {
            Flasher::setFlash('Peminjaman gagal ditambahkan.', 'danger', '', 'danger');
        }

        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }

    public function hapusPeminjaman($id_peminjaman) {
        if (!isset($id_peminjaman) || empty($id_peminjaman)) {
            Flasher::setFlash('ID tidak valid.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        if ($this->model('Peminjaman_model')->hapusDataPeminjaman($id_peminjaman) > 0) {
            Flasher::setFlash('Data berhasil dihapus.', 'success', '', 'success');
        } else {
            Flasher::setFlash('Data gagal dihapus.', 'danger', '', 'danger');
        }

        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }

    public function getUbah() {
        if (!isset($_POST['id_peminjaman']) || empty($_POST['id_peminjaman'])) {
            echo json_encode(['error' => 'ID peminjaman tidak ditemukan']);
            return;
        }
        
        $id = $_POST['id_peminjaman'];
        echo json_encode($this->model('Peminjaman_model')->getUbah($id));
    }
    
    
    
    public function ubahPeminjaman() {
        if ($this->model('Peminjaman_model')->ubahDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('Data berhasil diubah.', 'success', '', 'success');
        } else {
            Flasher::setFlash('Data gagal diubah.', 'danger', '', 'danger');
        }
        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }
}