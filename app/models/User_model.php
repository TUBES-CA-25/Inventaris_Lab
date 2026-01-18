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
        // 1. LOGIC FOTO PROFIL (Existing)
        if ($_FILES['foto']['error'] === 4) {
            $fotoPath = $data['fotoLama'];
        } else {
            $fotoPath = $this->uploadFoto();
            if ($data['fotoLama'] && file_exists($data['fotoLama']) && $data['fotoLama'] != '../public/img/foto-profile/user.svg') {
                unlink($data['fotoLama']);
            }
        }

        // 2. LOGIC TANDA TANGAN (New)
        // Default pakai TTD lama
        $ttdName = $data['file_ttdLama']; 
        
        // Cek apakah ada upload baru di input 'file_ttd'
        if (isset($_FILES['file_ttd']) && $_FILES['file_ttd']['error'] !== 4) {
            $uploadHasil = $this->uploadTTD();
            
            if ($uploadHasil) {
                $ttdName = $uploadHasil;
                
                // Hapus file lama
                $pathLama = '../public/img/ttd/' . $data['file_ttdLama'];
                if ($data['file_ttdLama'] && file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }
        }

        // Query Update (Gunakan file_ttd)
        $query = "UPDATE trx_data_user SET 
                    foto = :foto, 
                    nama_user = :nama_user, 
                    nim_nip = :nim_nip,
                    no_hp_user = :no_hp_user, 
                    alamat = :alamat,
                    file_ttd = :file_ttd  -- <-- GANTI INI
                  WHERE id_user = :id_user";

        $this->db->query($query);
        $this->db->bind('foto', $fotoPath);
        $this->db->bind('nama_user', $data['nama_user']);
        $this->db->bind('nim_nip', $data['nim_nip']);
        $this->db->bind('no_hp_user', $data['no_hp_user']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('file_ttd', $ttdName); // Bind ke variabel
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
                            d.foto, d.nama_user, d.nim_nip, d.no_hp_user, d.jenis_kelamin, d.alamat, d.file_ttd
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

    private function uploadTTD()
    {
        // Ganti 'ttd' jadi 'file_ttd'
        $namaFile = $_FILES['file_ttd']['name'];
        $ukuranFile = $_FILES['file_ttd']['size'];
        $error = $_FILES['file_ttd']['error'];
        $tmpName = $_FILES['file_ttd']['tmp_name'];

        if ($error === 4) {
            return false;
        }

        $ekstensiGambarValid = ['png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            return false;
        }

        if ($ukuranFile > 2000000) {
            return false;
        }

        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
        $targetDir = '../public/img/ttd/';
        
        move_uploaded_file($tmpName, $targetDir . $namaFileBaru);

        return $namaFileBaru;
    }

    public function updateTTDSpesifik($files)
    {
        $countSuccess = 0;
        $targetDir = '../public/img/ttd/';
        
        if (isset($files['ttd_kalab']) && $files['ttd_kalab']['error'] !== 4) {
            $tmp = $files['ttd_kalab']['tmp_name'];
            $type = mime_content_type($tmp);
            
            // Validasi PNG
            if ($type == 'image/png') {
                if(move_uploaded_file($tmp, $targetDir . 'ttd_huzain.png')) {
                    $countSuccess++;
                }
            } else {
                return -1; 
            }
        }

        if (isset($files['ttd_laboran']) && $files['ttd_laboran']['error'] !== 4) {
            $tmp = $files['ttd_laboran']['tmp_name'];
            $type = mime_content_type($tmp);
            
            if ($type == 'image/png') {
                if(move_uploaded_file($tmp, $targetDir . 'ttd_fatimah.png')) {
                    $countSuccess++;
                }
            } else {
                return -1; 
            }
        }

        return $countSuccess;
    }
}