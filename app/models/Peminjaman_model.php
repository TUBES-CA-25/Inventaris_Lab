<?php
class Peminjaman_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function postDataPeminjaman($data)
    {
        if (empty($data['tanggal_pengajuan'])) {
            $data['tanggal_pengajuan'] = date('Y-m-d');
        }

        $ket_header = isset($data['keterangan_peminjaman']) && is_array($data['keterangan_peminjaman'])
            ? implode(", ", $data['keterangan_peminjaman'])
            : "-";

        $queryHeader = "INSERT INTO trx_peminjaman
                  (nama_peminjam, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, 
                   tanggal_pengembalian, keterangan_peminjaman, status, file_surat) 
                  VALUES 
                  (:nama_peminjam, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, 
                   :tanggal_pengembalian, :ket, :status, :file_surat)";

        $this->db->query($queryHeader);

        $this->db->bind('nama_peminjam', $data['nama_peminjam']);

        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('ket', $ket_header);
        $this->db->bind('status', 'Melengkapi Surat');
        $this->db->bind('file_surat', null);

        $this->db->execute();
        $id_peminjaman_baru = $this->db->lastInsertId();

        if (!isset($data['id_jenis_barang']) || !is_array($data['id_jenis_barang'])) {
            return 1;
        }

        $jumlah_data = count($data['id_jenis_barang']);
        $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, jumlah) VALUES (:id_p, :id_b, :jml)";

        $barang_tersimpan = 0;

        for ($i = 0; $i < $jumlah_data; $i++) {
            if (!empty($data['id_jenis_barang'][$i])) {
                $this->db->query($queryDetail);
                $this->db->bind('id_p', $id_peminjaman_baru);
                $this->db->bind('id_b', $data['id_jenis_barang'][$i]);

                $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? $data['jumlah_peminjaman'][$i] : 1;
                $this->db->bind('jml', $jumlah);

                $this->db->execute();
                $barang_tersimpan++;
            }
        }

        return $barang_tersimpan;
    }

    public function getPeminjamanBarang()
    {
        $query = "SELECT trx_peminjaman.*, mst_jenis_barang.sub_barang 
                  FROM trx_peminjaman 
                  JOIN mst_jenis_barang ON trx_peminjaman.id_jenis_barang = mst_jenis_barang.id_jenis_barang";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getSubBarang()
    {
        $this->db->query("SELECT id_jenis_barang, sub_barang FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }

    public function getPeminjamanByFilters($id_jenis_barang, $status)
    {
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
        WHERE 1=1";

        if (!empty($id_jenis_barang)) {
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        }

        if (!empty($status)) {
            $query .= " AND b.status = :status";
        }

        $query .= " ORDER BY b.tanggal_pengajuan DESC";

        $this->db->query($query);

        if (!empty($id_jenis_barang)) {
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        }
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }

        return $this->db->resultSet();
    }

    public function hapusDataPeminjaman($id)
    {
        $query = "DELETE FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman";
        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getPeminjamanById($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->single();
    }

    public function getDetailValidasiDataPeminjaman($id_peminjaman)
    {
        $query = "SELECT tp.*, 
                          GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang,
                          SUM(tdp.jumlah) as jumlah_peminjaman
                  FROM trx_peminjaman tp
                  LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                  LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang 
                  WHERE tp.id_peminjaman = :id_peminjaman
                  GROUP BY tp.id_peminjaman";

        $this->db->query($query);
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    public function getUbah($id_peminjaman)
    {
        $tampilView = "SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman;";
        $this->db->query($tampilView);
        $this->db->bind("id_peminjaman", $id_peminjaman);

        return $this->db->single();
    }

    public function ubahDataPeminjaman($data)
    {
        $ket_header = isset($data['keterangan_peminjaman']) && is_array($data['keterangan_peminjaman'])
            ? implode(", ", $data['keterangan_peminjaman'])
            : (isset($data['keterangan_peminjaman']) ? $data['keterangan_peminjaman'] : "-");

        $queryPeminjaman = "UPDATE trx_peminjaman 
                            SET nama_peminjam = :nama_peminjam, 
                                judul_kegiatan = :judul_kegiatan, 
                                tanggal_peminjaman = :tanggal_peminjaman, 
                                tanggal_pengembalian = :tanggal_pengembalian, 
                                keterangan_peminjaman = :keterangan_peminjaman, 
                                status = :status 
                            WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($queryPeminjaman);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('keterangan_peminjaman', $ket_header);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);

        $this->db->execute();

        $this->db->query("DELETE FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        $detail_inserted = 0;

        if (isset($data['id_jenis_barang']) && is_array($data['id_jenis_barang'])) {
            $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, jumlah) VALUES (:id_p, :id_b, :jml)";

            $jumlah_data = count($data['id_jenis_barang']);
            for ($i = 0; $i < $jumlah_data; $i++) {
                if (!empty($data['id_jenis_barang'][$i])) {
                    $this->db->query($queryDetail);
                    $this->db->bind('id_p', $data['id_peminjaman']);
                    $this->db->bind('id_b', $data['id_jenis_barang'][$i]);

                    $jml = !empty($data['jumlah_peminjaman'][$i]) ? $data['jumlah_peminjaman'][$i] : 1;
                    $this->db->bind('jml', $jml);

                    $this->db->execute();
                    $detail_inserted++;
                }
            }
        }

        return $this->db->rowCount() + $detail_inserted;
    }

    public function getDetailDataPeminjaman($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    public function getAllBarang()
    {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }

    public function cariBarang($keyword)
    {
        $this->db->query("SELECT * FROM mst_jenis_barang WHERE sub_barang LIKE :keyword");
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function getBarangWhereIn($id_array)
    {
        if (empty($id_array)) return [];

        $placeholders = implode(',', array_fill(0, count($id_array), '?'));

        $query = "SELECT * FROM mst_jenis_barang WHERE id_jenis_barang IN ($placeholders)";
        $this->db->query($query);

        foreach ($id_array as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        return $this->db->resultSet();
    }

    public function updateStatusValidasi($id_peminjaman, $status, $catatan = null)
    {
        $query = "UPDATE trx_peminjaman SET status = :status";

        if ($status == 'ditolak') {
            $query .= ", keterangan_peminjaman = :keterangan";
        }

        $query .= " WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('status', $status);
        $this->db->bind('id_peminjaman', $id_peminjaman);

        if ($status == 'ditolak') {
            $pesan = "[DITOLAK] " . $catatan;
            $this->db->bind('keterangan', $pesan);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getValidasiGabungan()
    {
        $query = "SELECT tp.*, 
                          GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang 
                  FROM trx_peminjaman tp
                  LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                  LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                  
                  WHERE tp.status IN ('diproses', 'disetujui', 'ditolak') 
                  
                  GROUP BY tp.id_peminjaman
                  
                  ORDER BY 
                    CASE 
                        WHEN tp.status = 'diproses' THEN 1 
                        WHEN tp.status = 'disetujui' THEN 2 
                        ELSE 3
                    END ASC,
                    tp.tanggal_pengajuan DESC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function hitungStatus($status)
    {
        $this->db->query("SELECT COUNT(*) as total FROM trx_peminjaman WHERE status = :status");
        $this->db->bind('status', $status);

        $result = $this->db->single();

        return isset($result['total']) ? $result['total'] : 0;
    }

    public function getPeminjamanTerbaruUser($nama_user)
    {
        $query = "SELECT tp.*, mjb.sub_barang 
                  FROM trx_peminjaman tp
                  JOIN mst_jenis_barang mjb ON tp.id_jenis_barang = mjb.id_jenis_barang
                  WHERE tp.nama_peminjam = :nama 
                  AND tp.status = 'Melengkapi Surat'
                  ORDER BY tp.id_peminjaman DESC";

        $this->db->query($query);
        $this->db->bind('nama', $nama_user);
        return $this->db->resultSet();
    }

    public function getDetailPeminjaman($id_peminjaman)
    {
        $query = "SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('id', $id_peminjaman);
        return $this->db->single();
    }

    public function getUserProfile($id_user)
    {
        $query = "SELECT du.*, u.email 
                  FROM trx_data_user du
                  JOIN trx_user u ON du.id_user = u.id_user
                  WHERE u.id_user = :id_user";

        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        return $this->db->single();
    }

    public function updateSuratPeminjaman($id, $namaFile)
    {
        $query = "UPDATE trx_peminjaman SET 
                    file_surat = :file, 
                    status = 'diproses' 
                  WHERE id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('file', $namaFile);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getDetailBarangByPeminjamanId($id)
    {
        $query = "SELECT 
                d.id_jenis_barang, 
                d.jumlah, 
                d.keterangan_barang, 
                mjb.sub_barang as nama_barang, 
                mjb.kode_sub as kode_barang,
                mjb.grup_sub, 
                'Baik' as kondisi 
              FROM trx_detail_peminjaman d 
              JOIN mst_jenis_barang mjb ON d.id_jenis_barang = mjb.id_jenis_barang 
              WHERE d.id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);

        return $this->db->resultSet();
    }
}