<?php

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'core/Flasher.php';
require_once 'core/IdObfuscator.php';
require_once 'vendor/phpqrcode/qrlib.php';

require_once 'config/config.php';

// Global Session Timeout Logic
if (isset($_SESSION['login'])) {
    // Cek apakah ada aktivitas terakhir
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        // Jika durasi inaktivitas > batas waktu, logout
        if (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT_DURATION) {
            session_unset();
            session_destroy();
            // Redirect ke login dengan pesan timeout (opsional tambah parameter msg=timeout)
            header("Location: " . BASEURL . "Login");
            exit;
        }
    }
    // Update aktivitas terakhir setiap kali init.php dipanggil (setiap request)
    $_SESSION['LAST_ACTIVITY'] = time();
}