<?php

class Riwayat_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllRiwayat() {
        $query = "SELECT p.*, d.nama_user, d.nim_nip 
              FROM trx_peminjaman p
              JOIN trx_data_user d ON p.id_user = d.id_user
              WHERE p.status != :status_exclude
              ORDER BY p.tanggal_pengajuan DESC";
        
        $this->db->query($query);
        $this->db->bind('status_exclude', 'Melengkapi Surat');
        return $this->db->resultSet();
    }

    public function getRiwayatByUser($id_user) {
        $query = "SELECT p.*, d.nama_user 
              FROM trx_peminjaman p
              JOIN trx_data_user d ON p.id_user = d.id_user
              WHERE p.id_user = :id_user 
              ORDER BY p.tanggal_pengajuan DESC";
                  
        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        return $this->db->resultSet();
    }
}
