<?php

class Login extends Controller {
    
    public function index(){  
        // Jika sudah login, lempar ke Beranda
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            header("Location:" . BASEURL. "Beranda");
            exit();
        }

        $data['judul'] = 'Login';
        $this->view('templates/header', $data);
        $this->view('Login/index');
        $this->view('templates/footer');
    }
        
    public function login(){
        $email = $_POST['email'];
        $password = $_POST['kata-sandi']; 
        
        $user = $this->model("User_model")->getUser($email, $password);

        if ($user) {
            // Login Sukses
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['email'] = $user['email']; 
            $_SESSION['id_role'] = $user['id_role'];
            $_SESSION['login'] = true;

            header("Location:" . BASEURL . "Beranda");
            exit;
        } else {
            // Login Gagal
            Flasher::setFlash('Login Gagal!', 'Email atau Password', 'salah.', 'danger');
            header("Location:" . BASEURL . "Login");
            exit;
        }
    }
}