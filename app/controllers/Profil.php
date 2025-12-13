<?php

class profil extends Controller{
    public function index(){

        $data['judul'] = 'Profil';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('Profil/index', $data);
        $this->view('templates/footer');
    }

    public function getUbah(){
        echo json_encode( $this->model('User_model')->getUbah($_POST['id_user']));
    }

    public function ubah(){

        if($this->model('User_model')->ubah($_POST) > 0){
                    Flasher::setFlash('Profil', 'berhasil', ' diUbah', 'success');
                    header('Location: '. BASEURL . 'Profil');
                    exit;
                }
        else{
            Flasher::setFlash('Profil', 'gagal', ' diUbah', 'danger');
            header('Location: '. BASEURL . 'Profil');
            exit;
        }
    }

}

?>