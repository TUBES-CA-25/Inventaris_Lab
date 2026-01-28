<?php
class Logout extends Controller
{
    public function index()
    {
        // Session sudah di-start di index.php, tidak perlu session_start() lagi
        session_unset();
        session_destroy();
        header("Location:" . BASEURL . "Login");
        exit;
    }
}