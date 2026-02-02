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

                    if (empty($id_jenis)) continue;

                    $id_spesifikasi = $data['unit_selected'][$key];
                    $jumlah = (int) $data['jumlah_peminjaman'][$key];

                    $unique_key = $id_jenis . '_' . $id_spesifikasi;

                    if (isset($merged_items[$unique_key])) {
                        $merged_items[$unique_key]['jumlah'] += $jumlah;
                    } else {
                        $merged_items[$unique_key] = [
                            'id_jenis'       => $id_jenis,
                            'id_spesifikasi' => $id_spesifikasi,
                            'jumlah'         => $jumlah
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

    public function getDetailValidasiDataPeminjaman($id_peminjaman)
    {
        $query = "SELECT tp.*, 
                        tdu.nama_user, 
                        tdu.nim_nip,
                        GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang,
                        SUM(tdp.jumlah) as jumlah_peminjaman,
                        tp.keterangan_peminjaman as alasan_penolakan,
                        peng.status_pengembalian
                FROM trx_peminjaman tp
                JOIN trx_data_user tdu ON tp.id_user = tdu.id_user  
                LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                LEFT JOIN trx_pengembalian peng ON tp.id_peminjaman = peng.id_peminjaman
                
                WHERE tp.id_peminjaman = :id_peminjaman
                GROUP BY tp.id_peminjaman, tdu.nama_user, tdu.nim_nip, peng.status_pengembalian";

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
                if (empty($id_jenis)) continue;

                $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : 'NULL';
                $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? (int)$data['jumlah_peminjaman'][$i] : 1;

                $key = $id_jenis . "_" . $raw_unit;

                if (isset($merged_items[$key])) {
                    $merged_items[$key]['jumlah'] += $jumlah;
                } else {
                    $merged_items[$key] = [
                        'id_jenis' => $id_jenis,
                        'id_unit'  => ($raw_unit === 'NULL' || $raw_unit === 'Lainnya') ? null : $raw_unit,
                        'jumlah'   => $jumlah
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

        if ($status == 'tolak peminjaman') {
            $query .= ", keterangan_tolak = :keterangan";
        }

        $query .= " WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('status', $status);
        $this->db->bind('id_peminjaman', $id_peminjaman);

        if ($status == 'tolak peminjaman') {
            $pesan = empty($catatan) ? '-' : $catatan;
            $this->db->bind('keterangan', $pesan);
        }

        $this->db->execute();

        if ($status == 'dikembalikan') {

            $this->db->query("SELECT id_barang FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
            $this->db->bind('id', $id_peminjaman);
            $items = $this->db->resultSet();

            foreach ($items as $item) {
                if (!empty($item['id_barang'])) {

                    $queryRestore = "UPDATE trx_barang 
                                     SET status_peminjaman = 'Bisa', 
                                         id_status = 3
                                     WHERE id_barang = :idb";

                    $this->db->query($queryRestore);
                    $this->db->bind('idb', $item['id_barang']);
                    $this->db->execute();
                }
            }
        }

        return $this->db->rowCount();
    }

    public function getValidasiGabungan()
    {
        $query = "SELECT tp.*, 
                        tdu.nama_user, 
                        GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang 
                FROM trx_peminjaman tp
                JOIN trx_data_user tdu ON tp.id_user = tdu.id_user  
                LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                
                WHERE 
                    tp.status IN ('diproses', 'disetujui', 'Tolak Pengembalian') 
                
                GROUP BY tp.id_peminjaman, tdu.nama_user
                
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
            }
            
            else if (strpos($row['keterangan_barang'], 'REQ_SPEC:') !== false) {
                $parts = explode(':', $row['keterangan_barang']);
                $specId = end($parts);
                
                $row['id_spesifikasi'] = $specId;
                
                $infoSpec = $this->getNamaBarangBySpesifikasi($specId);
                if($infoSpec) {
                    $row['spesifikasi_barang'] = $infoSpec['spesifikasi_barang'];
                }
            }
        }

        return $results;
    }

    public function simpanTolakPengembalian($id_peminjaman, $alasan)
    {
        try {
            $this->db->beginTransaction();

            $queryMain = "UPDATE trx_peminjaman SET 
                          status = 'Tolak Pengembalian', 
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
                $this->db->query("UPDATE trx_pengembalian SET status_pengembalian = 'Periksa Ulang' WHERE id_pengembalian = :id");
                $this->db->bind('id', $id_pengembalian);
                $this->db->execute();
            } else {
                $this->db->query("INSERT INTO trx_pengembalian (id_peminjaman, status_pengembalian) VALUES (:id, 'Periksa Ulang')");
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

    public function getCekValidasiKalab($id)
    {
        $this->db->query("SELECT validasi_kalab FROM trx_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $id);
        $res = $this->db->single();
        return $res['validasi_kalab'] ?? '0';
    }

    public function validasiKalab($id_peminjaman)
    {
        $query = "UPDATE trx_peminjaman SET validasi_kalab = '1' WHERE id_peminjaman = :id";
        $this->db->query($query);
        $this->db->bind('id', $id_peminjaman);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function validasiLaboranCustom($data)
    {
        $id_peminjaman = $data['id_peminjaman'];

        $this->db->query("SELECT file_surat FROM trx_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $id_peminjaman);
        $dbData = $this->db->single();

        $fullPath = '../public/files/surat-peminjaman/' . $dbData['file_surat'];

        try {
            $this->prosesStempelDinamis(
                $fullPath,
                $data['pos_x'],
                $data['pos_y'],
                $data['page']
            );

            $query = "UPDATE trx_peminjaman SET 
                      validasi_laboran = '1', 
                      status = 'disetujui' 
                      WHERE id_peminjaman = :id";

            $this->db->query($query);
            $this->db->bind('id', $id_peminjaman);
            $this->db->execute();

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function prosesStempelDinamis($filePath, $percX, $percY, $targetPage)
    {
        $pathAutoload = __DIR__ . '/../../vendor/autoload.php';

        if (file_exists($pathAutoload)) {
            require_once $pathAutoload;
        } else {
            if (file_exists(__DIR__ . '/../core/fpdi/src/autoload.php')) {
                require_once __DIR__ . '/../core/fpdf/fpdf.php';
                require_once __DIR__ . '/../core/fpdi/src/autoload.php';
            } else {
                die("Error: Library FPDI tidak ditemukan di " . $pathAutoload);
            }
        }

        $pdf = new \setasign\Fpdi\Fpdi();

        try {
            $pageCount = $pdf->setSourceFile($filePath);
        } catch (Exception $e) {
            return 0;
        }

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            if ($i == $targetPage) {
                $widthMM = $size['width'];
                $heightMM = $size['height'];

                $absX = $widthMM * $percX;
                $absY = $heightMM * $percY;

                $ttdWidth = 35;

                $pathTTD_Fatimah = __DIR__ . '/../../public/img/ttd/ttd_fatimah.png';

                if (file_exists($pathTTD_Fatimah)) {
                    $pdf->Image($pathTTD_Fatimah, $absX, $absY, $ttdWidth);
                }

                $pathTTD_Huzain = __DIR__ . '/../../public/img/ttd/ttd_huzain.png';
                $posX_Huzain = $absX + 45;

                if (($posX_Huzain + $ttdWidth) > $widthMM) {
                    $posX_Huzain = $widthMM - $ttdWidth - 10;
                }

                if (file_exists($pathTTD_Huzain)) {
                    $pdf->Image($pathTTD_Huzain, $posX_Huzain, $absY, $ttdWidth);
                }
            }
        }

        $pdf->Output($filePath, 'F');
    }

    public function validasiLaboranDouble($data)
    {
        $id = $data['id_peminjaman'];

        $this->db->query("SELECT file_surat FROM trx_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $id);
        $res = $this->db->single();

        $fileName = $res['file_surat'];
        $pathFolder = __DIR__ . '/../../public/files/surat-peminjaman/';

        $pathAsli = $pathFolder . $fileName;
        $pathBackup = $pathFolder . 'backup_' . $fileName;

        $pathFatimah = __DIR__ . '/../../public/img/ttd/ttd_fatimah.png';
        $pathHuzain  = __DIR__ . '/../../public/img/ttd/ttd_huzain.png';

        if (!file_exists($pathBackup)) {
            if (!copy($pathAsli, $pathBackup)) {
                die("Gagal membuat backup. Cek permission folder.");
            }
        }

        require_once __DIR__ . '/../../vendor/autoload.php';

        $pdf = new \setasign\Fpdi\Fpdi();

        $pageCount = $pdf->setSourceFile($pathBackup);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            $widthMM = $size['width'];
            $heightMM = $size['height'];
            $ttdWidth = 35; 

            if ($i == $data['fatimah_page']) {
                $fx = $widthMM * $data['fatimah_x'];
                $fy = $heightMM * $data['fatimah_y'];

                if (file_exists($pathFatimah)) {
                    $pdf->Image($pathFatimah, $fx, $fy, $ttdWidth);
                }
            }

            if ($i == $data['huzain_page']) {
                $hx = $widthMM * $data['huzain_x'];
                $hy = $heightMM * $data['huzain_y'];

                if (file_exists($pathHuzain)) {
                    $pdf->Image($pathHuzain, $hx, $hy, $ttdWidth);
                }
            }
        }

        $pdf->Output($pathAsli, 'F');

        return 1;
    }

    public function finalisasiValidasi($id_peminjaman)
    {
        $query = "UPDATE trx_peminjaman SET 
                    validasi_kalab = '1', 
                    validasi_laboran = '1', 
                    status = 'disetujui' 
                    WHERE id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('id', $id_peminjaman);
        $this->db->execute();

        return $this->db->rowCount();
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