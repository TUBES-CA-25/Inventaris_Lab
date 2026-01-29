<?php

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'core/Flasher.php';
require_once 'core/IdObfuscator.php';
require_once 'core/ErrorHelper.php';
require_once 'core/EmailHelper.php';
require_once 'vendor/phpqrcode/qrlib.php';
require_once '../vendor/autoload.php';

require_once 'config/config.php';

// ================= GLOBAL ERROR HANDLERS =================

// Set error reporting based on environment
if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Global Exception Handler
set_exception_handler(function ($exception) {
    // Set error session
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '500';
    $_SESSION['error_message'] = $exception->getMessage();
    $_SESSION['error_code'] = $exception->getCode();
    $_SESSION['error_file'] = $exception->getFile();
    $_SESSION['error_line'] = $exception->getLine();
    $_SESSION['error_trace'] = $exception->getTraceAsString();

    // Redirect ke 500 error page
    header("Location: " . BASEURL . "ErrorPage/serverError");
    exit;
});

// Global Error Handler - HANYA tangkap error SERIUS
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // Jangan handle error yang di-suppress dengan @
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // Hanya tangkap error SERIUS (Fatal, Parse, Core, Compile, User Error)
    // TIDAK tangkap Warning, Notice, Deprecated
    $serious_errors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];

    if (!in_array($errno, $serious_errors)) {
        // Bukan error serius, biarkan PHP handle secara normal
        return false;
    }

    // Set error session untuk error serius
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '500';
    $_SESSION['error_message'] = $errstr;
    $_SESSION['error_code'] = $errno;
    $_SESSION['error_file'] = $errfile;
    $_SESSION['error_line'] = $errline;

    // Redirect ke 500 error page
    header("Location: " . BASEURL . "ErrorPage/serverError");
    exit;
});

// Shutdown function untuk catch fatal errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $_SESSION['has_error'] = true;
        $_SESSION['error_type'] = '500';
        $_SESSION['error_message'] = $error['message'];
        $_SESSION['error_code'] = $error['type'];
        $_SESSION['error_file'] = $error['file'];
        $_SESSION['error_line'] = $error['line'];

        header("Location: " . BASEURL . "ErrorPage/serverError");
        exit;
    }
});

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