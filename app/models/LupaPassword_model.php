<?php

class LupaPassword_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function checkCooldown1Menit($email)
    {
        $this->db->query("SELECT 1 FROM trx_verifikasi WHERE email = :email AND tipe_verifikasi = 'lupa_password' AND created_at >= (NOW() - INTERVAL 1 MINUTE)");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function countRequestsLast3Hours($email)
    {
        $this->db->query("SELECT COUNT(*) as total FROM trx_verifikasi WHERE email = :email AND tipe_verifikasi = 'lupa_password' AND created_at >= (NOW() - INTERVAL 3 HOUR)");
        $this->db->bind('email', $email);
        $result = $this->db->single();
        return $result['total'];
    }

    public function insertToken($email, $token)
    {
        $this->db->query("INSERT INTO trx_verifikasi (email, token, tipe_verifikasi, created_at, is_used) VALUES (:email, :token, 'lupa_password', NOW(), 0)");
        $this->db->bind('email', $email);
        $this->db->bind('token', $token);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function verifyToken($token)
    {
        // 5 mins validity
        $this->db->query("SELECT id_verifikasi, email FROM trx_verifikasi WHERE token = :token AND tipe_verifikasi = 'lupa_password' AND is_used = 0 AND created_at >= (NOW() - INTERVAL 5 MINUTE)");
        $this->db->bind('token', $token);
        return $this->db->single();
    }

    public function markAsUsed($id_verifikasi)
    {
        $this->db->query("UPDATE trx_verifikasi SET is_used = 1 WHERE id_verifikasi = :id");
        $this->db->bind('id', $id_verifikasi);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updatePasswordByEmail($email, $newPasswordHash)
    {
        $this->db->query("UPDATE trx_user SET password = :password WHERE email = :email");
        $this->db->bind('password', $newPasswordHash);
        $this->db->bind('email', $email);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function checkEmailExists($email)
    {
        $this->db->query("SELECT id_user, nama_user FROM trx_user WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    public function getCooldownRemainingSeconds($email)
    {
        $this->db->query("SELECT TIMESTAMPDIFF(SECOND, NOW(), created_at + INTERVAL 1 MINUTE) as remaining FROM trx_verifikasi WHERE email = :email AND tipe_verifikasi = 'lupa_password' ORDER BY created_at DESC LIMIT 1");
        $this->db->bind('email', $email);
        $result = $this->db->single();
        if ($result && $result['remaining'] > 0) {
            return (int)$result['remaining'];
        }
        return 0;
    }
}
