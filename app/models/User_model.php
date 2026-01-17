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
                    d.foto, d.nama_user, d.no_hp_user, d.jenis_kelamin, d.alamat
                  FROM trx_user u
                  JOIN trx_data_user d ON u.id_user = d.id_user 
                  JOIN mst_role mr ON u.id_role = mr.id_role
                  ORDER BY d.nama_user ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function cariUser()
    {
        $keyword = $_POST['keyword'];
        $query = "SELECT 
                    u.id_user, u.email, u.id_role, mr.role,
                    d.foto, d.nama_user, d.no_hp_user, d.jenis_kelamin, d.alamat
                  FROM trx_user u
                  JOIN trx_data_user d ON u.id_user = d.id_user 
                  JOIN mst_role mr ON u.id_role = mr.id_role 
                  WHERE d.nama_user LIKE :keyword
                      OR u.email LIKE :keyword
                      OR d.no_hp_user LIKE :keyword
                      OR mr.role LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function tambahUser($data)
    {
        $this->db->query("SELECT id_user FROM trx_user WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return -1;
        }

        if ($data['password'] !== $data['konfirmasi-password']) {
            return -2;
        }

        $fotoPath = $this->uploadFoto();
        if (!$fotoPath) {
            return -3;
        }

        try {
            $this->db->query('START TRANSACTION');

            $queryUser = "INSERT INTO trx_user (email, password, id_role) VALUES (:email, :password, :id_role)";
            $this->db->query($queryUser);
            $this->db->bind('email', $data['email']);
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
            $this->db->bind('id_role', $data['id_role'] ?? 7);
            $this->db->execute();

            $newUserId = $this->db->lastInsertId();

            $queryData = "INSERT INTO trx_data_user (id_user, foto, nama_user, nim_nip, no_hp_user, jenis_kelamin, alamat) 
                          VALUES (:id_user, :foto, :nama_user, :nim_nip, :no_hp_user, :jenis_kelamin, :alamat)";

            $this->db->query($queryData);
            $this->db->bind('id_user', $newUserId);
            $this->db->bind('foto', $fotoPath);
            $this->db->bind('nama_user', $data['nama_user']);
            $this->db->bind('nim_nip', $data['nim_nip']);
            $this->db->bind('no_hp_user', $data['no_hp_user']);
            $this->db->bind('jenis_kelamin', $data['jenis_kelamin']);
            $this->db->bind('alamat', $data['alamat']);
            $this->db->execute();
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return 0;
        }
    }

    public function hapusUser($id_user)
    {
        $this->db->query("SELECT foto FROM trx_data_user WHERE id_user = :id_user");
        $this->db->bind("id_user", $id_user);
        $user = $this->db->single();

        try {
            $this->db->query('START TRANSACTION');

            $this->db->query("DELETE FROM trx_data_user WHERE id_user = :id_user");
            $this->db->bind("id_user", $id_user);
            $this->db->execute();

            $this->db->query("DELETE FROM trx_user WHERE id_user = :id_user");
            $this->db->bind("id_user", $id_user);
            $this->db->execute();

            $this->db->query('COMMIT');

            if ($user && file_exists($user['foto'])) {
                unlink($user['foto']);
            }
            return 1;
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            return 0;
        }
    }

    public function updateUser($data)
    {
        if ($_FILES['foto']['error'] === 4) {
            $fotoPath = $data['fotoLama'];
        } else {
            $fotoPath = $this->uploadFoto();
            if ($data['fotoLama'] && file_exists($data['fotoLama'])) {
                unlink($data['fotoLama']);
            }
        }

        $query = "UPDATE trx_data_user SET 
                    foto = :foto, 
                    nama_user = :nama_user, 
                    nim_nip = :nim_nip,
                    no_hp_user = :no_hp_user, 
                    alamat = :alamat 
                  WHERE id_user = :id_user";

        $this->db->query($query);
        $this->db->bind('foto', $fotoPath);
        $this->db->bind('nama_user', $data['nama_user']);
        $this->db->bind('nim_nip', $data['nim_nip']);
        $this->db->bind('no_hp_user', $data['no_hp_user']);
        $this->db->bind('alamat', $data['alamat']);
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

        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
        $targetDir = '../public/img/foto-profile/';
        move_uploaded_file($tmpName, $targetDir . $namaFileBaru);

        return $targetDir . $namaFileBaru;
    }

    public function getUser($email, $password)
    {
        $this->db->query("SELECT * FROM trx_user WHERE email = :email");
        $this->db->bind("email", $email);
        $user = $this->db->single();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return NULL;
    }

    public function profile($data)
    {
        $this->db->query("SELECT 
                            u.id_user, u.email, u.id_role, mr.role,
                            d.foto, d.nama_user, d.nim_nip, d.no_hp_user, d.jenis_kelamin, d.alamat
                          FROM trx_user u 
                          JOIN trx_data_user d ON u.id_user = d.id_user 
                          JOIN mst_role mr ON u.id_role = mr.id_role 
                          WHERE u.id_user = :id_user");
        $this->db->bind('id_user', $data['id_user']);
        return $this->db->single();
    }

    public function getUbah($id_user)
    {
        $this->db->query("SELECT foto, nama_user, nim_nip, no_hp_user, alamat, id_user 
                          FROM trx_data_user 
                          WHERE id_user = :id_user");
        $this->db->bind("id_user", $id_user);
        return $this->db->single();
    }

    public function getRole($id_user)
    {
        $this->db->query("SELECT * FROM trx_user WHERE id_user = :id_user");
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
}