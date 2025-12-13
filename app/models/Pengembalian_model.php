<?php
class Pengembalian_model
{
    private $db;
    private $table = "trx_pengembalian";

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllPengembalian()
    {
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
                  ORDER BY p.nama_peminjam";

        $this->db->query($query);
        return $this->db->resultSet();
    }

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

    public function updatePengembalian($data)
    {
        $status = $data['status_pengembalian'];
        $tanggal_pengembalian = $data['tanggal_pengembalian'];
        $today = date('Y-m-d');
        $keterangan = '';

        if ($status === 'Dikembalikan') {
            $keterangan = ($today <= $tanggal_pengembalian) ? 'Tepat Waktu' : 'Tidak Tepat Waktu';
        } elseif ($status === 'Hilang' || $status === 'Rusak') {
            $keterangan = 'Bermasalah';
        } elseif ($status === 'Belum Dikembalikan' && $today > $tanggal_pengembalian) {
            $keterangan = 'Tidak Tepat Waktu';
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

        // Mengecek apakah ada baris yang diubah
        return $this->db->rowCount() > 0;
    }
}
