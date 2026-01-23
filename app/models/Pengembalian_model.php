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
        // Mengambil semua data peminjaman dengan informasi user
        $this->db->query("SELECT 
                p.*,
                u.nama_user as nama_peminjam
            FROM " . $this->table . " p
            LEFT JOIN trx_user tu ON p.id_user = tu.id_user
            LEFT JOIN trx_data_user u ON tu.id_user = u.id_user
            ORDER BY p.id_peminjaman DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatById($id)
    {
        // Query lengkap dengan LEFT JOIN untuk mendapatkan semua data
        $this->db->query("SELECT 
                p.*,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                pen.keterangan,
                pen.detail_masalah,
                pen.id_petugas,
                pen.status_pengembalian,
                petugas.nama_user as nama_petugas,
                dp.id_detail,
                dp.jumlah,
                dp.keterangan_barang,
                jb.sub_barang as nama_barang,
                jb.id_jenis_barang
            FROM trx_peminjaman p
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            LEFT JOIN trx_detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
            LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
            LEFT JOIN trx_data_user petugas ON pen.id_petugas = petugas.id_user
            WHERE p.id_peminjaman = :id
            LIMIT 1");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function inputPengembalianAsisten($data)
    {
        // 1. Simpan pengecekan ke trx_pengembalian
        $query = "INSERT INTO trx_pengembalian 
                  (id_peminjaman, tgl_pengembalian_aktual, status_pengembalian, keterangan, detail_masalah, id_petugas) 
                  VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :id_petugas)";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
        $this->db->bind('tgl', date('Y-m-d')); // Tanggal hari ini
        $this->db->bind('status', $data['status_pengembalian'] ?? 'Dikembalikan');
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('detail', $data['detail_masalah'] ?? '-');
        $this->db->bind('id_petugas', $_SESSION['id_user']);
        $this->db->execute();

        // 2. Update status utama menjadi 'Dikembalikan'
        $this->db->query("UPDATE trx_peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    // Method untuk mendapatkan semua jenis barang
    public function getAllJenisBarang()
    {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }
}