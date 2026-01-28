<?php

class ErrorPage extends Controller
{
    /**
     * Validate error session - error pages hanya bisa diakses jika ada error
     */
    private function validateErrorSession($requiredType = null)
    {
        if (!isset($_SESSION['has_error']) || $_SESSION['has_error'] !== true) {
            // Tidak ada error session - redirect ke beranda
            header("Location: " . BASEURL . "Beranda");
            exit;
        }

        // Jika required type specified, validasi type-nya
        if ($requiredType !== null && isset($_SESSION['error_type'])) {
            if ($_SESSION['error_type'] !== $requiredType) {
                // Error type tidak sesuai - redirect ke beranda
                header("Location: " . BASEURL . "Beranda");
                exit;
            }
        }
    }

    /**
     * Clear error session setelah ditampilkan
     */
    private function clearErrorSession()
    {
        unset($_SESSION['has_error']);
        unset($_SESSION['error_type']);
        unset($_SESSION['error_message']);
        unset($_SESSION['error_code']);
        unset($_SESSION['error_file']);
        unset($_SESSION['error_line']);
        unset($_SESSION['error_trace']);
    }

    /**
     * Display 404 - Page Not Found error
     */
    public function notFound()
    {
        $this->validateErrorSession('404');

        http_response_code(404);
        $data['judul'] = '404 - Halaman Tidak Ditemukan';
        $data['error_message'] = $_SESSION['error_message'] ?? 'Halaman yang Anda cari tidak ditemukan';

        $this->view('Error/404', $data);
        $this->clearErrorSession();
    }

    /**
     * Display 403 - Access Denied error
     */
    public function accessDenied()
    {
        $this->validateErrorSession('403');

        http_response_code(403);
        $data['judul'] = '403 - Akses Ditolak';
        $data['error_message'] = $_SESSION['error_message'] ?? 'Anda tidak memiliki izin untuk mengakses halaman ini';

        $this->view('Error/403', $data);
        $this->clearErrorSession();
    }

    /**
     * Display 401 - Unauthorized (belum login)
     */
    public function unauthorized()
    {
        $this->validateErrorSession('401');

        http_response_code(401);
        $data['judul'] = '401 - Unauthorized';
        $data['error_message'] = $_SESSION['error_message'] ?? 'Anda harus login terlebih dahulu';

        $this->view('Error/401', $data);
        $this->clearErrorSession();
    }

    /**
     * Display 500 - Internal Server Error
     */
    public function serverError()
    {
        $this->validateErrorSession('500');

        http_response_code(500);
        $data['judul'] = '500 - Kesalahan Server';
        $data['error_details'] = '';

        // Tampilkan detail error jika dalam mode development
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $details = [];
            if (isset($_SESSION['error_message'])) {
                $details[] = "Error: " . $_SESSION['error_message'];
            }
            if (isset($_SESSION['error_file']) && isset($_SESSION['error_line'])) {
                $details[] = "File: " . $_SESSION['error_file'] . " (Line: " . $_SESSION['error_line'] . ")";
            }
            if (isset($_SESSION['error_trace'])) {
                $details[] = "Stack Trace:\n" . $_SESSION['error_trace'];
            }
            $data['error_details'] = implode("\n", $details);
        }

        $this->view('Error/500', $data);
        $this->clearErrorSession();
    }

    /**
     * Display Database Connection Error
     */
    public function databaseError()
    {
        $this->validateErrorSession('database');

        http_response_code(500);
        $data['judul'] = 'Kesalahan Database';
        $data['db_error'] = '';

        // Tampilkan detail error jika dalam mode development
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $details = [];
            if (isset($_SESSION['error_message'])) {
                $details[] = "Database Error: " . $_SESSION['error_message'];
            }
            if (isset($_SESSION['error_code'])) {
                $details[] = "Error Code: " . $_SESSION['error_code'];
            }
            if (isset($_SESSION['error_file']) && isset($_SESSION['error_line'])) {
                $details[] = "File: " . $_SESSION['error_file'] . " (Line: " . $_SESSION['error_line'] . ")";
            }
            $data['db_error'] = implode("\n", $details);
        }

        $this->view('Error/database', $data);
        $this->clearErrorSession();
    }

    /**
     * Display generic error page
     */
    public function index()
    {
        $this->validateErrorSession();

        $code = $_SESSION['error_code'] ?? '500';
        http_response_code((int) $code);

        $data['judul'] = $code . ' - Error';
        $data['error_code'] = $code;
        $data['error_title'] = $_SESSION['error_title'] ?? 'Terjadi Kesalahan';
        $data['error_message'] = $_SESSION['error_message'] ?? 'Maaf, terjadi kesalahan yang tidak terduga.';
        $data['error_details'] = '';

        // Tampilkan detail error jika dalam mode development
        if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE === true) {
            $details = [];
            if (isset($_SESSION['error_file']) && isset($_SESSION['error_line'])) {
                $details[] = "File: " . $_SESSION['error_file'] . " (Line: " . $_SESSION['error_line'] . ")";
            }
            if (isset($_SESSION['error_trace'])) {
                $details[] = "Stack Trace:\n" . $_SESSION['error_trace'];
            }
            if (!empty($details)) {
                $data['error_details'] = implode("\n", $details);
            }
        }

        $this->view('Error/general', $data);
        $this->clearErrorSession();
    }
}
