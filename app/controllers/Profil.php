<?php

class Profil extends Controller {
    
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        $this->userModel = $this->model('User_model');
    }

    public function index(){
        $data['judul'] = 'Profil';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->userModel->profile($data);

        $this->view('templates/header', $data);
        $this->view('Profil/index', $data);
        $this->view('templates/footer');
    }

    public function getUbah(){
        echo json_encode($this->userModel->getUbah($_POST['id_user']));
    }

    public function ubah(){
        if($this->userModel->ubah($_POST) >= 0){
             Flasher::setFlash('Profil', 'berhasil', 'diubah', 'success');
        } else {
             Flasher::setFlash('Profil', 'gagal', 'diubah', 'danger');
        }
        header('Location: '. BASEURL . 'Profil');
        exit;
    }
}