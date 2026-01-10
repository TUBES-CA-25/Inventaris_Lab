<?php

class Register extends Controller {
    
    public function index(){
        $data['judul'] = 'Register';
        $this->view('templates/header', $data);
        $this->view('Register/index');
        $this->view('templates/footer');
    }

    public function tambah(){
        // Model sekarang mengembalikan kode status (-1, -2, -3, 1)
        $status = $this->model('User_model')->tambahUser($_POST);

        if($status === 1){
            Flasher::setFlash('Akun', 'berhasil', 'ditambahkan. Silakan Login.', 'success');
            header('Location: '. BASEURL . 'Login'); // Redirect ke Login, bukan Root
        } elseif ($status === -1) {
            Flasher::setFlash('Gagal', 'Email sudah digunakan.', 'Gunakan email lain.', 'danger');
            header('Location: '. BASEURL . 'Register');
        } elseif ($status === -2) {
            Flasher::setFlash('Gagal', 'Password tidak cocok.', 'Cek konfirmasi password.', 'danger');
            header('Location: '. BASEURL . 'Register');
        } else {
            Flasher::setFlash('Gagal', 'Kesalahan Sistem/Upload Foto.', '', 'danger');
            header('Location: '. BASEURL . 'Register');
        }
        exit;
    }
}