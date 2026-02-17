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
        $this->db->beginTransaction();

        if (empty($data['id_user'])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['id_user'])) {
                $data['id_user'] = $_SESSION['id_user'];
            }
        }

        try {
            $query = "INSERT INTO trx_peminjaman 
                      (id_user, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, tanggal_pengembalian, keterangan_peminjaman, status) 
                      VALUES 
                      (:id_user, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, :tanggal_pengembalian, :keterangan_peminjaman, :status)";

            $this->db->query($query);
            $this->db->bind('id_user', $data['id_user']);
            $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
            $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
            $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
            $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
            $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
            $this->db->bind('status', 'Melengkapi Surat');

            $this->db->execute();
            $id_peminjaman = $this->db->lastInsertId();

            $merged_items = [];

            if (isset($data['id_jenis_barang']) && is_array($data['id_jenis_barang'])) {

                foreach ($data['id_jenis_barang'] as $key => $id_jenis) {

                    if (empty($id_jenis))
                        continue;

                    $id_spesifikasi = $data['unit_selected'][$key];
                    $jumlah = (int) $data['jumlah_peminjaman'][$key];

                    $unique_key = $id_jenis . '_' . $id_spesifikasi;

                    if (isset($merged_items[$unique_key])) {
                        $merged_items[$unique_key]['jumlah'] += $jumlah;
                    } else {
                        $merged_items[$unique_key] = [
                            'id_jenis' => $id_jenis,
                            'id_spesifikasi' => $id_spesifikasi,
                            'jumlah' => $jumlah
                        ];
                    }
                }
            }

            foreach ($merged_items as $item) {
                $keterangan_simpan = "REQ_SPEC:" . $item['id_spesifikasi'];

                $queryDetail = "INSERT INTO trx_detail_peminjaman 
                                (id_peminjaman, id_jenis_barang, id_barang, jumlah, keterangan_barang) 
                                VALUES 
                                (:id_peminjaman, :id_jenis_barang, NULL, :jumlah, :ket)";

                $this->db->query($queryDetail);
                $this->db->bind('id_peminjaman', $id_peminjaman);
                $this->db->bind('id_jenis_barang', $item['id_jenis']);
                $this->db->bind('jumlah', $item['jumlah']);
                $this->db->bind('ket', $keterangan_simpan);

                $this->db->execute();
            }

            $this->db->commit();

            // --- NOTIFIKASI WHATSAPP KE GROUP ADMIN ---
            try {
                // 1. Ambil data Peminjam (untuk info nama)
                require_once __DIR__ . '/User_model.php';
                $userModel = new User_model();
                $peminjam = $userModel->profile(['id_user' => $data['id_user']]);

                // 2. Cek Konfigurasi Group ID
                if (defined('FONNTE_GROUP_ID') && FONNTE_GROUP_ID != 'ID_GRUP_WHATSAPP_DISINI') {
                    require_once __DIR__ . '/WhatsApp_model.php';
                    $wa = new WhatsApp_model();

                    // 3. Susun Pesan
                    $message = "*PENGAJUAN PEMINJAMAN BARU*\n\n";
                    $message .= "Halo Tim Admin, ada pengajuan barang baru.\n\n";
                    $message .= "Nama Peminjam: " . $peminjam['nama_user'] . "\n";
                    $message .= "Tanggal: " . date('d-m-Y', strtotime($data['tanggal_peminjaman'])) . "\n";
                    $message .= "Kegiatan: " . $data['judul_kegiatan'] . "\n\n";
                    $message .= "Mohon dicek di website: " . BASEURL . "\n";
                    $message .= "Terima Kasih.";

                    // 4. Kirim ke Group
                    $wa->send(FONNTE_GROUP_ID, $message);
                }
            } catch (Exception $e) {
                // Silent fail agar transaksi tidak batal
            }
            // ------------------------------------

            return $this->db->rowCount();
        } catch (Exception $e) {
            $this->db->rollBack();
            return 0;
        }
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
        $query = "SELECT tp.*, tdu.nama_user AS nama_peminjam, tdu.nim_nip
                  FROM trx_peminjaman tp
                  JOIN trx_data_user tdu ON tp.id_user = tdu.id_user
                  WHERE tp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
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
                            SET 
                                judul_kegiatan = :judul_kegiatan, 
                                tanggal_peminjaman = :tanggal_peminjaman, 
                                tanggal_pengembalian = :tanggal_pengembalian, 
                                keterangan_peminjaman = :keterangan_peminjaman, 
                                status = :status 
                            WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($queryPeminjaman);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('keterangan_peminjaman', $ket_header);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);

        $this->db->execute();
        $rowCountHeader = $this->db->rowCount();

        $this->db->query("DELETE FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        $detail_inserted = 0;

        if (isset($data['id_jenis_barang']) && is_array($data['id_jenis_barang'])) {

            $merged_items = [];

            foreach ($data['id_jenis_barang'] as $i => $id_jenis) {
                if (empty($id_jenis))
                    continue;

                $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : 'NULL';
                $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? (int) $data['jumlah_peminjaman'][$i] : 1;

                $key = $id_jenis . "_" . $raw_unit;

                if (isset($merged_items[$key])) {
                    $merged_items[$key]['jumlah'] += $jumlah;
                } else {
                    $merged_items[$key] = [
                        'id_jenis' => $id_jenis,
                        'id_unit' => ($raw_unit === 'NULL' || $raw_unit === 'Lainnya') ? null : $raw_unit,
                        'jumlah' => $jumlah
                    ];
                }
            }

            $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, id_barang, jumlah) 
                            VALUES (:id_p, :id_b, :id_unit, :jml)";

            foreach ($merged_items as $item) {
                $this->db->query($queryDetail);
                $this->db->bind('id_p', $data['id_peminjaman']);
                $this->db->bind('id_b', $item['id_jenis']);
                $this->db->bind('id_unit', $item['id_unit']);
                $this->db->bind('jml', $item['jumlah']);

                $this->db->execute();
                $detail_inserted++;
            }
        }

        return $rowCountHeader + $detail_inserted;
    }

    public function getDetailDataPeminjaman($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    public function getAllBarang()
    {
        $query = "SELECT 
                mjb.*, 
                (SELECT ms.foto_barang 
                 FROM mst_spesifikasi ms 
                 WHERE ms.id_jenis_barang = mjb.id_jenis_barang 
                 LIMIT 1) as foto_barang
              FROM mst_jenis_barang mjb
              ORDER BY mjb.sub_barang ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function cariBarang($keyword)
    {
        $query = "SELECT 
                mjb.*, 
                (SELECT tb.foto_barang 
                 FROM trx_barang tb 
                 WHERE tb.id_jenis_barang = mjb.id_jenis_barang 
                 LIMIT 1) as foto_barang
              FROM mst_jenis_barang mjb
              WHERE mjb.sub_barang LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function getBarangWhereIn($id_array)
    {
        if (empty($id_array))
            return [];

        $placeholders = implode(',', array_fill(0, count($id_array), '?'));

        $query = "SELECT * FROM mst_jenis_barang WHERE id_jenis_barang IN ($placeholders)";
        $this->db->query($query);

        foreach ($id_array as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        return $this->db->resultSet();
    }








    public function getPeminjamanTerbaruUser($id_user)
    {
        $query = "SELECT tp.*, GROUP_CONCAT(mjb.sub_barang) as sub_barang 
                FROM trx_peminjaman tp
                JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                WHERE tp.id_user = :id_user 
                AND tp.status = 'Melengkapi Surat'
                GROUP BY tp.id_peminjaman
                ORDER BY tp.id_peminjaman DESC";

        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
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
            d.id_detail,
            d.id_jenis_barang,
            d.jumlah, 
            d.id_barang,
            d.keterangan_barang,
            mjb.sub_barang as nama_barang, 
            ms.id_spesifikasi as id_spesifikasi_db,
            ms.spesifikasi_barang as spesifikasi_db
            
          FROM trx_detail_peminjaman d 
          JOIN mst_jenis_barang mjb ON d.id_jenis_barang = mjb.id_jenis_barang 
          LEFT JOIN trx_barang tb ON d.id_barang = tb.id_barang
          LEFT JOIN mst_spesifikasi ms ON tb.id_spesifikasi = ms.id_spesifikasi
          
          WHERE d.id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);
        $results = $this->db->resultSet();

        foreach ($results as &$row) {

            $row['id_spesifikasi'] = '';
            $row['spesifikasi_barang'] = '';

            if (!empty($row['id_barang']) && !empty($row['id_spesifikasi_db'])) {
                $row['id_spesifikasi'] = $row['id_spesifikasi_db'];
                $row['spesifikasi_barang'] = $row['spesifikasi_db'];
            } else if (strpos($row['keterangan_barang'], 'REQ_SPEC:') !== false) {
                $parts = explode(':', $row['keterangan_barang']);
                $specId = end($parts);

                $row['id_spesifikasi'] = $specId;

                $infoSpec = $this->getNamaBarangBySpesifikasi($specId);
                if ($infoSpec) {
                    $row['spesifikasi_barang'] = $infoSpec['spesifikasi_barang'];
                }
            }
        }

        return $results;
    }



    public function getUnitBarangTersedia($id_jenis_barang)
    {
        $query = "SELECT tb.id_barang, tb.urutan_unit, ms.kode_barang, ms.spesifikasi_barang, mkb.kondisi_barang 
              FROM trx_barang tb
              JOIN mst_spesifikasi ms ON tb.id_spesifikasi = ms.id_spesifikasi
              JOIN mst_kondisi_barang mkb ON tb.id_kondisi_barang = mkb.id_kondisi_barang
              WHERE ms.id_jenis_barang = :id 
              AND tb.status_peminjaman = 'Bisa'
              AND mkb.kondisi_barang = 'Baik'";

        $this->db->query($query);
        $this->db->bind('id', $id_jenis_barang);
        return $this->db->resultSet();
    }





    public function getStokTersediaBySpesifikasi($id_spesifikasi)
    {
        $query = "SELECT COUNT(*) as total 
                  FROM trx_barang tb
                  JOIN mst_kondisi_barang mkb ON tb.id_kondisi_barang = mkb.id_kondisi_barang
                  WHERE tb.id_spesifikasi = :id
                  AND mkb.kondisi_barang = 'Baik' 
                  AND tb.status_peminjaman = 'Bisa'";

        $this->db->query($query);
        $this->db->bind('id', $id_spesifikasi);
        $result = $this->db->single();

        return $result['total'] ?? 0;
    }

    public function getNamaBarangBySpesifikasi($id_spesifikasi)
    {
        $query = "SELECT mb.sub_barang, ms.spesifikasi_barang 
                  FROM mst_spesifikasi ms
                  JOIN mst_jenis_barang mb ON ms.id_jenis_barang = mb.id_jenis_barang
                  WHERE ms.id_spesifikasi = :id";
        $this->db->query($query);
        $this->db->bind('id', $id_spesifikasi);
        return $this->db->single();
    }

    public function getSpesifikasiByJenis($id_jenis_barang)
    {
        $query = "SELECT 
                    id_spesifikasi, 
                    spesifikasi_barang, 
                    kode_barang,
                    foto_barang
                  FROM mst_spesifikasi 
                  WHERE id_jenis_barang = :id";

        $this->db->query($query);
        $this->db->bind('id', $id_jenis_barang);
        return $this->db->resultSet();
    }

    public function otomatisasiPilihBarang($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $id_peminjaman);
        $request_details = $this->db->resultSet();

        $berhasil = 0;

        foreach ($request_details as $row) {

            if (empty($row['id_barang']) && strpos($row['keterangan_barang'], 'REQ_SPEC:') !== false) {

                $parts = explode(':', $row['keterangan_barang']);
                $id_spesifikasi = end($parts);
                $jumlah_diminta = (int) $row['jumlah'];

                $queryCari = "SELECT id_barang FROM trx_barang 
                              WHERE id_spesifikasi = :spec 
                              AND status_peminjaman = 'Bisa'
                              AND id_kondisi_barang = 1 
                              ORDER BY urutan_unit ASC
                              LIMIT $jumlah_diminta";

                $this->db->query($queryCari);
                $this->db->bind('spec', $id_spesifikasi);
                $kandidat_barang = $this->db->resultSet();

                if (count($kandidat_barang) < $jumlah_diminta) {
                    return 0;
                }

                foreach ($kandidat_barang as $index => $brg) {
                    $id_barang_fisik = $brg['id_barang'];

                    if ($index == 0) {
                        $queryUpdate = "UPDATE trx_detail_peminjaman 
                                        SET id_barang = :id_brg, 
                                            jumlah = 1,
                                            keterangan_barang = NULL 
                                        WHERE id_detail = :id_detail";

                        $this->db->query($queryUpdate);
                        $this->db->bind('id_brg', $id_barang_fisik);
                        $this->db->bind('id_detail', $row['id_detail']);
                        $this->db->execute();
                    } else {
                        $queryInsert = "INSERT INTO trx_detail_peminjaman 
                                        (id_peminjaman, id_jenis_barang, id_barang, jumlah, keterangan_barang) 
                                        VALUES 
                                        (:id_peminjaman, :id_jenis_barang, :id_barang, 1, NULL)";

                        $this->db->query($queryInsert);
                        $this->db->bind('id_peminjaman', $row['id_peminjaman']);
                        $this->db->bind('id_jenis_barang', $row['id_jenis_barang']);
                        $this->db->bind('id_barang', $id_barang_fisik);
                        $this->db->execute();
                    }

                    $queryLock = "UPDATE trx_barang 
                                  SET status_peminjaman = 'Tidak Bisa', id_status = 1 
                                  WHERE id_barang = :id";
                    $this->db->query($queryLock);
                    $this->db->bind('id', $id_barang_fisik);
                    $this->db->execute();
                }

                $berhasil++;
            }
        }

        return ($berhasil > 0) ? 1 : 0;
    }
}