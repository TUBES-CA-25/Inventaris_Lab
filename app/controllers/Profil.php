<?php

class Profil extends Controller
{

    private $userModel;

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        $this->userModel = $this->model('User_model');
    }

    public function index()
    {
        $data['judul'] = 'Profil';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->userModel->profile($data);

        $this->view('templates/header', $data);
        $this->view('Profil/index', $data);
        $this->view('templates/footer');
    }

    public function getUbah()
    {
        echo json_encode($this->userModel->getUbah($_POST['id_user']));
    }

    public function ubah()
    {
        if ($this->userModel->ubah($_POST) >= 0) {
            Flasher::setFlash('Profil', 'berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('Profil', 'gagal', 'diubah', 'danger');
        }
        header('Location: ' . BASEURL . 'Profil');
        exit;
    }

    public function updateTTD()
    {
        if (!isset($_SESSION['id_user']) && in_array($_SESSION['id_role'], ['1', '2'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        if (isset($_FILES['ttd_kalab']) || isset($_FILES['ttd_laboran'])) {

            $hasil = $this->userModel->updateTTDSpesifik($_FILES);

            if ($hasil > 0) {
                Flasher::setFlash('Tanda Tangan', 'berhasil', 'diperbarui', 'success');
            } elseif ($hasil === -1) {
                Flasher::setFlash('Tanda Tangan', 'gagal', 'Format file wajib PNG', 'danger');
            } else {
                Flasher::setFlash('Tanda Tangan', 'gagal', 'diperbarui (Cek izin folder/file)', 'warning');
            }
        }

        header('Location: ' . BASEURL . 'Profil');
        exit;
    }

    public function gantiPassword()
    {
        if ($this->model('User_model')->gantiPasswordUser($_POST) > 0) {
            Flasher::setFlash('Password', 'berhasil', 'diubah', 'success');
        } else {
            // Pesan error akan diatur oleh model (misal: password lama salah)
            Flasher::setFlash('Password', 'gagal', 'diubah', 'danger');
        }
        header('Location: ' . BASEURL . 'Profil');
        exit;
    }

}