<?php

class KelolaAkun extends Controller {
    
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        $this->userModel = $this->model('User_model');
    }

    public function index() {
        $data['judul'] = 'Kelola Akun';
        $data['dataTampilUser'] = $this->userModel->tampilUser();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->userModel->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Kelola_akun/index', $data);
        $this->view('templates/footer');
    }

    public function hapusUser($id_user){
        $id_user = IdObfuscator::decode($id_user);
        if (!$id_user) {
            header('Location: ' . BASEURL . 'KelolaAkun');
            exit;
        }
        if($this->userModel->hapusUser($id_user) > 0){
            Flasher::setFlash('User', 'berhasil', 'dihapus', 'success');
        }else{
            Flasher::setFlash('User', 'gagal', 'dihapus', 'danger');
        }
        header('Location: '. BASEURL . 'KelolaAkun');
        exit;
    }

    public function getUbah(){
        $data = $this->userModel->getUbah(IdObfuscator::decode($_POST['id_user']));
        if ($data) {
            $data['id_user'] = IdObfuscator::encode($data['id_user']);
        }
        echo json_encode($data);
    }

    public function ubahUser(){
        // Cek jika update berhasil (rowCount >= 0 karena bisa saja user simpan tanpa ubah data)
        // Disarankan model return rowCount, tapi kadang 0 jika tidak ada perubahan
        // Jadi logicnya: Asal tidak Error/False
        
        if(isset($_POST['id_user'])) {
            $_POST['id_user'] = IdObfuscator::decode($_POST['id_user']);
        }
        
        if($this->userModel->updateUser($_POST) >= 0){
            Flasher::setFlash('Data User', 'berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('Data User', 'gagal', 'diubah (Gagal Upload/DB)', 'danger');
        }
        header('Location: '. BASEURL . 'KelolaAkun');
        exit;
    }

    public function cari(){
        $data['judul'] = 'Data User';
        $data['dataTampilUser'] = $this->userModel->cariUser();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->userModel->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Kelola_akun/index', $data);
        $this->view('templates/footer');
    }

    public function getRole(){
        $data = $this->userModel->getRole(IdObfuscator::decode($_POST['id_user']));
        if ($data) {
             // Assuming getRole returns user data with 'id_user' or related
             // If it returns role list, maybe no need. 
             // But usually it returns the user's current role.
             if(isset($data['id_user'])) $data['id_user'] = IdObfuscator::encode($data['id_user']);
        }
        echo json_encode($data);
    }

    public function ubahRole() {      
        $_POST['id_user'] = IdObfuscator::decode($_POST['id_user']);
        if($this->userModel->ubahRole($_POST) > 0){
            Flasher::setFlash('Role', 'berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('Role', 'gagal', 'diubah', 'danger');
        } 
        header('Location: '. BASEURL . 'KelolaAkun');
        exit;
    }
}