<?php

/**
 * Error Helper Functions
 * Fungsi helper untuk memudahkan trigger error pages dari mana saja
 */

/**
 * Trigger 404 Not Found Error
 * @param string $message Custom error message
 */
function trigger404($message = 'Halaman tidak ditemukan')
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '404';
    $_SESSION['error_message'] = $message;
    header("Location: " . BASEURL . "ErrorPage/notFound");
    exit;
}

/**
 * Trigger 403 Access Denied Error
 * @param string $message Custom error message
 */
function trigger403($message = 'Akses ditolak')
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '403';
    $_SESSION['error_message'] = $message;
    header("Location: " . BASEURL . "ErrorPage/accessDenied");
    exit;
}

/**
 * Trigger 401 Unauthorized Error (belum login)
 * @param string $message Custom error message
 */
function trigger401($message = 'Anda harus login terlebih dahulu')
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '401';
    $_SESSION['error_message'] = $message;
    header("Location: " . BASEURL . "ErrorPage/unauthorized");
    exit;
}

/**
 * Trigger 500 Internal Server Error
 * @param string $message Custom error message
 * @param Exception|null $exception Exception object untuk detail
 */
function trigger500($message = 'Terjadi kesalahan server', $exception = null)
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = '500';
    $_SESSION['error_message'] = $message;

    if ($exception instanceof Exception) {
        $_SESSION['error_code'] = $exception->getCode();
        $_SESSION['error_file'] = $exception->getFile();
        $_SESSION['error_line'] = $exception->getLine();
        $_SESSION['error_trace'] = $exception->getTraceAsString();
    }

    header("Location: " . BASEURL . "ErrorPage/serverError");
    exit;
}

/**
 * Trigger Database Error
 * @param string $message Custom error message
 * @param PDOException|null $exception PDO Exception object
 */
function triggerDatabaseError($message = 'Kesalahan koneksi database', $exception = null)
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = 'database';
    $_SESSION['error_message'] = $message;

    if ($exception instanceof PDOException) {
        $_SESSION['error_code'] = $exception->getCode();
        $_SESSION['error_file'] = $exception->getFile();
        $_SESSION['error_line'] = $exception->getLine();
    }

    header("Location: " . BASEURL . "ErrorPage/databaseError");
    exit;
}

/**
 * Trigger Generic Error
 * @param string $code Error code (e.g., 400, 503)
 * @param string $title Error title
 * @param string $message Error message
 */
function triggerError($code, $title, $message)
{
    $_SESSION['has_error'] = true;
    $_SESSION['error_type'] = $code;
    $_SESSION['error_code'] = $code;
    $_SESSION['error_title'] = $title;
    $_SESSION['error_message'] = $message;
    header("Location: " . BASEURL . "ErrorPage/index");
    exit;
}

/**
 * Contoh penggunaan di Controller atau Model:
 * 
 * // 404 - Halaman tidak ditemukan
 * trigger404('Data dengan ID ' . $id . ' tidak ditemukan');
 * 
 * // 403 - Akses ditolak
 * if ($user_role !== 'admin') {
 *     trigger403('Hanya admin yang dapat mengakses halaman ini');
 * }
 * 
 * // 401 - Belum login
 * if (!isset($_SESSION['login'])) {
 *     trigger401('Silakan login terlebih dahulu');
 * }
 * 
 * // 500 - Server error
 * try {
 *     // risky code
 * } catch (Exception $e) {
 *     trigger500('Gagal memproses data', $e);
 * }
 * 
 * // Database error
 * try {
 *     $db->query("...");
 * } catch (PDOException $e) {
 *     triggerDatabaseError('Gagal mengakses database', $e);
 * }
 * 
 * // Generic error
 * triggerError('503', 'Service Unavailable', 'Layanan sedang dalam perbaikan');
 */
