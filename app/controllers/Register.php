<?php

class Register extends Controller
{

    public function index()
    {
        $data['judul'] = 'Register';
        $this->view('templates/header', $data);
        $this->view('Register/index');
        $this->view('templates/footer');
    }

    public function tambah()
    {
        // Model sekarang mengembalikan array atau kode status (-1, -2, -3, 0)
        $result = $this->model('User_model')->tambahUser($_POST);

        if (is_array($result) && $result['status'] === 1) {
            $userData = $this->model('User_model')->getUserEmailAndName($result['user_id']);

            $emailHelper = new EmailHelper();
            $emailSent = $emailHelper->sendVerificationEmail(
                $userData['email'],
                $userData['nama_user'],
                $result['token']
            );

            if ($emailSent) {
                Flasher::setFlash('Akun', 'berhasil', 'dibuat. Silakan cek email Anda untuk verifikasi.', 'success');
            } else {
                Flasher::setFlash('Akun', 'berhasil dibuat', 'namun email verifikasi gagal dikirim. Hubungi admin.', 'warning');
            }

            header('Location: ' . BASEURL . 'Login');
        } elseif ($result === -1) {
            Flasher::setFlash('Gagal', 'Email sudah digunakan.', 'Gunakan email lain.', 'danger');
            header('Location: ' . BASEURL . 'Register');
        } elseif ($result === -2) {
            Flasher::setFlash('Gagal', 'Password tidak cocok.', 'Cek konfirmasi password.', 'danger');
            header('Location: ' . BASEURL . 'Register');
        } else {
            Flasher::setFlash('Gagal', 'Kesalahan Sistem/Upload Foto.', '', 'danger');
            header('Location: ' . BASEURL . 'Register');
        }
        exit;
    }
}