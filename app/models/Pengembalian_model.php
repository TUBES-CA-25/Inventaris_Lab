<?php

class Pengembalian_model
{
    private $table = 'trx_peminjaman';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // FUNGSI INI YANG HILANG: Mengambil semua data peminjaman untuk diproses petugas
    public function getAllRiwayatForPetugas()
    {
        // Tambahkan WHERE status = 'Disetujui'
        $this->db->query("SELECT * FROM " . $this->table . " WHERE status = 'Disetujui' ORDER BY id_peminjaman DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatById($id)
    {
        $this->db->query("SELECT p.*, 
                             k.tgl_kembali, 
                             k.keterangan, 
                             k.detail_masalah, 
                             k.id_user AS id_petugas,
                             du.nama_user AS nama_petugas
                      FROM trx_peminjaman p
                      LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman 
                      LEFT JOIN trx_data_user du ON k.id_user = du.id_user -- JOIN untuk ambil nama petugas
                      WHERE p.id_peminjaman = :id");

        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function inputPengembalianAsisten($data)
    {
        // Perhatikan: Kita sekarang memasukkan 'id_user' ke database
        $query = "INSERT INTO trx_pengembalian 
              (id_peminjaman, tgl_kembali, status_pengembalian, keterangan, detail_masalah, id_user) 
              VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :id_user)";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
        $this->db->bind('tgl', date('Y-m-d'));
        $this->db->bind('status', 'Pending');
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('detail', $data['detail_masalah']);

        // --- BAGIAN PENTING: SINKRONISASI USER ---
        // Ambil ID dari session user yang sedang login (Asisten/Korlab)
        $this->db->bind('id_user', $_SESSION['id_user']);
        // -----------------------------------------

        $this->db->execute();

        // Update status peminjaman (tetap sama)
        $this->db->query("UPDATE trx_peminjaman SET status = 'dicek' WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        return $this->db->rowCount();
    }
}
