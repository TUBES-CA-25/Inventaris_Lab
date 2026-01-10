<?php
class Logout extends Controller {
    public function index(){
        session_start(); // Pastikan session dimulai sebelum dihancurkan
        session_unset();
        session_destroy();
        header("Location:" . BASEURL . "Login");
        exit;
    }
}