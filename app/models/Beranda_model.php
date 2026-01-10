<?php

/**
 * Model Beranda_model
 * * Bertanggung jawab menyediakan data statistik untuk Dashboard.
 */
class Beranda_model {

    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    /**
     * [LEGACY] Menghitung jumlah baris data pada tabel tertentu.
     * Method ini melakukan 1 query per panggilan.
     * Gunakan 'getAllCounts' untuk performa yang lebih baik di Dashboard.
     * * @param string $table Nama tabel yang ingin dihitung
     */
    public function getCount($table) {
        $this->db->query("SELECT COUNT(*) as total FROM " . $table);
        return $this->db->single()['total'];
    }

    /**
     * [OPTIMIZED] Mengambil semua statistik Dashboard sekaligus.
     * * Menggunakan teknik "Sub-Query SELECT" untuk menghitung data dari 
     * 5 tabel berbeda hanya dalam 1 kali koneksi database.
     * Ini mencegah masalah latency (keterlambatan jaringan) akibat bolak-balik request.
     * * @return array Array asosiatif berisi jumlah data masing-masing tabel
     */
    public function getAllCounts() {
        // Query Tunggal yang efisien.
        // PENTING: Menggunakan 'trx_barang' (Tabel Fisik) alih-alih 'detail_barang' (View)
        // karena menghitung baris pada View dengan banyak JOIN sangat lambat.
        
        $query = "SELECT 
                    (SELECT COUNT(*) FROM mst_jenis_barang) as jml_jenis,
                    (SELECT COUNT(*) FROM trx_peminjaman) as jml_peminjaman,
                    (SELECT COUNT(*) FROM mst_merek_barang) as jml_merek,
                    (SELECT COUNT(*) FROM trx_barang) as jml_barang, 
                    (SELECT COUNT(*) FROM trx_pengembalian) as jml_pengembalian";
        
        $this->db->query($query);
        return $this->db->single();
    }
}