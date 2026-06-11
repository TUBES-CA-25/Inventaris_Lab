<?php

class LupaPassword extends Controller
{
    public function index()
    {
        $data['judul'] = 'Lupa Kata Sandi';
        $data['cooldown'] = 0;
        $data['cooldown_email'] = '';
        
        if (isset($_SESSION['last_reset_email'])) {
            $email = $_SESSION['last_reset_email'];
            $model = $this->model('LupaPassword_model');
            $remaining = $model->getCooldownRemainingSeconds($email);
            if ($remaining > 0) {
                $data['cooldown'] = $remaining;
                $data['cooldown_email'] = $email;
            } else {
                unset($_SESSION['last_reset_email']);
            }
        }

        $this->view('templates/header', $data);
        $this->view('LupaPassword/index', $data);
        $this->view('templates/footer');
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        $email = $_POST['email'];
        $_SESSION['last_reset_email'] = $email;
        $model = $this->model('LupaPassword_model');

        // Check if email exists
        $user = $model->checkEmailExists($email);
        if (!$user) {
            Flasher::setFlash('Gagal', 'Email tidak terdaftar dalam sistem.', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        // Limit 5 requests per 3 hours
        $count = $model->countRequestsLast3Hours($email);
        if ($count >= 5) {
            Flasher::setFlash('Dibatasi', 'Anda telah mencapai batas 5 permintaan. Harap tunggu 3 jam sebelum mencoba lagi.', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        // Wait 1 minute between requests
        if ($model->checkCooldown1Menit($email)) {
            Flasher::setFlash('Tunggu Sebentar', 'Silakan tunggu 1 menit sebelum meminta link reset kembali.', '', 'warning');
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        // Generate token and send email
        $token = bin2hex(random_bytes(32));
        if ($model->insertToken($email, $token) > 0) {
            $emailHelper = new EmailHelper();
            $resetLink = BASEURL . "LupaPassword/reset/" . $token;
            
            if ($emailHelper->sendPasswordResetEmail($email, $user['nama_user'], $resetLink)) {
                Flasher::setFlash('Berhasil', 'Tautan reset kata sandi telah dikirim ke email Anda. Berlaku selama 5 menit.', '', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan saat mengirim email. Coba lagi nanti.', '', 'danger');
            }
        } else {
            Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem.', '', 'danger');
        }

        header('Location: ' . BASEURL . 'LupaPassword/index');
        exit;
    }

    public function reset($token = '')
    {
        if (empty($token)) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        $model = $this->model('LupaPassword_model');
        $validToken = $model->verifyToken($token);

        if (!$validToken) {
            Flasher::setFlash('Kadaluarsa', 'Tautan reset password sudah tidak valid atau expired (melewati 5 menit).', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        $data['judul'] = 'Reset Kata Sandi';
        $data['token'] = $token;
        $data['email'] = $validToken['email'];

        $this->view('templates/header', $data);
        $this->view('LupaPassword/reset', $data);
        $this->view('templates/footer');
    }

    public function processReset()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        $token = $_POST['token'];
        $password = $_POST['password'];
        $konfirmasi = $_POST['konfirmasi-password'];

        $model = $this->model('LupaPassword_model');
        $validToken = $model->verifyToken($token);

        if (!$validToken) {
            Flasher::setFlash('Kadaluarsa', 'Tautan reset password expired.', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/index');
            exit;
        }

        if ($password !== $konfirmasi) {
            Flasher::setFlash('Gagal', 'Password Baru dan Konfirmasi Password tidak cocok.', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/reset/' . $token);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        if ($model->updatePasswordByEmail($validToken['email'], $hashedPassword) >= 0) {
            $model->markAsUsed($validToken['id_verifikasi']);
            Flasher::setFlash('Berhasil', 'Kata sandi berhasil direset! Silakan login.', '', 'success');
            header('Location: ' . BASEURL . 'Login');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'Gagal mereset kata sandi. Coba lagi.', '', 'danger');
            header('Location: ' . BASEURL . 'LupaPassword/reset/' . $token);
            exit;
        }
    }
}
