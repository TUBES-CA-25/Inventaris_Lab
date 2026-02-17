<?php
class ValidasiPeminjaman extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION))
            session_start();

        if (!isset($_SESSION['id_user']) && in_array($_SESSION['id_role'], [ROLE_KALAB, ROLE_LABORAN])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Validasi Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $data['peminjaman'] = $this->model('ValidasiPeminjaman_model')->getValidasiGabungan();

        $data['total_disetujui'] = $this->model('ValidasiPeminjaman_model')->hitungStatus('disetujui');
        $data['total_diproses'] = $this->model('ValidasiPeminjaman_model')->hitungStatus('diproses');
        $data['total_ditolak'] = $this->model('ValidasiPeminjaman_model')->hitungStatus('ditolak');
        $data['total_kembali'] = $this->model('ValidasiPeminjaman_model')->hitungStatus('dikembalikan');

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

        $data['peminjaman'] = $this->model('ValidasiPeminjaman_model')->getDetailValidasiDataPeminjaman($id);
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
        $this->view('templates/footer1', $data);
    }


    public function accKalab()
    {
        if ($_SESSION['id_role'] != ROLE_KALAB) {
            Flasher::setFlash('Akses Ditolak', 'Hanya Kepala Lab yang bisa menyetujui tahap ini.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_encoded = $_POST['id_peminjaman'];
            $id_decoded = IdObfuscator::decode($id_encoded);

            if ($this->model('ValidasiPeminjaman_model')->validasiKalab($id_decoded) > 0) {
                Flasher::setFlash('Berhasil', 'Validasi Tahap 1 (Kepala Lab) disetujui.', '', 'success');
            } else {
                Flasher::setFlash('Info', 'Data sudah disetujui sebelumnya atau tidak ada perubahan.', '', 'info');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_encoded);
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

        if ($role != ROLE_KALAB && $role != ROLE_LABORAN) {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki wewenang tanda tangan.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }

        $peminjaman = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman);

        if ($role == ROLE_KALAB) {
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
            'role' => $role
        ];

        $this->view('ValidasiPeminjaman/TandaTangan', $data);
    }

    public function prosesAccLaboran()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataPost = [
                'id_peminjaman' => IdObfuscator::decode($_POST['id_peminjaman']),
                'fatimah_page' => $_POST['fatimah_page'],
                'huzain_page' => $_POST['huzain_page'],
                'fatimah_x' => $_POST['fatimah_x'],
                'fatimah_y' => $_POST['fatimah_y'],
                'huzain_x' => $_POST['huzain_x'],
                'huzain_y' => $_POST['huzain_y']
            ];

            $this->model('ValidasiPeminjaman_model')->validasiLaboranDouble($dataPost);

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
        if (!in_array($_SESSION['id_role'], [ROLE_KALAB, ROLE_LABORAN])) {
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
            $pesan = $_POST['pesan_penolakan'] ?? '';

            if ($this->model('ValidasiPeminjaman_model')->updateStatusValidasi($id_decoded, $status, $pesan) > 0) {
                Flasher::setFlash('Berhasil', 'Status peminjaman berhasil diubah menjadi ' . ucfirst($status), '', 'success');
            } else {
                Flasher::setFlash('Info', 'Status diperbarui.', '', 'info');
            }

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
            $alasan = $_POST['alasan_penolakan'];

            if ($this->model('Pengembalian_model')->simpanTolakPengembalian($id_peminjaman, $alasan) > 0) {
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
        $id_peminjaman_decoded = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman_decoded) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $hasilOtomatis = $this->model('Peminjaman_model')->otomatisasiPilihBarang($id_peminjaman_decoded);

        if ($hasilOtomatis == 0) {
            Flasher::setFlash('Gagal', 'Stok barang fisik tidak mencukupi untuk disetujui otomatis.', '', 'danger');
            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }

        $this->model('ValidasiPeminjaman_model')->finalisasiValidasi($id_peminjaman_decoded);

        $peminjaman = $this->model('Peminjaman_model')->getDetailPeminjaman($id_peminjaman_decoded);
        if ($peminjaman) {
            $fileName = $peminjaman['file_surat'];
            $pathBackup = __DIR__ . '/../../public/files/surat-peminjaman/backup_' . $fileName;

            if (file_exists($pathBackup)) {
                unlink($pathBackup);
            }
        }

        Flasher::setFlash('Berhasil', 'Peminjaman disetujui. Barang telah dialokasikan otomatis.', '', 'success');
        header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
        exit;
    }
    public function kirimNotifikasi()
    {
        if (!in_array($_SESSION['id_role'], [ROLE_KALAB, ROLE_LABORAN])) {
            header('Location: ' . BASEURL . 'ValidasiPeminjaman');
            exit;
        }

        $jumlahTerikirm = $this->model('Notification_model')->prosesNotifikasiOtomatis();

        if ($jumlahTerikirm > 0) {
            Flasher::setFlash('Berhasil', "Total $jumlahTerikirm email notifikasi berhasil dikirim.", '', 'success');
        } else {
            Flasher::setFlash('Info', 'Tidak ada peminjaman yang perlu dinotifikasi saat ini.', '', 'info');
        }

        header('Location: ' . BASEURL . 'ValidasiPeminjaman');
        exit;
    }
}
