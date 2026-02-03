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
    
    public function getStatistik($id_user = null)
    {
        $query = "SELECT 
            SUM(CASE WHEN status = 'Disetujui' OR status = 'Diterima' THEN 1 ELSE 0 END) as total_disetujui,
            SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as total_diproses,
            SUM(CASE WHEN status LIKE '%Ditolak%' THEN 1 ELSE 0 END) as total_ditolak,
            SUM(CASE WHEN status = 'Selesai' OR status = 'Dikembalikan' THEN 1 ELSE 0 END) as total_kembali
            FROM trx_peminjaman";

        if ($id_user != null) {
            $query .= " WHERE id_user = :id_user";
        }

        $this->db->query($query);

        if ($id_user != null) {
            $this->db->bind('id_user', $id_user);
        }

        return $this->db->single(); 
    }
}
