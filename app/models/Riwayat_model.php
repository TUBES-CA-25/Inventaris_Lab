<?php

class Riwayat_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllRiwayat()
    {
        $query = "SELECT p.id_peminjaman, p.id_user, p.id_jenis_peminjaman, p.judul_kegiatan, 
                         p.tanggal_pengajuan, p.tanggal_peminjaman, p.tanggal_pengembalian, 
                         p.keterangan_peminjaman, p.keterangan_tolak, p.id_status_peminjaman, 
                         p.file_surat, p.validasi_kalab,
                         d.nama_user, d.nim_nip, msp.nama_status AS status
              FROM trx_peminjaman p
              JOIN trx_data_user d ON p.id_user = d.id_user
              JOIN mst_status_peminjaman msp ON p.id_status_peminjaman = msp.id_status_peminjaman
              WHERE msp.nama_status != :status_exclude
              ORDER BY p.tanggal_pengajuan DESC";

        $this->db->query($query);
        $this->db->bind('status_exclude', 'Melengkapi Surat');
        return $this->db->resultSet();
    }

    public function getRiwayatByUser($id_user)
    {
        $query = "SELECT p.id_peminjaman, p.id_user, p.id_jenis_peminjaman, p.judul_kegiatan, 
                         p.tanggal_pengajuan, p.tanggal_peminjaman, p.tanggal_pengembalian, 
                         p.keterangan_peminjaman, p.keterangan_tolak, p.id_status_peminjaman, 
                         p.file_surat, p.validasi_kalab,
                         d.nama_user, msp.nama_status AS status
              FROM trx_peminjaman p
              JOIN trx_data_user d ON p.id_user = d.id_user
              JOIN mst_status_peminjaman msp ON p.id_status_peminjaman = msp.id_status_peminjaman
              WHERE p.id_user = :id_user 
              ORDER BY p.tanggal_pengajuan DESC";

        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        return $this->db->resultSet();
    }

    public function getStatistik($id_user = null)
    {
        $query = "SELECT 
            SUM(CASE WHEN id_status_peminjaman IN (3) THEN 1 ELSE 0 END) as total_disetujui,
            SUM(CASE WHEN id_status_peminjaman = 2 THEN 1 ELSE 0 END) as total_diproses,
            SUM(CASE WHEN id_status_peminjaman = 1 THEN 1 ELSE 0 END) as total_surat,
            SUM(CASE WHEN id_status_peminjaman IN (4, 6) THEN 1 ELSE 0 END) as total_ditolak,
            SUM(CASE WHEN id_status_peminjaman = 5 THEN 1 ELSE 0 END) as total_kembali
            FROM trx_peminjaman";

        if ($id_user != null) {
            $query .= " WHERE id_user = :id_user";
        }

        $this->db->query($query);

        if ($id_user != null) {
            $this->db->bind('id_user', $id_user);
        }

        return $this->db->single();
    }
}
