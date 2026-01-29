<?php

class Auth extends Controller
{

    /**
     * Verify email using token from URL
     * Token can be passed as: /Auth/verify/TOKEN_HERE
     */
    public function verify($token = '')
    {
        // Get token from URL parameter (path or query string)
        if (empty($token)) {
            $token = $_GET['token'] ?? '';
        }

        if (empty($token)) {
            $data['judul'] = 'Verifikasi Gagal';
            $data['message'] = 'Token verifikasi tidak ditemukan.';
            $this->view('templates/header', $data);
            $this->view('Auth/verify_error', $data);
            $this->view('templates/footer');
            return;
        }

        // Verify token
        $user = $this->model('User_model')->verifyEmailToken($token);

        if ($user) {
            // Token valid - Mark email as verified
            $this->model('User_model')->markEmailAsVerified($user['id_user']);

            $data['judul'] = 'Verifikasi Berhasil';
            $this->view('templates/header', $data);
            $this->view('Auth/verify_success');
            $this->view('templates/footer');
        } else {
            // Token invalid or expired
            $data['judul'] = 'Verifikasi Gagal';
            $data['message'] = 'Link verifikasi tidak valid atau sudah expired.';
            $this->view('templates/header', $data);
            $this->view('Auth/verify_error', $data);
            $this->view('templates/footer');
        }
    }
}
