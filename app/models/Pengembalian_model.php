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
        $id_pengembalian = $data['id_pengembalian'];
        $tanggal_janji_kembali = $data['tanggal_pengembalian'];
        
        $today = date('Y-m-d');
        $keterangan = '';

        // --- Logika Keterangan ---
        if ($status === 'Dikembalikan') {
            $keterangan = ($today <= $tanggal_janji_kembali) ? 'Tepat Waktu' : 'Tidak Tepat Waktu';
        } elseif ($status === 'Hilang' || $status === 'Rusak') {
            $keterangan = 'Bermasalah';
        } elseif ($status === 'Belum Dikembalikan') {
            if ($today > $tanggal_janji_kembali) $keterangan = 'Tidak Tepat Waktu';
        }

        // --- UPDATE STATUS PENGEMBALIAN ---
        $query = "UPDATE trx_pengembalian SET 
                    status_pengembalian = :status_pengembalian, 
                    keterangan = :keterangan, 
                    detail_masalah = :detail_masalah
                  WHERE id_pengembalian = :id_pengembalian";

        $this->db->query($query);
        $this->db->bind('status_pengembalian', $status);
        $this->db->bind('keterangan', $keterangan);
        $this->db->bind('detail_masalah', $data['detail_masalah']);
        $this->db->bind('id_pengembalian', $id_pengembalian);
        $this->db->execute();

        // --- LOGIKA KEMBALIKAN STOK KE trx_barang ---
        // Hanya jika status 'Dikembalikan'
        if ($status === 'Dikembalikan') {
            
            // 1. Cari data barang dari tabel peminjaman
            $cariData = "SELECT p.id_jenis_barang, p.jumlah_peminjaman 
                         FROM trx_pengembalian k
                         JOIN trx_peminjaman p ON k.id_peminjaman = p.id_peminjaman
                         WHERE k.id_pengembalian = :id";
            
            $this->db->query($cariData);
            $this->db->bind('id', $id_pengembalian);
            $pinjam = $this->db->single();

            if ($pinjam) {
                // 2. Tambah Stok Kembali (jumlah_barang + jumlah_peminjaman)
                $balikinStok = "UPDATE trx_barang 
                                SET jumlah_barang = jumlah_barang + :jml 
                                WHERE id_jenis_barang = :id_brg";
                
                $this->db->query($balikinStok);
                $this->db->bind('jml', $pinjam['jumlah_peminjaman']);
                $this->db->bind('id_brg', $pinjam['id_jenis_barang']);
                $this->db->execute();
            }
        }

        return $this->db->rowCount();
    }

    
}