<?php

class User_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function tampilUser()
    {
        $query = "SELECT 
                    u.id_user, u.email, u.id_role, mr.role,
                    u.foto, u.nama_user, u.no_hp_user, u.jenis_kelamin, u.alamat
                  FROM trx_user u
                  JOIN mst_role mr ON u.id_role = mr.id_role
                  ORDER BY u.nama_user ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function cariUser()
    {
        $keyword = $_POST['keyword'];
        $query = "SELECT 
                    u.id_user, u.email, u.id_role, mr.role,
                    u.foto, u.nama_user, u.no_hp_user, u.jenis_kelamin, u.alamat
                  FROM trx_user u
                  JOIN mst_role mr ON u.id_role = mr.id_role 
                  WHERE u.nama_user LIKE :keyword
                      OR u.email LIKE :keyword
                      OR u.no_hp_user LIKE :keyword
                      OR mr.role LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function tambahUser($data)
    {
        // 1. Cek duplikasi email
        $this->db->query("SELECT id_user FROM trx_user WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return -1;
        }

        // 2. Cek password
        if ($data['password'] !== $data['konfirmasi-password']) {
            return -2;
        }

        // 3. Validasi Berdasarkan Asal Instansi & Tentukan Role Otomatis
        $asal = $data['asal_instansi'] ?? '';
        $nim_nip = $data['nim_nip'] ?? '';
        $id_role = null;

        if ($asal === 'fikom') {
            // Mahasiswa (130/131, 11 digit)
            if ((strpos($nim_nip, '130') === 0 || strpos($nim_nip, '131') === 0) && strlen($nim_nip) === 11) {
                $id_role = '6'; // MHS
            }
            // Dosen (09, 10 digit)
            elseif (strpos($nim_nip, '09') === 0 && strlen($nim_nip) === 10) {
                $id_role = '5'; // DOSEN
            } else {
                // Input FIKOM tidak valid
                return -4;
            }
        } else {
            // Luar FIKOM tidak lagi diizinkan mendaftar mandiri
            return -5;
        }

        if (!$id_role) {
            return -5;
        }

        // 4. Handle Foto (Optional, Base64 from Cropper)
        $fotoPath = '../public/img/foto-profile/user.svg'; // Default
        if (!empty($data['cropped_foto'])) {
            $fotoPath = $this->saveBase64Image($data['cropped_foto']);
            if (!$fotoPath) {
                return -3; // Upload error
            }
        }

        $verificationToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+' . VERIFICATION_LINK_EXPIRY . ' hours'));

        try {
            $this->db->query('START TRANSACTION');

            $queryUser = "INSERT INTO trx_user (email, password, id_role, email_verified, verification_token, token_expiry, foto, nama_user, nim_nip, no_hp_user, jenis_kelamin, alamat) 
                          VALUES (:email, :password, :id_role, 0, :token, :expiry, :foto, :nama_user, :nim_nip, :no_hp_user, :jenis_kelamin, :alamat)";
            $this->db->query($queryUser);
            $this->db->bind('email', $data['email']);
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
            $this->db->bind('id_role', $id_role);
            $this->db->bind('token', $verificationToken);
            $this->db->bind('expiry', $tokenExpiry);
            $this->db->bind('foto', $fotoPath);
            $this->db->bind('nama_user', $data['nama_user']);
            $this->db->bind('nim_nip', $nim_nip);
            $this->db->bind('no_hp_user', $data['no_hp_user']);
            $this->db->bind('jenis_kelamin', $data['jenis_kelamin']);
            $this->db->bind('alamat', $data['alamat']);
            $this->db->execute();

            $newUserId = $this->db->lastInsertId();

            $this->db->query('COMMIT');

            return [
                'status' => 1,
                'user_id' => $newUserId,
                'token' => $verificationToken
            ];
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return 0;
        }
    }

    public function hapusUser($id_user)
    {
        $this->db->query("SELECT foto FROM trx_user WHERE id_user = :id_user");
        $this->db->bind("id_user", $id_user);
        $user = $this->db->single();

        try {
            $this->db->query("DELETE FROM trx_user WHERE id_user = :id_user");
            $this->db->bind("id_user", $id_user);
            $this->db->execute();

            if ($user && file_exists($user['foto']) && $user['foto'] != '../public/img/foto-profile/user.svg') {
                unlink($user['foto']);
            }
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function updateUser($data)
    {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 4) {
            $fotoPath = $data['fotoLama'];
        } elseif (isset($_FILES['foto'])) {
            $fotoPath = $this->uploadFoto();
            if ($data['fotoLama'] && file_exists($data['fotoLama']) && $data['fotoLama'] != '../public/img/foto-profile/user.svg') {
                unlink($data['fotoLama']);
            }
        } else {
            $fotoPath = $data['fotoLama'];
        }

        $ttdName = $data['file_ttdLama'];

        if (isset($_FILES['file_ttd']) && $_FILES['file_ttd']['error'] !== 4) {
            $uploadHasil = $this->uploadTTD();

            if ($uploadHasil) {
                $ttdName = $uploadHasil;

                $pathLama = '../public/img/ttd/' . $data['file_ttdLama'];
                if ($data['file_ttdLama'] && file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }
        }

        $query = "UPDATE trx_user SET 
                    foto = :foto, 
                    nama_user = :nama_user, 
                    nim_nip = :nim_nip,
                    no_hp_user = :no_hp_user, 
                    alamat = :alamat,
                    file_ttd = :file_ttd
                  WHERE id_user = :id_user";

        $this->db->query($query);
        $this->db->bind('foto', $fotoPath);
        $this->db->bind('nama_user', $data['nama_user']);
        $this->db->bind('nim_nip', $data['nim_nip']);
        $this->db->bind('no_hp_user', $data['no_hp_user']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('file_ttd', $ttdName);
        $this->db->bind('id_user', $data['id_user']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubah($data)
    {
        return $this->updateUser($data);
    }

    private function uploadFoto()
    {
        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];

        if ($error === 4) {
            return false;
        }

        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            return false;
        }

        if ($ukuranFile > 2000000) {
            return false;
        }

        $namaFileBaru = uniqid() . '.webp'; // Convert to webp
        $targetDir = '../public/img/foto-profile/';

        // Convert to WebP using GD
        if ($ekstensiGambar === 'png') {
            $img = imagecreatefrompng($tmpName);
        } else {
            $img = imagecreatefromjpeg($tmpName);
        }

        if ($img) {
            // Keep transparency if needed, but for profile usually not
            imagewebp($img, $targetDir . $namaFileBaru, 80);
            imagedestroy($img);
            return $targetDir . $namaFileBaru;
        }

        return false;
    }

    private function uploadTTD()
    {
        $namaFile = $_FILES['file_ttd']['name'];
        $ukuranFile = $_FILES['file_ttd']['size'];
        $error = $_FILES['file_ttd']['error'];
        $tmpName = $_FILES['file_ttd']['tmp_name'];

        if ($error === 4) {
            return false;
        }

        $ekstensiValid = ['png'];
        $ekstensi = explode('.', $namaFile);
        $ekstensi = strtolower(end($ekstensi));
        if (!in_array($ekstensi, $ekstensiValid)) {
            return false;
        }

        if ($ukuranFile > 1000000) { // Max 1MB
            return false;
        }

        $namaFileBaru = uniqid() . '.png';
        $targetDir = '../public/img/ttd/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($tmpName, $targetDir . $namaFileBaru)) {
            return $namaFileBaru;
        }

        return false;
    }

    /**
     * Save Base64 Cropped Image as WebP
     */
    private function saveBase64Image($base64Data)
    {
        try {
            // data:image/png;base64,xxxx
            list($type, $data) = explode(';', $base64Data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $targetDir = '../public/img/foto-profile/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . '.webp';
            $filePath = $targetDir . $fileName;

            $img = imagecreatefromstring($data);
            if ($img) {
                // Save as WebP
                imagewebp($img, $filePath, 80);
                imagedestroy($img);
                return $filePath;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUser($email, $password)
    {
        $this->db->query("SELECT id_user, email, password, id_role, email_verified, nama_user FROM trx_user WHERE email = :email");
        $this->db->bind("email", $email);
        $user = $this->db->single();

        if ($user && password_verify($password, $user['password'])) {
            // Check if email is verified
            if ($user['email_verified'] == 0) {
                return ['verified' => false];
            }
            return $user;
        }
        return NULL;
    }

    public function profile($data)
    {
        $this->db->query("SELECT 
                            u.id_user, u.email, u.id_role, mr.role,
                            u.foto, u.nama_user, u.nim_nip, u.no_hp_user, u.jenis_kelamin, u.alamat, u.file_ttd
                          FROM trx_user u 
                          JOIN mst_role mr ON u.id_role = mr.id_role 
                          WHERE u.id_user = :id_user");
        $this->db->bind('id_user', $data['id_user']);
        return $this->db->single();
    }

    public function getUbah($id_user)
    {
        $this->db->query("SELECT foto, nama_user, nim_nip, no_hp_user, alamat, id_user 
                          FROM trx_user 
                          WHERE id_user = :id_user");
        $this->db->bind("id_user", $id_user);
        return $this->db->single();
    }

    public function getRole($id_user)
    {
        $this->db->query("SELECT id_user, id_role FROM trx_user WHERE id_user = :id_user");
        $this->db->bind("id_user", $id_user);
        return $this->db->single();
    }

    public function ubahRole($data)
    {
        $this->db->query("UPDATE trx_user SET id_role = :id_role WHERE id_user = :id_user");
        $this->db->bind('id_role', $data['id_role']);
        $this->db->bind('id_user', $data['id_user']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Get user's email and name for sending verification email
     */
    public function getUserEmailAndName($userId)
    {
        $this->db->query("SELECT email, nama_user 
                          FROM trx_user
                          WHERE id_user = :id_user");
        $this->db->bind('id_user', $userId);
        return $this->db->single();
    }

    /**
     * Verify email token and check if it's still valid
     */
    public function verifyEmailToken($token)
    {
        $this->db->query("SELECT id_user, email, id_role FROM trx_user 
                          WHERE verification_token = :token 
                          AND token_expiry > NOW()");
        $this->db->bind('token', $token);
        return $this->db->single();
    }

    /**
     * Mark user's email as verified
     */
    public function markEmailAsVerified($userId)
    {
        $this->db->query("UPDATE trx_user 
                          SET email_verified = 1, 
                              verification_token = NULL, 
                              token_expiry = NULL 
                          WHERE id_user = :id_user");
        $this->db->bind('id_user', $userId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateTTDSpesifik($files)
    {
        $countSuccess = 0;

        $rootPath = dirname(__DIR__, 2);
        $targetDir = $rootPath . '/public/img/ttd/';

        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                return 0;
            }
        }

        if (isset($files['ttd_kalab']) && $files['ttd_kalab']['error'] === 0) {
            $tmp = $files['ttd_kalab']['tmp_name'];
            $type = mime_content_type($tmp);

            if ($type == 'image/png') {
                $dest = $targetDir . 'ttd_huzain.png';

                if (file_exists($dest)) {
                    unlink($dest);
                }

                if (move_uploaded_file($tmp, $dest)) {
                    $countSuccess++;
                }
            } else {
                return -1;
            }
        }

        if (isset($files['ttd_laboran']) && $files['ttd_laboran']['error'] === 0) {
            $tmp = $files['ttd_laboran']['tmp_name'];
            $type = mime_content_type($tmp);

            if ($type == 'image/png') {
                $dest = $targetDir . 'ttd_fatimah.png';

                if (file_exists($dest)) {
                    unlink($dest);
                }

                if (move_uploaded_file($tmp, $dest)) {
                    $countSuccess++;
                }
            } else {
                return -1;
            }
        }

        return $countSuccess;
    }

    public function gantiPasswordUser($data)
    {
        $id_user = $_SESSION['id_user'];
        $currentPassword = $data['currentPassword'];
        $newPassword = $data['newPassword'];
        $confirmPassword = $data['confirmPassword'];

        // 1. Ambil password lama dari DB
        $this->db->query("SELECT password FROM trx_user WHERE id_user = :id");
        $this->db->bind('id', $id_user);
        $user = $this->db->single();

        // 2. Verifikasi password lama
        if (!password_verify($currentPassword, $user['password'])) {
            return 0; // Password lama salah
        }

        // 3. Cek konfirmasi password baru
        if ($newPassword !== $confirmPassword) {
            return 0; // Password tidak cocok
        }

        // 4. Update password baru
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->db->query("UPDATE trx_user SET password = :password WHERE id_user = :id");
        $this->db->bind('password', $passwordHash);
        $this->db->bind('id', $id_user);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getAdminPhoneNumbers()
    {
        // Ambil No HP dari Role KALAB (1) dan LABORAN (2)
        $query = "SELECT no_hp_user, nama_user 
                  FROM trx_user
                  WHERE id_role IN (:role_kalab, :role_laboran) 
                  AND no_hp_user IS NOT NULL 
                  AND no_hp_user != ''";

        $this->db->query($query);
        $this->db->bind('role_kalab', '1');
        $this->db->bind('role_laboran', '2');

        return $this->db->resultSet();
    }

    public function getUsersByRole($id_role)
    {
        $this->db->query("SELECT id_user, nama_user, nim_nip FROM trx_user WHERE id_role = :id_role ORDER BY nama_user ASC");
        $this->db->bind('id_role', $id_role);
        return $this->db->resultSet();
    }

    public function getAllRoles()
    {
        $this->db->query("SELECT * FROM mst_role ORDER BY id_role ASC");
        return $this->db->resultSet();
    }

    public function getUserByName($nama)
    {
        $this->db->query("SELECT * FROM trx_user WHERE nama_user = :nama");
        $this->db->bind('nama', $nama);
        return $this->db->single();
    }
}
