<?php

class Register extends Controller {
    public function index(){
        $data['judul'] = 'Register';
        $this->view('templates/header', $data);
        $this->view('Register/index');
        $this->view('templates/footer');
    }

    public function tambah(){
        if($this->model('User_model')->tambahUser($_POST) > 0){
            Flasher::setFlash('Akun', 'berhasil', ' ditambahkan', 'success');
            header('Location: '. BASEURL . '/');
            exit;
        } else{
            Flasher::setFlash('Akun', 'gagal', ' ditambahkan', 'danger');
            header('Location: '. BASEURL . 'Register');
            exit;
        }
    }
        
}