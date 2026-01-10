<?php

/**
 * Model Pengembalian_model
 * * Menangani logika database untuk tabel 'trx_pengembalian' dan relasinya dengan 'trx_peminjaman'.
 */
class Pengembalian_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil daftar peminjaman yang statusnya 'Disetujui'.
     * Menggunakan LEFT JOIN agar transaksi yang belum masuk tabel pengembalian tetap muncul.
     */
    public function getAllPengembalian()
    {
        // COALESCE digunakan untuk mengisi nilai default jika NULL (belum ada record di tabel pengembalian)
        $query = "SELECT 
                    k.id_pengembalian, 
                    p.nama_peminjam, 
                    p.tanggal_peminjaman, 
                    p.tanggal_pengembalian, 
                    jb.sub_barang, 
                    COALESCE(k.status_pengembalian, 'Belum Dikembalikan') AS status_pengembalian, 
                    k.keterangan,
                    k.detail_masalah
                  FROM trx_peminjaman p
                  LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman
                  JOIN mst_jenis_barang jb ON p.id_jenis_barang = jb.id_jenis_barang
                  WHERE p.status = 'disetujui'
                  ORDER BY p.tanggal_pengembalian ASC"; // Diurutkan berdasarkan tenggat waktu

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Mengambil satu data spesifik untuk proses edit.
     */
    public function getUbahPengembalian($id_pengembalian)
    {
        $query = "SELECT 
                    k.id_pengembalian, 
                    p.nama_peminjam,
                    p.tanggal_peminjaman,
                    k.status_pengembalian, 
                    k.keterangan, 
                    k.detail_masalah, 
                    p.tanggal_pengembalian
                  FROM trx_pengembalian k
                  JOIN trx_peminjaman p ON k.id_peminjaman = p.id_peminjaman
                  WHERE k.id_pengembalian = :id_pengembalian";

        $this->db->query($query);
        $this->db->bind('id_pengembalian', $id_pengembalian);
        return $this->db->single();
    }

    /**
     * Update data pengembalian.
     * * Logika Bisnis:
     * 1. Jika Status 'Dikembalikan': Cek tanggal hari ini vs tanggal janji kembali.
     * 2. Jika Status 'Hilang/Rusak': Set keterangan 'Bermasalah'.
     */
    public function updatePengembalian($data)
    {
        $status = $data['status_pengembalian'];
        $tanggal_janji_kembali = $data['tanggal_pengembalian']; // Dari DB/Hidden Input
        
        // Gunakan Y-m-d untuk perbandingan tanggal yang aman
        $today = date('Y-m-d');
        $keterangan = '';

        // --- Logika Penentuan Keterangan ---
        if ($status === 'Dikembalikan') {
            // Cek apakah pengembalian melebihi tanggal janji
            if ($today <= $tanggal_janji_kembali) {
                $keterangan = 'Tepat Waktu';
            } else {
                $keterangan = 'Tidak Tepat Waktu';
            }
        } 
        elseif ($status === 'Hilang' || $status === 'Rusak') {
            $keterangan = 'Bermasalah';
        } 
        elseif ($status === 'Belum Dikembalikan') {
            // Jika status masih belum dikembalikan tapi sudah lewat tanggal
            if ($today > $tanggal_janji_kembali) {
                $keterangan = 'Tidak Tepat Waktu';
            } else {
                $keterangan = ''; // Masih dalam masa peminjaman
            }
        }

        $query = "UPDATE trx_pengembalian SET 
                    status_pengembalian = :status_pengembalian, 
                    keterangan = :keterangan, 
                    detail_masalah = :detail_masalah
                  WHERE id_pengembalian = :id_pengembalian";

        $this->db->query($query);
        $this->db->bind('status_pengembalian', $status);
        $this->db->bind('keterangan', $keterangan);
        $this->db->bind('detail_masalah', $data['detail_masalah']);
        $this->db->bind('id_pengembalian', $data['id_pengembalian']);

        $this->db->execute();

        return $this->db->rowCount();
    }
}