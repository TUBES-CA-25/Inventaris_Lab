<?php
class ValidasiPeminjaman extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();

        if (!isset($_SESSION['id_user']) && in_array($_SESSION['id_role'], ['1', '2'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        else if (!isset($_SESSION['id_user']) && in_array($_SESSION['id_role'], ['1', '2'])) {
            header('Location: ' . BASEURL . 'Beranda');
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
        $id = IdObfuscator::decode($id);
        if (!$id) {
            Flasher::setFlash('Gagal', 'ID tidak valid', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }
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
        $data['status_Kembali'] = isset($data['peminjaman']['status_pengembalian']) ? $data['peminjaman']['status_pengembalian'] : '-';

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('ValidasiPeminjaman/DetailPeminjaman', $data);
        // $this->view('templates/footer', $data);
    }

    public function accKalab()
    {
        if ($_SESSION['id_role'] != '1') {
            Flasher::setFlash('Akses Ditolak', 'Hanya Kepala Lab yang bisa menyetujui tahap ini.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = IdObfuscator::decode($_POST['id_peminjaman']);

            if ($this->model('Peminjaman_model')->validasiKalab($id) > 0) {
                Flasher::setFlash('Berhasil', 'Validasi Tahap 1 (Kepala Lab) disetujui.', '', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan atau data sudah disetujui sebelumnya.', '', 'warning');
            }
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id);
            exit;
        }
    }

    public function viewValidasiPosisi($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }
        $role = $_SESSION['id_role'];

        if ($role != '1' && $role != '2') {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki wewenang tanda tangan.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }

        $peminjaman = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);

        if ($role == '1') {
            $label_box = "TTD Kepala Lab (Huzain)";
            $warna_box = "rgba(78, 115, 223, 0.6)";
            $border_box = "#4e73df";
        } else {
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

    public function prosesAccLaboran()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataPost = [
                'id_peminjaman' => IdObfuscator::decode($_POST['id_peminjaman']),
                'fatimah_page'  => $_POST['fatimah_page'],
                'huzain_page'   => $_POST['huzain_page'],
                'fatimah_x'     => $_POST['fatimah_x'],
                'fatimah_y'     => $_POST['fatimah_y'],
                'huzain_x'      => $_POST['huzain_x'],
                'huzain_y'      => $_POST['huzain_y']
            ];

            $this->model('Peminjaman_model')->validasiLaboranDouble($dataPost);

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/previewHasil/' . IdObfuscator::encode($dataPost['id_peminjaman']));
            exit;
        }
    }

    public function previewHasil($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }
        if (!in_array($_SESSION['id_role'], ['1', '2'])) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $data['judul'] = 'Preview Hasil Tanda Tangan';
        $data['peminjaman'] = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);

        $this->view('templates/header', $data);
        $this->view('ValidasiPeminjaman/preview_hasil', $data);
        $this->view('templates/footer');
    }

    public function updateStatus()
    {
        if (!isset($_SESSION['login'])) {
            header("Location:" . BASEURL . "Login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_encoded = $_POST['id_peminjaman'];
            $id_decoded = IdObfuscator::decode($id_encoded);

            $status = $_POST['status'];
            $pesan  = $_POST['pesan_penolakan'] ?? '';

            if ($this->model('Peminjaman_model')->updateStatusValidasi($id_decoded, $status, $pesan) > 0) {
                Flasher::setFlash('Berhasil', 'Status peminjaman berhasil diubah menjadi ' . ucfirst($status), '', 'success');
            } else {
                // Info saja, mungkin status sudah sama sebelumnya
                Flasher::setFlash('Info', 'Status diperbarui.', '', 'info');
            }

            // 4. REDIRECT URL (Pakai ID Encoded / String Acak)
            // Kita kembalikan user ke halaman detail dengan ID yang aman
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_encoded);
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
            $id_peminjaman = IdObfuscator::decode($_POST['id_peminjaman']);
            $alasan        = $_POST['alasan_penolakan'];

            if ($this->model('Peminjaman_model')->simpanTolakPengembalian($id_peminjaman, $alasan) > 0) {
                Flasher::setFlash('Berhasil', 'Pengembalian ditolak. Status diubah menjadi Ditolak.', '', 'warning');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menyimpan penolakan.', '', 'danger');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $_POST['id_peminjaman']);
            exit;
        }
    }

    public function selesaiValidasi($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $peminjaman = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);
        $this->model('Peminjaman_model')->finalisasiValidasi($id_peminjaman) > 0;

        if ($peminjaman) {
            $fileName = $peminjaman['file_surat'];
            $pathBackup = __DIR__ . '/../../public/files/surat-peminjaman/backup_' . $fileName;

            if (file_exists($pathBackup)) {
                unlink($pathBackup);
            }
        }

        Flasher::setFlash('Berhasil', 'Proses validasi selesai. Dokumen telah disimpan.', '', 'success');
        header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
        exit;
    }
}