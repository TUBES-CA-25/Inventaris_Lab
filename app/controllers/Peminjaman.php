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
        // Validasi Keranjang Kosong
        if (empty($_POST['id_jenis_barang'])) {
            Flasher::setFlash('Tidak ada barang yang dipilih.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        $successCount = 0;
        $totalItems = count($_POST['id_jenis_barang']);

        // Looping Insert untuk setiap barang
        for ($i = 0; $i < $totalItems; $i++) {
            $dataInsert = [
                'nama_peminjam'         => $_SESSION['nama_user'] ?? 'Mahasiswa',
                'judul_kegiatan'        => $_POST['judul_kegiatan'],
                'tanggal_pengajuan'     => $_POST['tanggal_pengajuan'],
                'tanggal_peminjaman'    => $_POST['tanggal_peminjaman'],
                'tanggal_pengembalian'  => $_POST['tanggal_pengembalian'],
                'id_jenis_barang'       => $_POST['id_jenis_barang'][$i],
                'jumlah_peminjaman'     => $_POST['jumlah_peminjaman'][$i],
                'keterangan_peminjaman' => $_POST['keterangan_peminjaman'][$i],
                'status'                => 'diproses'
            ];

            if ($this->model('Peminjaman_model')->postDataPeminjaman($dataInsert) > 0) {
                $successCount++;
            }
        }

        if ($successCount > 0) {
            unset($_SESSION['keranjang']); // Kosongkan keranjang setelah sukses
            Flasher::setFlash($successCount . ' barang berhasil diajukan.', 'success', '', 'success');
            header('Location: ' . BASEURL . 'Peminjaman');
        } else {
            Flasher::setFlash('Gagal mengajukan peminjaman.', 'danger', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        }
        exit;
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

    public function hapusItem($id_barang)
    {
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION['keranjang'])) {
            // Cari posisi barang di array session
            $key = array_search($id_barang, $_SESSION['keranjang']);

            // Jika ketemu, hapus
            if ($key !== false) {
                unset($_SESSION['keranjang'][$key]);
                // Rapikan index array agar tidak loncat
                $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            }
        }

        // Kembali ke form
        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }
}
