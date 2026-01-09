<?php
class ValidasiPeminjaman extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
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

        $data['peminjaman'] = $this->model('Peminjaman_model')->getDetailValidasiDataPeminjaman($id);

        $data['detail_barang'] = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id);

        if (!$data['peminjaman']) {
            Flasher::setFlash('Gagal', 'Data peminjaman tidak ditemukan', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('ValidasiPeminjaman/DetailPeminjaman', $data);
        $this->view('templates/footer');
    }

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
                Flasher::setFlash('Berhasil', 'Status peminjaman berhasil diubah', '', 'success');
            } else {
                Flasher::setFlash('Info', 'Tidak ada perubahan status', '', 'info');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }
    }
}
