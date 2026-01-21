<?php

class Pengembalian_model {
    private $table = 'trx_peminjaman';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // FUNGSI INI YANG HILANG: Mengambil semua data peminjaman untuk diproses petugas
    public function getAllRiwayatForPetugas() {
        // Mengambil semua data agar Asisten/Korlab bisa melihat seluruh peminjam
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY id_peminjaman DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatById($id) {
        // Gunakan LEFT JOIN agar data detail tetap tampil meski belum ada data di trx_pengembalian
        $this->db->query("SELECT p.*, k.tgl_kembali, k.keterangan, k.detail_masalah, k.id_petugas 
                          FROM trx_peminjaman p
                          LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman 
                          WHERE p.id_peminjaman = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function inputPengembalianAsisten($data) {
        // 1. Simpan pengecekan ke trx_pengembalian
        $query = "INSERT INTO trx_pengembalian 
                  (id_peminjaman, tgl_kembali, status_pengembalian, keterangan, detail_masalah, id_petugas) 
                  VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :id_petugas)";
        
        $this->db->query($query);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
        $this->db->bind('tgl', date('Y-m-d'));
        $this->db->bind('status', 'Pending'); 
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('detail', $data['detail_masalah']);
        $this->db->bind('id_petugas', $_SESSION['id_user']);
        $this->db->execute();

        // 2. Update status utama menjadi 'dicek'
        $this->db->query("UPDATE trx_peminjaman SET status = 'dicek' WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        return $this->db->rowCount();
    }
}