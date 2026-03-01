<?php

class Beranda_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllCounts()
    {
        $query = "SELECT 
                    (SELECT COUNT(*) FROM mst_jenis_barang) as jml_jenis,
                    (SELECT COUNT(*) FROM trx_peminjaman) as jml_peminjaman,
                    (SELECT COUNT(*) FROM mst_merek_barang) as jml_merek,
                    (SELECT COUNT(*) FROM trx_barang) as jml_barang, 
                    (SELECT COUNT(*) FROM trx_pengembalian) as jml_pengembalian,
                    (SELECT COUNT(*) FROM trx_peminjaman WHERE id_status_peminjaman = 2) as jml_peminjaman_proses,
                    (SELECT COUNT(*) FROM trx_peminjaman WHERE YEARWEEK(tanggal_peminjaman, 1) = YEARWEEK(CURDATE(), 1)) as jml_peminjaman_minggu";
        $this->db->query($query);
        return $this->db->single();
    }

    public function getChartDataFiltered($mode, $tahun, $bulan = null)
    {
        $data = [
            'peminjaman' => [],
            'pengembalian' => [],
            'bagus' => [],
            'rusak' => []
        ];

        // Konfigurasi Loop & Grouping
        if ($mode == 'harian') {
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $loopStart = 1;
            $loopEnd = $jmlHari;
            $groupBy = "DAY";
        } elseif ($mode == 'bulanan') {
            $loopStart = 1;
            $loopEnd = 12;
            $groupBy = "MONTH";
        } else { // tahunan
            $loopStart = $tahun - 4;
            $loopEnd = $tahun;
            $groupBy = "YEAR";
        }

        // Inisialisasi Array 0
        $labels = [];
        $monthNames = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];

        for ($i = $loopStart; $i <= $loopEnd; $i++) {
            // Jika bulanan, ubah angka jadi nama bulan
            if ($mode == 'bulanan') {
                $labels[] = $monthNames[$i];
            } else {
                $labels[] = $i;
            }

            $data['peminjaman'][$i] = 0;
            $data['pengembalian'][$i] = 0;
            $data['bagus'][$i] = 0;
            $data['rusak'][$i] = 0;
        }

        // --- QUERY 1: Peminjaman (trx_peminjaman) ---
        $sql = "SELECT $groupBy(tanggal_peminjaman) as waktu, COUNT(*) as total 
                FROM trx_peminjaman WHERE 1=1 ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tanggal_peminjaman) = '$bulan' AND YEAR(tanggal_peminjaman) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tanggal_peminjaman) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tanggal_peminjaman) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tanggal_peminjaman)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['peminjaman'][$idx]))
                $data['peminjaman'][$idx] = intval($row['total']);
        }

        // --- QUERY 2: Pengembalian (trx_pengembalian) ---
        // Menggunakan tgl_pengembalian_aktual dari tabel trx_pengembalian
        $sql = "SELECT $groupBy(tgl_pengembalian_aktual) as waktu, COUNT(*) as total 
                FROM trx_pengembalian WHERE tgl_pengembalian_aktual IS NOT NULL ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengembalian_aktual) = '$bulan' AND YEAR(tgl_pengembalian_aktual) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengembalian_aktual) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tgl_pengembalian_aktual) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengembalian_aktual)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['pengembalian'][$idx]))
                $data['pengembalian'][$idx] = intval($row['total']);
        }

        // --- QUERY 3: Barang Bagus (trx_barang) ---
        // Menggunakan COUNT(*) karena 1 baris = 1 barang
        $sql = "SELECT $groupBy(tgl_pengadaan_barang) as waktu, COUNT(*) as total 
                FROM trx_barang 
                WHERE id_kondisi_barang = 1 ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengadaan_barang) = '$bulan' AND YEAR(tgl_pengadaan_barang) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengadaan_barang) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tgl_pengadaan_barang) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengadaan_barang)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['bagus'][$idx]))
                $data['bagus'][$idx] = intval($row['total']);
        }

        // --- QUERY 4: Barang Rusak (trx_barang) ---
        $sql = "SELECT $groupBy(tgl_pengadaan_barang) as waktu, COUNT(*) as total 
                FROM trx_barang 
                WHERE id_kondisi_barang != 1 ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengadaan_barang) = '$bulan' AND YEAR(tgl_pengadaan_barang) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengadaan_barang) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tgl_pengadaan_barang) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengadaan_barang)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['rusak'][$idx]))
                $data['rusak'][$idx] = intval($row['total']);
        }

        $pengadaan = [];
        for ($i = $loopStart; $i <= $loopEnd; $i++) {
            $pengadaan[] = ($data['bagus'][$i] ?? 0) + ($data['rusak'][$i] ?? 0);
        }

        return [
            'labels' => $labels,
            'peminjaman' => array_values($data['peminjaman']),
            'pengembalian' => array_values($data['pengembalian']),
            'bagus' => array_values($data['bagus']),
            'rusak' => array_values($data['rusak']),
            'total_barang_baru' => $pengadaan,
        ];
    }

    public function getStudentStats($id_user)
    {
        // Peminjaman Berlangsung (Status 1: Melengkapi, 2: Diproses, 3: Disetujui, 6: Tolak Pengembalian)
        // Kita hitung yang belum 'Dikembalikan' (5) dan tidak 'Ditolak Peminjaman' (4)
        $query = "SELECT 
                    SUM(CASE WHEN id_status_peminjaman IN (1, 2, 3, 6) THEN 1 ELSE 0 END) as ongoing,
                    SUM(CASE WHEN id_status_peminjaman IN (3, 6) AND tanggal_pengembalian < CURDATE() THEN 1 ELSE 0 END) as overdue
                  FROM trx_peminjaman 
                  WHERE id_user = :id_user";

        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        return $this->db->single();
    }

    public function getRecentLoans($id_user, $limit = 3)
    {
        $query = "SELECT p.id_peminjaman, p.judul_kegiatan, p.tanggal_peminjaman, p.tanggal_pengembalian, 
                         msp.nama_status AS status
                  FROM trx_peminjaman p
                  JOIN mst_status_peminjaman msp ON p.id_status_peminjaman = msp.id_status_peminjaman
                  WHERE p.id_user = :id_user
                  ORDER BY p.tanggal_peminjaman DESC
                  LIMIT :limit";

        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    // --- LABORAN DASHBOARD METHODS ---

    /**
     * Get loans that are pending (Status 2: Diproses)
     */
    public function getPeminjamanPending($limit = 5)
    {
        $query = "SELECT tp.id_peminjaman, tp.judul_kegiatan, tp.tanggal_pengajuan, tdu.nama_user as peminjam
                  FROM trx_peminjaman tp
                  JOIN trx_data_user tdu ON tp.id_user = tdu.id_user
                  WHERE tp.id_status_peminjaman = 2
                  ORDER BY tp.tanggal_pengajuan ASC
                  LIMIT :limit";
        $this->db->query($query);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get returns that are verified (Status Pengembalian 1: Selesai Periksa)
     * but not yet finalized (Status Peminjaman still 3/6)
     */
    public function getPengembalianVerified($limit = 5)
    {
        $query = "SELECT tp.id_peminjaman, tp.judul_kegiatan, peng.tgl_pengembalian_aktual, tdu.nama_user as peminjam
                  FROM trx_peminjaman tp
                  JOIN trx_pengembalian peng ON tp.id_peminjaman = peng.id_peminjaman
                  JOIN trx_data_user tdu ON tp.id_user = tdu.id_user
                  WHERE peng.id_status_pengembalian = 1 AND tp.id_status_peminjaman IN (3, 6)
                  ORDER BY peng.tgl_pengembalian_aktual ASC
                  LIMIT :limit";
        $this->db->query($query);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    public function getStudentChartData($id_user, $mode, $tahun, $bulan = null)
    {
        $data = [
            'peminjaman' => [],
            'pengembalian' => []
        ];

        if ($mode == 'harian') {
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $loopStart = 1;
            $loopEnd = $jmlHari;
            $groupBy = "DAY";
        } elseif ($mode == 'bulanan') {
            $loopStart = 1;
            $loopEnd = 12;
            $groupBy = "MONTH";
        } else {
            $loopStart = $tahun - 4;
            $loopEnd = $tahun;
            $groupBy = "YEAR";
        }

        $labels = [];
        $monthNames = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];

        for ($i = $loopStart; $i <= $loopEnd; $i++) {
            if ($mode == 'bulanan') {
                $labels[] = $monthNames[$i];
            } else {
                $labels[] = $i;
            }
            $data['peminjaman'][$i] = 0;
            $data['pengembalian'][$i] = 0;
        }

        // Peminjaman
        $sql = "SELECT $groupBy(tanggal_peminjaman) as waktu, COUNT(*) as total 
                FROM trx_peminjaman WHERE id_user = :id_user ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tanggal_peminjaman) = '$bulan' AND YEAR(tanggal_peminjaman) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tanggal_peminjaman) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tanggal_peminjaman) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tanggal_peminjaman)";

        $this->db->query($sql);
        $this->db->bind('id_user', $id_user);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['peminjaman'][$idx]))
                $data['peminjaman'][$idx] = intval($row['total']);
        }

        // Pengembalian
        $sql = "SELECT $groupBy(tgl_pengembalian_aktual) as waktu, COUNT(*) as total 
                FROM trx_pengembalian pen
                JOIN trx_peminjaman p ON pen.id_peminjaman = p.id_peminjaman
                WHERE p.id_user = :id_user AND tgl_pengembalian_aktual IS NOT NULL ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengembalian_aktual) = '$bulan' AND YEAR(tgl_pengembalian_aktual) = '$tahun' ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengembalian_aktual) = '$tahun' ";
        } else {
            $sql .= "AND YEAR(tgl_pengembalian_aktual) BETWEEN '$loopStart' AND '$loopEnd' ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengembalian_aktual)";

        $this->db->query($sql);
        $this->db->bind('id_user', $id_user);
        foreach ($this->db->resultSet() as $row) {
            $idx = intval($row['waktu']);
            if (isset($data['pengembalian'][$idx]))
                $data['pengembalian'][$idx] = intval($row['total']);
        }

        return [
            'labels' => $labels,
            'peminjaman' => array_values($data['peminjaman']),
            'pengembalian' => array_values($data['pengembalian'])
        ];
    }

    // --- ASSISTANT DASHBOARD METHODS ---

    public function getAssistantStats()
    {
        $query = "SELECT 
                    SUM(CASE WHEN id_status_peminjaman IN (1, 2, 3, 6) THEN 1 ELSE 0 END) as ongoing,
                    SUM(CASE WHEN id_status_peminjaman IN (3, 6) AND tanggal_pengembalian < CURDATE() THEN 1 ELSE 0 END) as overdue
                  FROM trx_peminjaman";

        $this->db->query($query);
        return $this->db->single();
    }

    public function getDamagedGoodsPaged($limit, $offset)
    {
        $query = "SELECT 
                    b.id_barang,
                    b.urutan_unit,
                    spek.spesifikasi_barang,
                    spek.kode_barang,
                    mjb.sub_barang,
                    mkb.kondisi_barang
                  FROM trx_barang b
                  JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                  JOIN mst_jenis_barang mjb ON spek.id_jenis_barang = mjb.id_jenis_barang
                  JOIN mst_kondisi_barang mkb ON b.id_kondisi_barang = mkb.id_kondisi_barang
                  WHERE b.id_kondisi_barang != 1
                  ORDER BY b.id_barang DESC
                  LIMIT :limit OFFSET :offset";

        $this->db->query($query);
        $this->db->bind('limit', $limit);
        $this->db->bind('offset', $offset);
        return $this->db->resultSet();
    }

    public function getTotalDamagedGoodsCount()
    {
        $query = "SELECT COUNT(*) as total FROM trx_barang WHERE id_kondisi_barang != 1";
        $this->db->query($query);
        $res = $this->db->single();
        return $res['total'] ?? 0;
    }

    public function logActivity($id_user, $action_type, $details)
    {
        $query = "INSERT INTO trx_log_activity (id_user, action_type, details) 
                  VALUES (:id_user, :action_type, :details)";
        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        $this->db->bind('action_type', $action_type);
        $this->db->bind('details', $details);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAssistantActivityLog($id_user, $limit = 5)
    {
        $query = "SELECT action_type, details, created_at 
                  FROM trx_log_activity 
                  WHERE id_user = :id_user 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $this->db->query($query);
        $this->db->bind('id_user', $id_user);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    public function getAllAssistantActivityLog($limit = 10)
    {
        $query = "SELECT log.action_type, log.details, log.created_at, u.nama_user 
                  FROM trx_log_activity log
                  JOIN trx_data_user u ON log.id_user = u.id_user
                  ORDER BY log.created_at DESC 
                  LIMIT :limit";
        $this->db->query($query);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}