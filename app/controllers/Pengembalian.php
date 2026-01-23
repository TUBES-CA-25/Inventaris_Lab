<?php

class Pengembalian extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }

    public function index()
    {
        // PROTEKSI: Jika bukan Korlab(3) atau Asisten(4), tendang keluar
        if ($_SESSION['id_role'] != 3 && $_SESSION['id_role'] != 4) {
            header('Location: ' . BASEURL . 'Beranda');
            exit;
        }

        $data['judul'] = 'Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Ambil SEMUA data peminjaman untuk semua role
        $data['riwayat'] = $this->model('Pengembalian_model')->getAllRiwayatForPetugas();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Ambil data satu baris spesifik untuk detail
        $data['detail'] = $this->model('Pengembalian_model')->getRiwayatById($id);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/detil', $data);
        $this->view('templates/footer');
    }

    public function input($id)
    {
        // PROTEKSI: Jika bukan Korlab(3) atau Asisten(4), tendang keluar
        if ($_SESSION['id_role'] != 3 && $_SESSION['id_role'] != 4) {
            header('Location: ' . BASEURL . 'Beranda');
            exit;
        }

        $data['judul'] = 'Input Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Ambil data peminjaman berdasarkan ID
        $data['peminjaman'] = $this->model('Pengembalian_model')->getRiwayatById($id);

        // Ambil semua jenis barang untuk dropdown
        $data['jenis_barang'] = $this->model('Pengembalian_model')->getAllJenisBarang();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/input', $data);
        $this->view('templates/footer');
    }

    public function proses_input()
    {
        // PROTEKSI: Jika bukan Korlab(3) atau Asisten(4), tendang keluar
        if ($_SESSION['id_role'] != 3 && $_SESSION['id_role'] != 4) {
            header('Location: ' . BASEURL . 'Beranda');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . 'Pengembalian');
            exit;
        }

        // Proses input pengembalian
        $result = $this->model('Pengembalian_model')->inputPengembalianAsisten($_POST);

        if ($result > 0) {
            Flasher::setFlash('Pengembalian berhasil dicatat!', 'success');
        } else {
            Flasher::setFlash('Pengembalian gagal dicatat!', 'danger');
        }

        header('Location: ' . BASEURL . 'Pengembalian');
        exit;
    }

    public function simpan()
    {
        $this->auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . 'Pengembalian');
            exit;
        }

        if (isset($_FILES['bukti_pengembalian']) && $_FILES['bukti_pengembalian']['error'] === 0) {
            $uploadDir = '../public/uploads/pengembalian/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['bukti_pengembalian']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array($ext, $allowed)) {
                $_POST['bukti_pengembalian'] =
                    uniqid() . '_' . $_FILES['bukti_pengembalian']['name'];

                move_uploaded_file(
                    $_FILES['bukti_pengembalian']['tmp_name'],
                    $uploadDir . $_POST['bukti_pengembalian']
                );
            }
        }

        $_POST['id_user'] = $_SESSION['id_user'];

        if ($this->model('Pengembalian_model')->tambahPengembalian($_POST) > 0) {
            Flasher::setFlash('Data pengembalian berhasil disimpan!', 'success');
        } else {
            Flasher::setFlash('Data pengembalian gagal disimpan!', 'danger');
        }

        header('Location: ' . BASEURL . 'Pengembalian');
        exit;
    }

    public function getUbah()
    {
        $data = $this->model('Pengembalian_model')->getUbahPengembalian(IdObfuscator::decode($_POST['id_pengembalian']));
        if ($data) {
            $data['id_pengembalian'] = IdObfuscator::encode($data['id_pengembalian']);
        }
        echo json_encode($data);
    }

    public function ubahPengembalian()
    {
        $_POST['id_pengembalian'] = IdObfuscator::decode($_POST['id_pengembalian']);
        if ($this->model('Pengembalian_model')->updatePengembalian($_POST) > 0) {
            Flasher::setFlash('Data berhasil diubah.', 'success');
        } else {
            Flasher::setFlash('Data gagal diubah.', 'danger');
        }

        header('Location: ' . BASEURL . 'Pengembalian/index');
        exit;
    }
}