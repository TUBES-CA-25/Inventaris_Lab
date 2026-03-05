<?php

class Pengembalian_model
{
    private $table = 'trx_peminjaman';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllRiwayatForPetugas()
    {
        $this->db->query("SELECT 
                p.id_peminjaman, p.id_user, p.id_jenis_peminjaman, p.judul_kegiatan, 
                p.tanggal_pengajuan, p.tanggal_peminjaman, p.tanggal_pengembalian, 
                p.keterangan_peminjaman, p.id_status_peminjaman,
                u.nama_user as nama_peminjam,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                mspg.nama_status_pengembalian AS status_pengembalian,
                msp.nama_status AS status,
                mkp.nama_keterangan as keterangan_pengembalian,
                CASE 
                    WHEN pen.id_pengembalian IS NOT NULL THEN 'Sudah Di-ACC'
                    WHEN p.id_status_peminjaman = 5 THEN 'Sudah Di-ACC' -- 5 = Dikembalikan
                    ELSE 'Belum Di-ACC'
                END as status_acc
            FROM " . $this->table . " p
            LEFT JOIN trx_user u ON p.id_user = u.id_user
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            JOIN mst_status_peminjaman msp ON p.id_status_peminjaman = msp.id_status_peminjaman
            LEFT JOIN mst_status_pengembalian mspg ON pen.id_status_pengembalian = mspg.id_status_pengembalian
            LEFT JOIN mst_keterangan_pengembalian mkp ON pen.id_keterangan_pengembalian = mkp.id_keterangan_pengembalian
            WHERE p.id_status_peminjaman IN (3, 6) -- 3=Disetujui, 6=Tolak Pengembalian
            ORDER BY 
                CASE WHEN pen.id_pengembalian IS NULL THEN 0 ELSE 1 END,
                p.id_peminjaman DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatById($id_peminjaman)
    {
        $this->db->query("SELECT 
                p.id_peminjaman, p.id_user, p.id_jenis_peminjaman, p.judul_kegiatan, 
                p.tanggal_pengajuan, p.tanggal_peminjaman, p.tanggal_pengembalian, 
                p.keterangan_peminjaman, p.keterangan_tolak, p.id_status_peminjaman,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                mkp.nama_keterangan as keterangan_header,
                pen.detail_masalah,
                mspg.nama_status_pengembalian AS status_pengembalian,
                msp.nama_status AS status,
                (SELECT tpp.bukti_foto 
                 FROM trx_pemeriksa_pengembalian tpp 
                 WHERE tpp.id_pengembalian = pen.id_pengembalian 
                 AND tpp.bukti_foto IS NOT NULL 
                 ORDER BY tpp.waktu_periksa DESC LIMIT 1) as bukti_foto,
                u.nama_user as nama_peminjam
            FROM trx_peminjaman p
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            LEFT JOIN trx_user u ON p.id_user = u.id_user
            JOIN mst_status_peminjaman msp ON p.id_status_peminjaman = msp.id_status_peminjaman
            LEFT JOIN mst_status_pengembalian mspg ON pen.id_status_pengembalian = mspg.id_status_pengembalian
            LEFT JOIN mst_keterangan_pengembalian mkp ON pen.id_keterangan_pengembalian = mkp.id_keterangan_pengembalian
            WHERE p.id_peminjaman = :id
            LIMIT 1");

        $this->db->bind('id', $id_peminjaman);
        return $this->db->single();
    }

    public function updateOrInsertPengembalian($data)
    {
        try {
            $this->db->beginTransaction();

            $this->db->query("SELECT id_pengembalian FROM trx_pengembalian WHERE id_peminjaman = :id");
            $this->db->bind('id', $data['id_peminjaman']);
            $existing = $this->db->single();

            $id_pengembalian = null;

            if ($existing) {
                $id_pengembalian = $existing['id_pengembalian'];

                // Mapping strings to IDs
                $statusMap = ['Selesai Periksa' => 1, 'Periksa' => 2, 'Periksa Ulang' => 3];
                $ketMap = ['Tepat Waktu' => 1, 'Tidak Tepat Waktu' => 2, 'Bermasalah' => 3];

                $stId = $statusMap[$data['status_pengembalian']] ?? 2;
                $ketId = $ketMap[$data['keterangan'] ?? 'Tepat Waktu'] ?? 1;

                $query = "UPDATE trx_pengembalian SET 
                          tgl_pengembalian_aktual = :tgl, 
                          id_status_pengembalian = :st, 
                          id_keterangan_pengembalian = :ket,
                          detail_masalah = :detail
                          WHERE id_pengembalian = :id";

                $this->db->query($query);
                $this->db->bind('tgl', $data['tgl_pengembalian_aktual']);
                $this->db->bind('st', $stId);
                $this->db->bind('ket', $ketId);
                $this->db->bind('detail', $data['detail_masalah'] ?? '-');
                $this->db->bind('id', $id_pengembalian);
                $this->db->execute();

            } else {
                // Mapping strings to IDs
                $statusMap = ['Selesai Periksa' => 1, 'Periksa' => 2, 'Periksa Ulang' => 3];
                $ketMap = ['Tepat Waktu' => 1, 'Tidak Tepat Waktu' => 2, 'Bermasalah' => 3];

                $stId = $statusMap[$data['status_pengembalian']] ?? 2;
                $ketId = $ketMap[$data['keterangan'] ?? 'Tepat Waktu'] ?? 1;

                $query = "INSERT INTO trx_pengembalian 
                          (id_peminjaman, tgl_pengembalian_aktual, id_status_pengembalian, id_keterangan_pengembalian, detail_masalah) 
                          VALUES (:idp, :tgl, :st, :ket, :detail)";

                $this->db->query($query);
                $this->db->bind('idp', $data['id_peminjaman']);
                $this->db->bind('tgl', $data['tgl_pengembalian_aktual']);
                $this->db->bind('st', $stId);
                $this->db->bind('ket', $ketId);
                $this->db->bind('detail', $data['detail_masalah'] ?? '-');
                $this->db->execute();
                $id_pengembalian = $this->db->lastInsertId();
            }

            $id_petugas_aksi = isset($data['id_petugas']) ? $data['id_petugas'] : $_SESSION['id_user'];

            $foto_log = !empty($data['bukti_foto']) ? 'uploads/pengembalian/' . $data['bukti_foto'] : null;

            $queryLog = "INSERT INTO trx_pemeriksa_pengembalian (id_pengembalian, id_user, bukti_foto) 
                         VALUES (:id_peng, :id_user, :foto)";
            $this->db->query($queryLog);
            $this->db->bind('id_peng', $id_pengembalian);
            $this->db->bind('id_user', $id_petugas_aksi);
            $this->db->bind('foto', $foto_log);
            $this->db->execute();

            if (isset($data['kondisi']) && is_array($data['kondisi'])) {
                foreach ($data['kondisi'] as $id_detail_pinjam => $kondisi_item) {
                    $ket_item = $data['ket_item'][$id_detail_pinjam] ?? '-';

                    $this->db->query("SELECT id_detail_pengembalian FROM trx_detail_pengembalian WHERE id_pengembalian = :id_peng AND id_detail_peminjaman = :id_dp");
                    $this->db->bind('id_peng', $id_pengembalian);
                    $this->db->bind('id_dp', $id_detail_pinjam);
                    $cekItem = $this->db->single();

                    if ($cekItem) {
                        $this->db->query("UPDATE trx_detail_pengembalian SET id_kondisi_pengembalian=:k, keterangan_kondisi=:kk WHERE id_detail_pengembalian=:id");
                        $this->db->bind('id', $cekItem['id_detail_pengembalian']);
                    } else {
                        $this->db->query("INSERT INTO trx_detail_pengembalian (id_pengembalian, id_detail_peminjaman, jumlah_kembali, id_kondisi_pengembalian, keterangan_kondisi) VALUES (:idp, :idd, 1, :k, :kk)");
                        $this->db->bind('idp', $id_pengembalian);
                        $this->db->bind('idd', $id_detail_pinjam);
                    }

                    $kondisiMap = ['Baik' => 1, 'Rusak' => 2, 'Hilang' => 3];
                    $kondisiId = $kondisiMap[$kondisi_item] ?? 1;

                    $this->db->bind('k', $kondisiId);
                    $this->db->bind('kk', $ket_item);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return 1;
        } catch (Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }

    public function getAllJenisBarang()
    {
        $this->db->query("SELECT id_jenis_barang, sub_barang FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }

    public function getBarangPengembalian($id_peminjaman, $id_pengembalian = null)
    {
        $query = "SELECT 
                    dp.id_detail as id_detail_peminjaman,
                    dp.jumlah as jumlah_pinjam,
                    ms.kode_barang,
                    ms.jumlah_total,
                    b.urutan_unit,
                    jb.sub_barang as nama_barang,
                    tk.id_detail_pengembalian,
                    tk.jumlah_kembali,
                    mkpg.nama_kondisi as kondisi_barang,
                    tk.keterangan_kondisi
                  FROM trx_detail_peminjaman dp
                  JOIN trx_barang b ON dp.id_barang = b.id_barang
                  JOIN mst_spesifikasi ms ON b.id_spesifikasi = ms.id_spesifikasi
                  JOIN mst_jenis_barang jb ON ms.id_jenis_barang = jb.id_jenis_barang
                  LEFT JOIN trx_detail_pengembalian tk ON dp.id_detail = tk.id_detail_peminjaman 
                  AND tk.id_pengembalian = :id_pengembalian
                  LEFT JOIN mst_kondisi_pengembalian mkpg ON tk.id_kondisi_pengembalian = mkpg.id_kondisi_pengembalian
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        $this->db->bind('id_pengembalian', $id_pengembalian);
        return $this->db->resultSet();
    }

    public function getBarangPinjamPreview($id_peminjaman)
    {
        $query = "SELECT 
                    dp.id_detail,
                    dp.jumlah as jumlah_kembali, 
                    'Dipinjam' as kondisi_barang,
                    '-' as keterangan_kondisi,
                    ms.kode_barang,
                    jb.sub_barang as nama_barang
                  FROM trx_detail_peminjaman dp
                  LEFT JOIN trx_barang b ON dp.id_barang = b.id_barang
                  LEFT JOIN mst_spesifikasi ms ON b.id_spesifikasi = ms.id_spesifikasi
                  LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->resultSet();
    }

    public function getItemsForForm($id_peminjaman)
    {
        $query = "SELECT 
                    dp.id_detail,
                    dp.id_barang,
                    dp.jumlah,
                    jb.sub_barang as nama_barang,
                    ms.kode_barang,         
                    ms.jumlah_total,        
                    b.urutan_unit,          
                    ms.spesifikasi_barang,
                    mkpg.nama_kondisi as kondisi_existing,
                    tk.keterangan_kondisi as ket_existing,
                    tk.jumlah_kembali as jml_existing
                  FROM trx_detail_peminjaman dp
                  LEFT JOIN trx_barang b ON dp.id_barang = b.id_barang
                  LEFT JOIN mst_spesifikasi ms ON b.id_spesifikasi = ms.id_spesifikasi 
                  LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
                  LEFT JOIN trx_pengembalian header ON header.id_peminjaman = dp.id_peminjaman
                  LEFT JOIN trx_detail_pengembalian tk ON tk.id_detail_peminjaman = dp.id_detail AND tk.id_pengembalian = header.id_pengembalian
                  LEFT JOIN mst_kondisi_pengembalian mkpg ON tk.id_kondisi_pengembalian = mkpg.id_kondisi_pengembalian
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->resultSet();
    }

    public function getLogRiwayat($id_pengembalian)
    {
        if (empty($id_pengembalian))
            return [];

        $query = "SELECT 
                    tpp.waktu_periksa,
                    tpp.bukti_foto,
                    tdu.nama_user
                  FROM trx_pemeriksa_pengembalian tpp
                  LEFT JOIN trx_user tdu ON tpp.id_user = tdu.id_user
                  WHERE tpp.id_pengembalian = :id
                  ORDER BY tpp.waktu_periksa DESC";

        $this->db->query($query);
        $this->db->bind('id', $id_pengembalian);
        return $this->db->resultSet();
    }


    public function simpanTolakPengembalian($id_peminjaman, $alasan)
    {
        try {
            $this->db->beginTransaction();

            $queryMain = "UPDATE trx_peminjaman SET 
                          id_status_peminjaman = 6, 
                          keterangan_tolak = :ket 
                          WHERE id_peminjaman = :id";

            $pesan_lengkap = "[MASALAH PENGEMBALIAN] " . $alasan;

            $this->db->query($queryMain);
            $this->db->bind('ket', $pesan_lengkap);
            $this->db->bind('id', $id_peminjaman);
            $this->db->execute();

            $this->db->query("SELECT id_pengembalian FROM trx_pengembalian WHERE id_peminjaman = :id");
            $this->db->bind('id', $id_peminjaman);
            $existing = $this->db->single();

            $id_pengembalian = null;

            if ($existing) {
                $id_pengembalian = $existing['id_pengembalian'];
                $this->db->query("UPDATE trx_pengembalian SET id_status_pengembalian = 3 WHERE id_pengembalian = :id"); // 3 = Periksa Ulang
                $this->db->bind('id', $id_pengembalian);
                $this->db->execute();
            } else {
                $this->db->query("INSERT INTO trx_pengembalian (id_peminjaman, id_status_pengembalian) VALUES (:id, 3)"); // 3 = Periksa Ulang
                $this->db->bind('id', $id_peminjaman);
                $this->db->execute();
                $id_pengembalian = $this->db->lastInsertId();
            }

            $this->db->commit();
            return 1;
        } catch (Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }
}