<?php
class Peminjaman_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function postDataPeminjaman($data) {
        if (!isset($data['tanggal_pengajuan']) || empty($data['tanggal_pengajuan'])) {
            $data['tanggal_pengajuan'] = date('d-m-Y'); // Format for MySQL date
        }
    
        // Status harus selalu "Diproses" saat insert
        $data['status'] = "Diproses";
    
        // Insert new peminjaman data into the table
        $query = "INSERT INTO trx_peminjaman
                  (nama_peminjam, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, tanggal_pengembalian, id_jenis_barang, jumlah_peminjaman, keterangan_peminjaman, status) 
                  VALUES (:nama_peminjam, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, :tanggal_pengembalian, :id_jenis_barang, :jumlah_peminjaman, :keterangan_peminjaman, :status)";
    
        $this->db->query($query);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']); // Status default "Diproses"
    
        $this->db->execute();
    
        return $this->db->rowCount();
    }
    

    public function getPeminjamanBarang() {
        $query = "SELECT trx_peminjaman.*, mst_jenis_barang.sub_barang 
                  FROM trx_peminjaman 
                  JOIN mst_jenis_barang ON trx_peminjaman.id_jenis_barang = mst_jenis_barang.id_jenis_barang";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getSubBarang() {
        $query = "SELECT id_jenis_barang, sub_barang FROM mst_jenis_barang ORDER BY sub_barang";
        $this->db->query($query);
        return $this->db->resultSet();
    }
    
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
        WHERE 1=1"; // Start with no filter
    
        if (!empty($id_jenis_barang)) {
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        }
    
        if (!empty($status)) {
            $query .= " AND b.status = :status";
        }
    
        $this->db->query($query);
    
        // Bind parameters
        if (!empty($id_jenis_barang)) {
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        }
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
    
        return $this->db->resultSet();
    }
    
    
    
    
    public function hapusDataPeminjaman($id) {
        $query = "DELETE FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman";
        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getPeminjamanById($id_peminjaman){
        
        this->db->query('SELECT * FROM '. $this->table . 'WHERE id_peminjaman = :id_peminjaman');
        this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->single();
    }
    public function getUbah($id_peminjaman) {
        $tampilView = "SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman;";
        $this->db->query($tampilView);
        $this->db->bind("id_peminjaman", $id_peminjaman);

        return $this->db->single();
    
    }

    public function ubahDataPeminjaman($data) {
        $queryPeminjaman = "UPDATE trx_peminjaman 
                            SET nama_peminjam = :nama_peminjam, 
                                judul_kegiatan = :judul_kegiatan, 
                                tanggal_peminjaman = :tanggal_peminjaman, 
                                tanggal_pengembalian = :tanggal_pengembalian, 
                                id_jenis_barang = :id_jenis_barang, 
                                jumlah_peminjaman = :jumlah_peminjaman, 
                                keterangan_peminjaman = :keterangan_peminjaman, 
                                status = :status 
                            WHERE id_peminjaman = :id_peminjaman";
    
        $this->db->query($queryPeminjaman);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']); 
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']); // Bisa diubah hanya pada ubahDataPeminjaman
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
    
        $this->db->execute();
    
        return $this->db->rowCount();
    }
    

    public function getDetailDataPeminjaman($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind("id_peminjaman", $id_peminjaman);

        return $this->db->single();
    }
}