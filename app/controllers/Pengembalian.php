<?php

/**
 * Controller Pengembalian
 * * Mengelola proses pengembalian barang dan pemantauan status (Tepat waktu/Terlambat).
 */
class Pengembalian extends Controller
{
    private function auth()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

    public function __construct()
    {
        // 1. Gatekeeper: Cek Login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }

    public function index()
    {
        $this->auth();

        $data['judul'] = 'Pengembalian Barang';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        $data['pengembalian'] = $this->pengembalianModel->getAllPengembalian();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/from', $data);
        $this->view('templates/footer');
    }

    public function Riwayat()
    {
        $this->auth();

        $data['judul'] = 'Riwayat Pengembalian';
        $data['id_user'] = $_SESSION['id_user'];
        $data['pengembalian'] =
            $this->model('Pengembalian_model')->getAllPengembalian();
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Pengembalian/index', $data);
        $this->view('templates/footer');
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
        echo json_encode(
            $this->model('Pengembalian_model')
                 ->getUbahPengembalian($_POST['id_pengembalian'])
        );
    }

    public function ubahPengembalian()
    {
        if ($this->model('Pengembalian_model')->updatePengembalian($_POST) > 0) {
            Flasher::setFlash('Data berhasil diubah.', 'success');
        } else {
            Flasher::setFlash('Data gagal diubah.', 'danger');
        }

        header('Location: ' . BASEURL . 'Pengembalian/index');
        exit;
    }
}