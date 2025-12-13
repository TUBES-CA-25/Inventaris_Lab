<?php

class KelolaAkun extends Controller {
    
    public function index() {
        $data['judul'] = 'Kelola Akun';
        
        $userModel = $this->model('User_model');
        $data['dataTampilUser']= $userModel->tampilUser();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', data: $data);
        $this->view('Kelola_akun/index', $data);
        $this->view('templates/footer');
    }


    public function hapusUser($id_user){
        
        if($this->model('User_model')->hapusUser($id_user) > 0){
            Flasher::setFlash('User', 'berhasil', ' dihapus', 'success');
            header('Location: '. BASEURL . 'KelolaAkun');
            exit;
        }else{
            Flasher::setFlash('User', 'gagal', ' dihapus', 'danger');
            header('Location: '. BASEURL . 'KelolaAkun');
            exit;
        }
    }

    public function getUbah(){
        echo json_encode( $this->model('User_model')->getUbah($_POST['id_user']));
    }

    public function ubahUser(){
            if($this->model('User_model')->updateUser($_POST) > 0){
                Flasher::setFlash('Data User', 'berhasil', ' diUbah', 'success');
                header('Location: '. BASEURL . 'KelolaAkun');
                exit;
            } else {
            Flasher::setFlash('User_model', 'gagal', ' diUbah', 'danger');
            header('Location: '. BASEURL . 'KelolaAkun');
            exit;
        }
    }

    public function cari(){
        $data['judul'] = 'Data User';
        
        // Mengambil data kondisi barang dari model
        $tampilUser = $this->model('User_model');
        $data['dataTampilUser']= $tampilUser->cariUser();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Memanggil view transaksi barang
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', data: $data);
        $this->view('Kelola_akun/index', $data);
        $this->view('templates/footer');
    }

    public function getRole(){
        echo json_encode( $this->model('User_model')->getRole($_POST['id_user']));
    }

    public function ubahRole()
    {      
        $ubahRole =  $this->model('User_model')->ubahRole($_POST);   
        if($ubahRole > 0){
        Flasher::setFlash('Role', 'berhasil', ' diUbah', 'success');
        header('Location: '. BASEURL . 'KelolaAkun');
        exit;
    } else {
    Flasher::setFlash('Role', 'gagal', ' diUbah', 'danger');
    header('Location: '. BASEURL . 'KelolaAkun');
    exit;
} 
}
}