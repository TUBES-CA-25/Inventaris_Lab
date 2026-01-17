<?php
class ValidasiPeminjaman extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();

        // 1. Cek Login
        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 2. Cek Role: Mahasiswa (7) dilarang masuk sini
        if (isset($_SESSION['id_role']) && $_SESSION['id_role'] == '7') {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Validasi Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $data['peminjaman'] = $this->model('Peminjaman_model')->getValidasiGabungan();

        $data['total_disetujui'] = $this->model('Peminjaman_model')->hitungStatus('disetujui');
        $data['total_diproses']  = $this->model('Peminjaman_model')->hitungStatus('diproses');
        $data['total_ditolak']   = $this->model('Peminjaman_model')->hitungStatus('ditolak');
        $data['total_kembali']   = $this->model('Peminjaman_model')->hitungStatus('dikembalikan');

        foreach ($data['peminjaman'] as &$peminjaman) {
            $peminjaman['tanggal_pengajuan'] = date('d-m-Y', strtotime($peminjaman['tanggal_pengajuan']));
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('ValidasiPeminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Validasi Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Mengambil data detail peminjaman
        $data['peminjaman'] = $this->model('Peminjaman_model')->getDetailValidasiDataPeminjaman($id);
        $data['detail_barang'] = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id);

        if (!$data['peminjaman']) {
            Flasher::setFlash('Gagal', 'Data peminjaman tidak ditemukan', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        // Pastikan nama view ini sesuai dengan file yang Anda edit sebelumnya
        $this->view('ValidasiPeminjaman/DetailPeminjaman', $data);
        // $this->view('templates/footer');
    }

    // --- [BARU] FITUR VALIDASI BERTINGKAT ---

    // 1. Aksi untuk Kepala Lab (Huzain) - Hanya centang database
    public function accKalab()
    {
        // Cek Role: Hanya Kepala Lab (Role ID 1) yang boleh
        if ($_SESSION['id_role'] != '1') {
            Flasher::setFlash('Akses Ditolak', 'Hanya Kepala Lab yang bisa menyetujui tahap ini.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_peminjaman'];

            if ($this->model('Peminjaman_model')->validasiKalab($id) > 0) {
                Flasher::setFlash('Berhasil', 'Validasi Tahap 1 (Kepala Lab) disetujui.', '', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan atau data sudah disetujui sebelumnya.', '', 'warning');
            }
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id);
            exit;
        }
    }

    // 2. Halaman Drag & Drop Tanda Tangan (Untuk Laboran)
    public function viewValidasiPosisi($id_peminjaman)
    {
        // ... (Kode pengecekan Role dan Logika Warna Box TETAP SAMA seperti sebelumnya) ...
        $role = $_SESSION['id_role'];

        if ($role != '1' && $role != '2') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki wewenang tanda tangan.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }

        $peminjaman = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);

        if ($role == '1') { // HUZAIN
            $label_box = "TTD Kepala Lab (Huzain)";
            $warna_box = "rgba(78, 115, 223, 0.6)";
            $border_box = "#4e73df";
        } else { // FATIMAH
            $label_box = "TTD Laboran (Fatimah)";
            $warna_box = "rgba(28, 200, 138, 0.6)";
            $border_box = "#1cc88a";
        }

        $data['judul'] = 'Atur Posisi Tanda Tangan';
        $data['id_peminjaman'] = $id_peminjaman;
        $data['file_surat'] = $peminjaman['file_surat'];

        $data['ui'] = [
            'label' => $label_box,
            'color' => $warna_box,
            'border' => $border_box,
            'role'  => $role
        ];

        $this->view('ValidasiPeminjaman/TandaTangan', $data);
    }

    // 3. Proses Final Validasi Laboran (Menerima Koordinat & Stempel PDF)
    // Update method ini (Redirect-nya diubah)
    public function prosesAccLaboran()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataPost = [
                'id_peminjaman' => $_POST['id_peminjaman'],
                'page'          => $_POST['page_target'],
                'fatimah_x'     => $_POST['fatimah_x'],
                'fatimah_y'     => $_POST['fatimah_y'],
                'huzain_x'      => $_POST['huzain_x'],
                'huzain_y'      => $_POST['huzain_y']
            ];

            // Panggil Model (Stempel PDF)
            $this->model('Peminjaman_model')->validasiLaboranDouble($dataPost);


            // JANGAN langsung ke Detail, tapi ke halaman PREVIEW dulu
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/previewHasil/' . $dataPost['id_peminjaman']);
            exit;
        }
    }

    // --- [BARU] HALAMAN PREVIEW HASIL ---
    public function previewHasil($id_peminjaman)
    {
        // Cek Role (Hanya Laboran/Kalab)
        if (!in_array($_SESSION['id_role'], ['1', '2'])) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $data['judul'] = 'Preview Hasil Tanda Tangan';
        $data['peminjaman'] = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);

        // Jika data tidak ada
        if (!$data['peminjaman']) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('ValidasiPeminjaman/preview_hasil', $data); // View Baru
        $this->view('templates/footer');
    }

    // --- FITUR LAMA (Update Status Manual & Tolak) ---

    public function updateStatus()
    {
        if (!isset($_SESSION['login'])) {
            header("Location:" . BASEURL . "Login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_peminjaman = $_POST['id_peminjaman'];
            $status        = $_POST['status'];

            $pesan         = $_POST['pesan_penolakan'] ?? '';

            if ($this->model('Peminjaman_model')->updateStatusValidasi($id_peminjaman, $status, $pesan) > 0) {
                Flasher::setFlash('Berhasil', 'Status peminjaman berhasil diubah menjadi ' . ucfirst($status), '', 'success');
            } else {
                Flasher::setFlash('Info', 'Tidak ada perubahan status', '', 'info');
            }

            // Redirect balik ke detail agar user melihat perubahannya
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }
    }

    public function tolakPengembalian()
    {
        if (!isset($_SESSION['login'])) {
            header("Location:" . BASEURL . "Login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_peminjaman = $_POST['id_peminjaman'];
            $alasan        = $_POST['alasan_penolakan'];

            if ($this->model('Peminjaman_model')->simpanTolakPengembalian($id_peminjaman, $alasan) > 0) {
                Flasher::setFlash('Berhasil', 'Pengembalian ditolak. Status diubah menjadi Ditolak.', '', 'warning');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menyimpan penolakan.', '', 'danger');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }
    }
}
