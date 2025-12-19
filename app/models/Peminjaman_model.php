<?php

/**
 * Model Peminjaman_model
 * * Menangani interaksi database untuk tabel 'trx_peminjaman'.
 */
class Peminjaman_model {
    
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Insert data peminjaman baru.
     */
    public function postDataPeminjaman($data) {
        // Set tanggal pengajuan otomatis hari ini jika kosong
        if (empty($data['tanggal_pengajuan'])) {
            $data['tanggal_pengajuan'] = date('Y-m-d'); // Gunakan format Y-m-d untuk Database MySQL
        }
    
        $query = "INSERT INTO trx_peminjaman
                  (nama_peminjam, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, 
                   tanggal_pengembalian, id_jenis_barang, jumlah_peminjaman, keterangan_peminjaman, status) 
                  VALUES 
                  (:nama_peminjam, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, 
                   :tanggal_pengembalian, :id_jenis_barang, :jumlah_peminjaman, :keterangan_peminjaman, :status)";
    
        $this->db->query($query);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']); 
    
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    /**
     * Mengambil daftar Sub Barang (Jenis) untuk keperluan Dropdown Filter.
     */
    public function getSubBarang() {
        $this->db->query("SELECT id_jenis_barang, sub_barang FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }
    
    /**
     * Mengambil data peminjaman dengan fitur Filter dinamis.
     * Jika filter kosong, query akan mengembalikan semua data.
     */
    public function getPeminjamanByFilters($id_jenis_barang, $status) {
        $query = "SELECT 
            b.id_peminjaman,
            b.nama_peminjam,
            b.judul_kegiatan,
            b.tanggal_pengajuan,
            b.tanggal_peminjaman,
            b.tanggal_pengembalian,
            j.sub_barang,
            b.jumlah_peminjaman,
            b.keterangan_peminjaman,
            b.status
        FROM trx_peminjaman b
        JOIN mst_jenis_barang j ON b.id_jenis_barang = j.id_jenis_barang
        WHERE 1=1"; // Trik '1=1' memudahkan penyambungan string query "AND"
    
        if (!empty($id_jenis_barang)) {
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        }
    
        if (!empty($status)) {
            $query .= " AND b.status = :status";
        }
        
        // Urutkan dari yang terbaru (opsional, tapi bagus untuk UX)
        $query .= " ORDER BY b.tanggal_pengajuan DESC";
    
        $this->db->query($query);
    
        // Binding parameters jika ada
        if (!empty($id_jenis_barang)) {
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        }
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
    
        return $this->db->resultSet();
    }
    
    /**
     * Menghapus data peminjaman.
     * Menangani proteksi Foreign Key (misal: jika sudah ada di tabel Pengembalian).
     */
    public function hapusDataPeminjaman($id) {
        try {
            $this->db->query("DELETE FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
            $this->db->bind('id_peminjaman', $id);
            $this->db->execute();
    
            return $this->db->rowCount();
        } catch (PDOException $e) {
             // Menangkap error jika data sedang direferensikan tabel lain (misal: trx_pengembalian)
             if ($e->getCode() == '23000' || isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return -1; // Kode Khusus
            }
            return 0;
        }
    }

    /**
     * Mengambil satu data detail peminjaman berdasarkan ID.
     */
    public function getDetailDataPeminjaman($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    /**
     * Update data peminjaman.
     */
    public function ubahDataPeminjaman($data) {
        $query = "UPDATE trx_peminjaman SET 
                    nama_peminjam = :nama_peminjam, 
                    judul_kegiatan = :judul_kegiatan, 
                    tanggal_peminjaman = :tanggal_peminjaman, 
                    tanggal_pengembalian = :tanggal_pengembalian, 
                    id_jenis_barang = :id_jenis_barang, 
                    jumlah_peminjaman = :jumlah_peminjaman, 
                    keterangan_peminjaman = :keterangan_peminjaman, 
                    status = :status 
                  WHERE id_peminjaman = :id_peminjaman";
    
        $this->db->query($query);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']); 
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']); 
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
    
        $this->db->execute();
    
        return $this->db->rowCount();
    }
}