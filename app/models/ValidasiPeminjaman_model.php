<?php
class ValidasiPeminjaman_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
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

        if ($status == 'dikembalikan' || $status == 'tolak peminjaman') {

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
        $pathHuzain = __DIR__ . '/../../public/img/ttd/ttd_huzain.png';

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
}
