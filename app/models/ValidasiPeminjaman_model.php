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

        $fileName = $dbData['file_surat'];
        $fullPath = __DIR__ . '/../../public/files/surat-peminjaman/' . $fileName; // Use absolute path consistent with other methods

        // Config for automated positioning (side-by-side)
        // Note: We need to calculate absolute positions inside the helper or pass them slightly differently.
        // For simplicity reusing the logic but passing a callback or flag could be cleaner.
        // Here we will use a specific structure for the helper.

        // This method calculates X/Y based on percentage in the Controller/View? 
        // The original method took percX/percY.

        try {
            // Using the new helper, but we need to match the "Side by Side" logic of the original prosesStempelDinamis
            // Since the logic for calculating Huzain's position relative to Fatimah was specific,
            // we will reconstruct it within a simplified structure.

            // To do this cleanly, we might need to know the page size *before* defining coords if we want to determine "Next to it".
            // But the original code calculated it INSIDE the loop. 
            // We will modify the helper to accept a strategy or just keep this method using a specialized call if it's too unique.

            // Actually, let's look at the original `prosesStempelDinamis`.
            // It calculates absolute X/Y from percentage.
            // Then places Fatimah. Then places Huzain.

            // Let's just modernize `prosesStempelDinamis` to be `applyAutoSignatures` and usage in `validasiLaboranDouble` to `applyManualSignatures`.
            // Or better: `processPdfSignatures` that takes a list of signatures.

            // Let's implement `processPdfSignatures` and use it for BOTH.

            // For Custom/Auto (this method): we need to calculate params inside the loop? No, can pass percentages.
            // But strict refactoring might be complex if we don't know page size.
            // Let's stick to cleaning up THIS method to use a shared `initFpdi` and `saveFpdi` at least.

            // Actually, `validasiLaboranDouble` is better written. `prosesStempelDinamis` was a bit rigid.
            // Let's rewrite this to use `processPdfSignatures` by calculating the config.
            // But we don't know page width in mm yet.

            // Strategy: Keep `prosesStempelDinamis` for now but clean it.
            // And clean `validasiLaboranDouble`.
            // OR extract the common FPDI setup/teardown.

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

    // Shared FPDI Loader
    private function loadFpdi($filePath)
    {
        $pathAutoload = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($pathAutoload)) {
            require_once $pathAutoload;
        } else {
            // Fallback logic
            if (file_exists(__DIR__ . '/../core/fpdi/src/autoload.php')) {
                require_once __DIR__ . '/../core/fpdf/fpdf.php';
                require_once __DIR__ . '/../core/fpdi/src/autoload.php';
            }
        }

        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($filePath);
        return [$pdf, $pageCount];
    }

    private function prosesStempelDinamis($filePath, $percX, $percY, $targetPage)
    {
        list($pdf, $pageCount) = $this->loadFpdi($filePath);

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
                // Logic for Huzain Next to Fatimah
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

        if (!file_exists($pathBackup)) {
            if (!copy($pathAsli, $pathBackup)) {
                die("Gagal membuat backup. Cek permission folder.");
            }
        }

        // Use Loop for Manual Positioning
        list($pdf, $pageCount) = $this->loadFpdi($pathBackup);

        $pathFatimah = __DIR__ . '/../../public/img/ttd/ttd_fatimah.png';
        $pathHuzain = __DIR__ . '/../../public/img/ttd/ttd_huzain.png';

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
