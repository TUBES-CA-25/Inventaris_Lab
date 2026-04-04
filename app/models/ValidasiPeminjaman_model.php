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
        $query = "SELECT tp.id_peminjaman, tp.id_user, tp.id_jenis_peminjaman, tp.judul_kegiatan, 
                        tp.tanggal_pengajuan, tp.tanggal_peminjaman, tp.tanggal_pengembalian, 
                        tp.keterangan_peminjaman, tp.keterangan_tolak, tp.id_status_peminjaman, 
                        tp.file_surat, tp.validasi_kalab,
                        tpi.validasi_laboran,
                        tdu.nama_user, 
                        tdu.nim_nip,
                        tpa.dosen_pembimbing, tpa.kategori_kegiatan, tpa.validasi_dosen,
                        GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang,
                        SUM(tdp.jumlah) as jumlah_peminjaman,
                        tp.keterangan_peminjaman as alasan_penolakan,
                        mspg.nama_status_pengembalian AS status_pengembalian,
                        msp.nama_status as status
                FROM trx_peminjaman tp
                JOIN trx_user tdu ON tp.id_user = tdu.id_user  
                LEFT JOIN mst_status_peminjaman msp ON tp.id_status_peminjaman = msp.id_status_peminjaman
                LEFT JOIN trx_peminjaman_akademik tpa ON tp.id_peminjaman = tpa.id_peminjaman
                LEFT JOIN trx_peminjaman_internal tpi ON tp.id_peminjaman = tpi.id_peminjaman
                LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                LEFT JOIN trx_pengembalian peng ON tp.id_peminjaman = peng.id_peminjaman
                LEFT JOIN mst_status_pengembalian mspg ON peng.id_status_pengembalian = mspg.id_status_pengembalian
                
                WHERE tp.id_peminjaman = :id_peminjaman
                GROUP BY tp.id_peminjaman, tp.id_user, tp.id_jenis_peminjaman, tp.judul_kegiatan, tp.tanggal_pengajuan, tp.tanggal_peminjaman, tp.tanggal_pengembalian, tp.keterangan_peminjaman, tp.keterangan_tolak, tp.id_status_peminjaman, tp.file_surat, tp.validasi_kalab, tpi.validasi_laboran, tdu.nama_user, tdu.nim_nip, mspg.nama_status_pengembalian, msp.nama_status, tpa.dosen_pembimbing, tpa.kategori_kegiatan, tpa.validasi_dosen";

        $this->db->query($query);
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    public function updateStatusValidasi($id_peminjaman, $status, $catatan = null)
    {
        $statusMap = [
            'melengkapi surat' => 1,
            'diproses' => 2,
            'disetujui' => 3,
            'tolak peminjaman' => 4,
            'dikembalikan' => 5,
            'tolak pengembalian' => 6
        ];

        $statusId = $statusMap[strtolower($status)] ?? 2;

        $query = "UPDATE trx_peminjaman SET id_status_peminjaman = :statusId";

        if ($statusId == 4) { // Tolak
            $query .= ", keterangan_tolak = :keterangan";
        }

        $query .= " WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('statusId', $statusId);
        $this->db->bind('id_peminjaman', $id_peminjaman);

        if ($statusId == 4) {
            $pesan = empty($catatan) ? '-' : $catatan;
            $this->db->bind('keterangan', $pesan);
        }

        $this->db->execute();

        // --- NOTIFIKASI WA MAHASISWA (DITOLAK) ---
        if ($statusId == 4 || $statusId == 6) {
            try {
                $this->db->query("SELECT u.no_hp_user, u.nama_user, p.judul_kegiatan 
                                  FROM trx_peminjaman p 
                                  JOIN trx_user u ON p.id_user = u.id_user 
                                  WHERE p.id_peminjaman = :id");
                $this->db->bind('id', $id_peminjaman);
                $info = $this->db->single();

                if ($info && !empty($info['no_hp_user'])) {
                    require_once __DIR__ . '/WhatsApp_model.php';
                    $wa = new WhatsApp_model();
                    $alasan = empty($catatan) ? 'Tidak ada alasan.' : $catatan;
                    $msg = "Halo *{$info['nama_user']}*,\n\nMohon maaf, pengajuan peminjaman Anda untuk kegiatan *{$info['judul_kegiatan']}* telah *DITOLAK*.\nAlasan: {$alasan}\n\nTerima kasih.";
                    $wa->send($info['no_hp_user'], $msg);
                }
            } catch (Exception $e) {}
        }

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

    public function autoCancelExpiredLoans()
    {
        $today = date('Y-m-d');
        
        // Find loans with status 1 (Melengkapi Surat) or 2 (Diproses) where tanggal_peminjaman < today (H+1)
        $query = "SELECT id_peminjaman FROM trx_peminjaman 
                  WHERE id_status_peminjaman IN (1, 2) 
                  AND tanggal_peminjaman < :today";
        $this->db->query($query);
        $this->db->bind('today', $today);
        $expired = $this->db->resultSet();
        
        if (empty($expired)) return 0;
        
        $count = 0;
        foreach ($expired as $loan) {
            $id = $loan['id_peminjaman'];
            
            // 1. Restore items if any were locked (Status 1/2 shouldn't usually have items, but safety first)
            $this->db->query("SELECT id_barang FROM trx_detail_peminjaman WHERE id_peminjaman = :id AND id_barang IS NOT NULL");
            $this->db->bind('id', $id);
            $items = $this->db->resultSet();
            
            foreach ($items as $item) {
                $this->db->query("UPDATE trx_barang SET status_peminjaman = 'Bisa', id_status = 3 WHERE id_barang = :idb");
                $this->db->bind('idb', $item['id_barang']);
                $this->db->execute();
            }
            
            // 2. Update status to Tolak Peminjaman (4)
            $this->db->query("UPDATE trx_peminjaman SET 
                              id_status_peminjaman = 4, 
                              keterangan_tolak = 'Otomatis dibatalkan: Melewati batas waktu validasi (H+1 dari tanggal peminjaman).' 
                              WHERE id_peminjaman = :id");
            $this->db->bind('id', $id);
            $this->db->execute();
            $count++;
        }
        
        return $count;
    }

    public function getValidasiGabungan($role = null, $nama_user = null)
    {
        $this->autoCancelExpiredLoans(); // Run cleanup every time list is fetched

        $query = "SELECT tp.id_peminjaman, tp.id_user, tp.id_jenis_peminjaman, tp.judul_kegiatan, 
                        tp.tanggal_pengajuan, tp.tanggal_peminjaman, tp.tanggal_pengembalian, 
                        tdu.nama_user, 
                        msp.nama_status AS status,
                        GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang 
                FROM trx_peminjaman tp
                JOIN trx_user tdu ON tp.id_user = tdu.id_user  
                JOIN mst_status_peminjaman msp ON tp.id_status_peminjaman = msp.id_status_peminjaman
                LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                LEFT JOIN trx_peminjaman_akademik tpa ON tp.id_peminjaman = tpa.id_peminjaman
                
                WHERE 
                    tp.id_status_peminjaman IN (2, 3, 6) ";

        if ($role == '5') {
            $query .= " AND tpa.dosen_pembimbing = :dosen ";
        } elseif ($role == '2') {
            $query .= " AND tp.id_jenis_peminjaman = 2 ";
        }

        $query .= " GROUP BY tp.id_peminjaman, tdu.nama_user, msp.nama_status
                
                ORDER BY 
                    CASE 
                        WHEN tp.id_status_peminjaman = 2 THEN 1 
                        WHEN tp.id_status_peminjaman = 3 THEN 2 
                        ELSE 3
                    END ASC,
                    tp.tanggal_pengajuan DESC";

        $this->db->query($query);
        if ($role == '5') {
            $this->db->bind('dosen', $nama_user);
        }
        return $this->db->resultSet();
    }

    public function hitungStatus($status, $role = null, $nama_user = null)
    {
        $statusMap = [
            'melengkapi surat' => 1,
            'diproses' => 2,
            'disetujui' => 3,
            'tolak peminjaman' => 4,
            'ditolak' => 4,
            'dikembalikan' => 5,
            'tolak pengembalian' => 6
        ];
        $statusId = $statusMap[strtolower($status)] ?? 0;

        $query = "SELECT COUNT(DISTINCT tp.id_peminjaman) as total 
                  FROM trx_peminjaman tp
                  LEFT JOIN trx_peminjaman_akademik tpa ON tp.id_peminjaman = tpa.id_peminjaman
                  WHERE tp.id_status_peminjaman = :sid";

        if ($role == '5') {
            $query .= " AND tpa.dosen_pembimbing = :dosen ";
        } elseif ($role == '2') {
            $query .= " AND tp.id_jenis_peminjaman = 2 ";
        }

        $this->db->query($query);
        $this->db->bind('sid', $statusId);
        if ($role == '5') {
            $this->db->bind('dosen', $nama_user);
        }

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

            // Fetch loan data to check type
            $loan = $this->getDetailValidasiDataPeminjaman($id_peminjaman);
            $role = $_SESSION['id_role'];

            // Update flags based on role
            if ($role == '2') { // Laboran
                $this->db->query("UPDATE trx_peminjaman_internal SET validasi_laboran = '1' WHERE id_peminjaman = :id");
                $this->db->bind('id', $id_peminjaman);
                $this->db->execute();
            } elseif ($role == '1') { // Kalab
                $this->db->query("UPDATE trx_peminjaman SET validasi_kalab = '1' WHERE id_peminjaman = :id");
                $this->db->bind('id', $id_peminjaman);
                $this->db->execute();
            }

            // Determine if final status update is needed
            // Academic (1): needs Dosen (Role 5) AND Kalab (Role 1). Kalab is final.
            // Internal (2): needs Laboran (Role 2). Laboran is final.
            $isFinal = false;
            if ($loan['id_jenis_peminjaman'] == 2 && $role == '2') $isFinal = true;
            if ($loan['id_jenis_peminjaman'] == 1 && $role == '1') $isFinal = true;

            if ($isFinal) {
                $this->db->query("UPDATE trx_peminjaman SET id_status_peminjaman = 3 WHERE id_peminjaman = :id");
                $this->db->bind('id', $id_peminjaman);
                $this->db->execute();
            }

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

        // Use pathAsli as source so existing signatures (from sequential steps) are preserved.
        // pathBackup is kept as a reference to the original clean document.
        list($pdf, $pageCount) = $this->loadFpdi($pathAsli);

        $pathFatimah = __DIR__ . '/../../public/img/ttd/ttd_fatimah.png';
        $pathHuzain = __DIR__ . '/../../public/img/ttd/ttd_huzain.png';

        // Determine which box(es) to process based on role and type
        $loanData = $this->getDetailValidasiDataPeminjaman($id);
        $role = $_SESSION['id_role'];
        $id_jenis = $loanData['id_jenis_peminjaman'];

        $shouldPlaceFatimah = false;
        $shouldPlaceHuzain = false;

        $isDosen = isset($_SESSION['id_role']) && $_SESSION['id_role'] == '5';
        $pathDosen = '';
        if ($isDosen) {
            $this->db->query("SELECT file_ttd FROM trx_user WHERE id_user = :id");
            $this->db->bind('id', $_SESSION['id_user']);
            $userTTD = $this->db->single();
            if ($userTTD && !empty($userTTD['file_ttd'])) {
                $pathDosen = __DIR__ . '/../../public/img/ttd/' . $userTTD['file_ttd'];
            }
        }

        if ($id_jenis == 1) { // Academic
            if ($role == '5') $shouldPlaceFatimah = true;
            if ($role == '1') $shouldPlaceHuzain = true;
        } else { // Internal
            if ($role == '2') {
                $shouldPlaceFatimah = true;
                $shouldPlaceHuzain = true;
            }
        }

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            $widthMM = $size['width'];
            $heightMM = $size['height'];
            $ttdWidth = 35;

            // Place Fatimah/Dosenbox if enabled and page matches
            if ($shouldPlaceFatimah && $i == $data['fatimah_page'] && $data['fatimah_x'] > 0) {
                $fx = $widthMM * $data['fatimah_x'];
                $fy = $heightMM * $data['fatimah_y'];

                if ($isDosen) {
                    if (!empty($pathDosen) && file_exists($pathDosen)) {
                        $pdf->Image($pathDosen, $fx, $fy, $ttdWidth);
                    }
                } else {
                    if (file_exists($pathFatimah)) {
                        $pdf->Image($pathFatimah, $fx, $fy, $ttdWidth);
                    }
                }
            }

            // Place Huzain/Kalab box if enabled and page matches
            if ($shouldPlaceHuzain && $i == $data['huzain_page'] && $data['huzain_x'] > 0) {
                $hx = $widthMM * $data['huzain_x'];
                $hy = $heightMM * $data['huzain_y'];
                if (file_exists($pathHuzain)) {
                    $pdf->Image($pathHuzain, $hx, $hy, $ttdWidth);
                }
            }
        }

        $pdf->Output($pathAsli, 'F');

        // Identify current signer and loan type
        $loanData = $this->getDetailValidasiDataPeminjaman($id);
        $role = $_SESSION['id_role'];
        $id_jenis = $loanData['id_jenis_peminjaman'];

        // Update validation flags based on role and type
        if ($id_jenis == 1) { // Academic
            if ($role == '5') { // Dosen
                $this->db->query("UPDATE trx_peminjaman_akademik SET validasi_dosen = '1' WHERE id_peminjaman = :id");
            } elseif ($role == '1') { // Kalab
                $this->db->query("UPDATE trx_peminjaman SET validasi_kalab = '1' WHERE id_peminjaman = :id");
            }
        } else { // Internal
            if ($role == '2') { // Laboran (Signs both)
                $this->db->query("UPDATE trx_peminjaman_internal SET validasi_laboran = '1' WHERE id_peminjaman = :id");
                // In internal loans, Kalab validation is already '1' from Step 1 (Button)
            }
        }
        $this->db->bind('id', $id);
        $this->db->execute();

        return 1;
    }

    public function finalisasiValidasi($id_peminjaman)
    {
        $this->db->query("UPDATE trx_peminjaman SET 
                    validasi_kalab = '1', 
                    id_status_peminjaman = 3 
                    WHERE id_peminjaman = :id");
        $this->db->bind('id', $id_peminjaman);
        $this->db->execute();

        // Fetch loan type
        $p = $this->getDetailValidasiDataPeminjaman($id_peminjaman);
        
        if ($p['id_jenis_peminjaman'] == 1) {
            $this->db->query("UPDATE trx_peminjaman_akademik SET validasi_dosen = '1' WHERE id_peminjaman = :id");
        } else {
            $this->db->query("UPDATE trx_peminjaman_internal SET validasi_laboran = '1' WHERE id_peminjaman = :id");
        }
        $this->db->bind('id', $id_peminjaman);
        $this->db->execute();

        // --- NOTIFIKASI WA MAHASISWA (DISETUJUI) ---
        try {
            $this->db->query("SELECT u.no_hp_user, u.nama_user, p.judul_kegiatan 
                              FROM trx_peminjaman p 
                              JOIN trx_user u ON p.id_user = u.id_user 
                              WHERE p.id_peminjaman = :id");
            $this->db->bind('id', $id_peminjaman);
            $info = $this->db->single();

            if ($info && !empty($info['no_hp_user'])) {
                require_once __DIR__ . '/WhatsApp_model.php';
                $wa = new WhatsApp_model();
                $msg = "Halo *{$info['nama_user']}*,\n\nPengajuan peminjaman Anda untuk kegiatan *{$info['judul_kegiatan']}* telah *DISETUJUI*.\n\nSilakan ambil barang di laboratorium sesuai jadwal.\nTerima kasih.";
                $wa->send($info['no_hp_user'], $msg);
            }
        } catch (Exception $e) {}

        return $this->db->rowCount();
    }
}
