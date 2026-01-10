<?php
class Peminjaman extends Controller
{

    public function index()
    {
        // 1. Cek Session
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 2. Setup Data Dasar
        $data['judul'] = 'Barang Laboratorium'; // Judul sesuai gambar
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $PeminjamanModel = $this->model('Peminjaman_model');

        // 3. AMBIL DATA BARANG (Untuk Tampilan Grid/Kartu)
        // Pastikan Model sudah punya method getAllBarang() seperti langkah sebelumnya
        $data['barang'] = $PeminjamanModel->getAllBarang();

        // 4. Ambil Dropdown (Untuk isi Modal Form Peminjaman)
        $data['sub_barang'] = $PeminjamanModel->getSubBarang();

        // 5. Load View
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data); // View ini harus berisi kode HTML Grid/Kartu tadi
        $this->view('templates/footer');
    }

    // Method Baru: Untuk Search Bar di pojok kanan atas
    public function cari()
    {
        if (!isset($_SESSION)) session_start();

        $data['judul'] = 'Pencarian Barang';
        $data['profile'] = $this->model("User_model")->profile(['id_user' => $_SESSION['id_user']]);
        $PeminjamanModel = $this->model('Peminjaman_model');

        // Ambil keyword dari POST
        if (isset($_POST['keyword'])) {
            $data['barang'] = $PeminjamanModel->cariBarang($_POST['keyword']);
        } else {
            $data['barang'] = $PeminjamanModel->getAllBarang();
        }

        // Tetap butuh ini untuk Modal
        $data['sub_barang'] = $PeminjamanModel->getSubBarang();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }


    // Tambahkan/Update method ini di Peminjaman.php

    public function tambahItem($id_barang)
    {
        if (!isset($_SESSION)) session_start();

        // 1. Siapkan Keranjang di Session
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        // 2. Masukkan ID barang (Cegah duplikat)
        if (!in_array($id_barang, $_SESSION['keranjang'])) {
            $_SESSION['keranjang'][] = $id_barang;
        }

        // 3. Redirect ke Halaman Form
        // Ini akan memanggil method formPeminjaman() di bawah
        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function formPeminjaman()
    {
        $data['judul'] = 'Form Pengajuan Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Ambil data barang yang sudah dipilih di keranjang
        if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
            $data['barang_selected'] = $this->model('Peminjaman_model')->getBarangWhereIn($_SESSION['keranjang']);
        } else {
            $data['barang_selected'] = [];
        }

        // Load View dengan nama file 'from.php'
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/from', $data); // <--- SESUAI NAMA FILE ANDA
        $this->view('templates/footer');
    }

    public function prosesTambahPeminjaman()
    {
        // 1. Validasi Keranjang Kosong
        // (Sesuai name di from.php: id_jenis_barang[])
        if (empty($_POST['id_jenis_barang'])) {
            Flasher::setFlash('Tidak ada barang yang dipilih.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }

        // 2. Ambil Nama User dari Session/Database (Security)
        $dataUser['id_user'] = $_SESSION['id_user'];
        $userProfile = $this->model('User_model')->profile($dataUser);
        
        // 3. Gabungkan Data Input dengan Nama Peminjam
        $dataPayload = $_POST;
        $dataPayload['nama_peminjam'] = $userProfile['nama_user']; 

        // 4. Panggil Model SEKALI SAJA (HAPUS LOOPING DI SINI)
        if ($this->model('Peminjaman_model')->postDataPeminjaman($dataPayload) > 0) {
            
            // Hapus session keranjang jika sukses
            unset($_SESSION['keranjang']); 
            
            Flasher::setFlash('Pengajuan berhasil! Silakan lengkapi surat.', 'berhasil', '', 'success');
            header('Location: ' . BASEURL . 'Riwayat'); 
            exit;
            
        } else {
            Flasher::setFlash('Gagal mengajukan peminjaman.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
    }
    

    // Method detail (Opsional, jika ingin melihat detail riwayat, mungkin perlu controller terpisah atau method lain)
    public function detail($id_peminjaman)
    {
        $data['judul'] = 'Detail Peminjaman';
        $data['dataTampilPeminjaman'] = $this->model('Peminjaman_model')->getDetailDataPeminjaman($id_peminjaman);

        $this->view('templates/header', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    // --- FITUR EDIT / TAMBAH BARANG (LOAD DATA LAMA) ---
    public function tambahBarang($id_peminjaman)
    {
        // 1. Ambil Data Header & Detail dari Database
        $header = $this->model('Peminjaman_model')->getPeminjamanById($id_peminjaman);
        $details = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman); // Pastikan function ini mengembalikan id_jenis_barang

        if (!$header) {
            Flasher::setFlash('Data transaksi tidak ditemukan.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        // 2. Reset Keranjang Lama
        $_SESSION['keranjang'] = [];
        
        // 3. Siapkan Array Data Edit untuk View
        // Kita butuh mapping ID Barang -> Jumlah & Keterangan untuk pre-fill input
        $edit_details_map = []; 

        foreach ($details as $item) {
            // Masukkan ID ke Keranjang agar muncul di list view
            // Perhatikan: Model getDetailBarangByPeminjamanId harus select id_jenis_barang juga
            // Jika model Anda belum return id_jenis_barang, update modelnya dulu (lihat langkah 3 di bawah).
            $_SESSION['keranjang'][] = $item['id_jenis_barang'];

            // Simpan detail (jumlah/ket) ke map
            $edit_details_map[$item['id_jenis_barang']] = [
                'jumlah' => $item['jumlah'],
                'keterangan' => $item['keterangan_barang'] ?? '-'
            ];
        }

        // 4. Simpan Data Edit ke Session
        $_SESSION['edit_mode'] = true;
        $_SESSION['edit_id_peminjaman'] = $id_peminjaman;
        $_SESSION['edit_header'] = $header;         // Untuk Judul, Tanggal, dll
        $_SESSION['edit_details_map'] = $edit_details_map; // Untuk Jumlah & Ket per barang

        // 5. Redirect ke Form
        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    // --- PROSES SIMPAN PERUBAHAN (UPDATE) ---
    public function prosesUpdatePeminjaman()
    {
        // Cek apakah ini mode edit
        if (!isset($_SESSION['edit_id_peminjaman'])) {
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        // 1. Ambil Data User (Sama seperti Tambah)
        $dataUser['id_user'] = $_SESSION['id_user'];
        $userProfile = $this->model('User_model')->profile($dataUser);

        // 2. Siapkan Data Payload
        $dataPayload = $_POST;
        $dataPayload['id_peminjaman'] = $_SESSION['edit_id_peminjaman']; // ID Transaksi yg mau diedit
        $dataPayload['nama_peminjam'] = $userProfile['nama_user'];
        // Status tetap sama atau direset ke 'Melengkapi Surat' tergantung kebijakan. 
        // Di sini kita reset agar admin cek ulang jika ada perubahan item.
        $dataPayload['status'] = 'Melengkapi Surat'; 

        // 3. Panggil Model Update
        // Pastikan Model Peminjaman_model punya method ubahDataPeminjaman()
        if ($this->model('Peminjaman_model')->ubahDataPeminjaman($dataPayload) >= 0) {
            
            // Bersihkan Session Edit & Keranjang
            unset($_SESSION['keranjang']);
            unset($_SESSION['edit_mode']);
            unset($_SESSION['edit_id_peminjaman']);
            unset($_SESSION['edit_header']);
            unset($_SESSION['edit_details_map']);

            Flasher::setFlash('Data peminjaman berhasil diperbarui.', 'berhasil', '', 'success');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        } else {
            Flasher::setFlash('Gagal memperbarui data.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
    }
}
