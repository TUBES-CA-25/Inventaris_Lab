<?php

/**
 * Model Merek_barang_model
 * * Menangani interaksi database untuk tabel 'mst_merek_barang'.
 */
class Merek_barang_model {

    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    /**
     * Mengambil seluruh data merek.
     * Diurutkan berdasarkan nama merek (A-Z).
     */
    public function getDataMerekBarang() {
        $this->db->query("SELECT * FROM mst_merek_barang ORDER BY nama_merek_barang ASC");
        return $this->db->resultSet();
    }

    /**
     * Tambah data merek baru.
     */
    public function postDataMerekBarang($data)
    {
        $query = "INSERT INTO mst_merek_barang (nama_merek_barang, kode_merek_barang) 
                  VALUES (:nama_merek_barang, :kode_merek_barang)";
        
        $this->db->query($query);
        $this->db->bind('nama_merek_barang', $data['nama_merek_barang']);
        $this->db->bind('kode_merek_barang', $data['kode_merek_barang']);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    /**
     * Cek Duplikasi Data.
     * Mengecek apakah Nama atau Kode sudah dipakai oleh data lain.
     * * Logika: (Nama Sama OR Kode Sama) AND ID != ID Sendiri
     */
    public function cekDataMerekBarang($data)
    {
        $query = "SELECT COUNT(*) FROM mst_merek_barang WHERE 
                  (nama_merek_barang = :nama_merek_barang OR kode_merek_barang = :kode_merek_barang) 
                  AND id_merek_barang != :id_merek_barang";

        $this->db->query($query);
        $this->db->bind('nama_merek_barang', $data['nama_merek_barang']);
        $this->db->bind('kode_merek_barang', $data['kode_merek_barang']);
        
        // Handling untuk Tambah Data (dimana ID belum ada/kosong)
        // Jika kosong, kita bind dengan string kosong atau 0 agar query valid.
        $id = !empty($data['id_merek_barang']) ? $data['id_merek_barang'] : '';
        $this->db->bind('id_merek_barang', $id);
        
        $this->db->execute();

        return $this->db->single()['COUNT(*)'];
    }

    /**
     * Menghapus data merek dengan aman.
     * Mengembalikan nilai -1 jika gagal karena Foreign Key Constraint.
     */
    public function hapusMerekBarang($id_merek_barang){
        try {
            $this->db->query("DELETE FROM mst_merek_barang WHERE id_merek_barang = :id_merek_barang");
            $this->db->bind("id_merek_barang", $id_merek_barang);
            $this->db->execute();

            return $this->db->rowCount();
        } catch (PDOException $e) {
            // Cek Error Code MySQL 23000 / 1451 (Integrity Constraint Violation)
            if ($e->getCode() == '23000' || isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return -1; // Kode Error Khusus: Data Sedang Dipakai
            }
            return 0; // Gagal Umum
        }
    }

    public function getUbah($id_merek_barang) {
        $this->db->query("SELECT * FROM mst_merek_barang WHERE id_merek_barang = :id_merek_barang");
        $this->db->bind("id_merek_barang", $id_merek_barang);

        return $this->db->single();
    }

    public function ubahMerekBarang($data)
    {
        $query = "UPDATE mst_merek_barang SET 
                    nama_merek_barang = :nama_merek_barang, 
                    kode_merek_barang = :kode_merek_barang 
                  WHERE id_merek_barang = :id_merek_barang";
        
        $this->db->query($query);
        $this->db->bind('nama_merek_barang', $data['nama_merek_barang']);
        $this->db->bind('kode_merek_barang', $data['kode_merek_barang']);
        $this->db->bind('id_merek_barang', $data['id_merek_barang']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function cariDataMerekBarang(){
        $keyword = $_POST['keyword'];
        $query= "SELECT * FROM mst_merek_barang WHERE 
                 nama_merek_barang LIKE :keyword OR 
                 kode_merek_barang LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }
}