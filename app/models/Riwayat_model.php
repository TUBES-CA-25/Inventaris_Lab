<?php

class Riwayat_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllRiwayat()
    {
        $query = "SELECT p.*, k.status_pengembalian 
                  FROM trx_peminjaman p
                  LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman
                  ORDER BY p.tanggal_pengajuan DESC";
        
        $this->db->query($query);
        $results = $this->db->resultSet();

        foreach ($results as &$row) {
            if (!empty($row['status_pengembalian']) && $row['status_pengembalian'] == 'Dikembalikan') {
                $row['status'] = 'dikembalikan'; 
            }
        }
        return $results;
    }

    public function getRiwayatByUser($nama_user)
    {
        $query = "SELECT p.*, k.status_pengembalian 
                  FROM trx_peminjaman p
                  LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman
                  WHERE p.nama_peminjam = :nama
                  ORDER BY p.tanggal_pengajuan DESC";
        
        $this->db->query($query);
        $this->db->bind('nama', $nama_user);
        $results = $this->db->resultSet();

        foreach ($results as &$row) {
            if (!empty($row['status_pengembalian']) && $row['status_pengembalian'] == 'Dikembalikan') {
                $row['status'] = 'dikembalikan';
            }
        }
        return $results;
    }
}