<?php

class Beranda_model {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getAllCounts() {
        $query = "SELECT 
                    (SELECT COUNT(*) FROM mst_jenis_barang) as jml_jenis,
                    (SELECT COUNT(*) FROM trx_peminjaman) as jml_peminjaman,
                    (SELECT COUNT(*) FROM mst_merek_barang) as jml_merek,
                    (SELECT COUNT(*) FROM trx_barang) as jml_barang, 
                    (SELECT COUNT(*) FROM trx_pengembalian) as jml_pengembalian";
        $this->db->query($query);
        return $this->db->single();
    }

    public function getChartDataFiltered($mode, $tahun, $bulan = null) {
        $data = [
            'peminjaman' => [],
            'pengembalian' => [],
            'bagus' => [],
            'rusak' => []
        ];

        // Tentukan format grouping dan range loop berdasarkan mode
        if ($mode == 'harian') {
            // Loop tanggal 1 sampai akhir bulan
            $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $loopStart = 1;
            $loopEnd = $jmlHari;
            $groupBy = "DAY";
            $dateCol = "$tahun-$bulan-"; // Prefix tanggal
        } elseif ($mode == 'bulanan') {
            // Loop bulan 1 sampai 12
            $loopStart = 1;
            $loopEnd = 12;
            $groupBy = "MONTH";
        } else { // tahunan
            // Loop 5 tahun ke belakang dari tahun yang dipilih
            $loopStart = $tahun - 4;
            $loopEnd = $tahun;
            $groupBy = "YEAR";
        }

        // Siapkan array kosong (init 0) agar grafik tidak bolong
        $labels = [];
        for ($i = $loopStart; $i <= $loopEnd; $i++) {
            $labels[] = $i; // Label X-Axis (Tanggal, Bulan, atau Tahun)
            $data['peminjaman'][$i] = 0;
            $data['pengembalian'][$i] = 0;
            $data['bagus'][$i] = 0;
            $data['rusak'][$i] = 0;
        }

        // --- QUERY 1: Peminjaman ---
        $sql = "SELECT $groupBy(tanggal_peminjaman) as waktu, COUNT(*) as total 
                FROM trx_peminjaman WHERE 1=1 ";
        
        if ($mode == 'harian') {
            $sql .= "AND MONTH(tanggal_peminjaman) = $bulan AND YEAR(tanggal_peminjaman) = $tahun ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tanggal_peminjaman) = $tahun ";
        } else {
            $sql .= "AND YEAR(tanggal_peminjaman) BETWEEN $loopStart AND $loopEnd ";
        }
        $sql .= "GROUP BY $groupBy(tanggal_peminjaman)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $data['peminjaman'][$row['waktu']] = intval($row['total']);
        }

        // --- QUERY 2: Pengembalian ---
        // Asumsi: status 'Dikembalikan'
        $sql = "SELECT $groupBy(tanggal_pengembalian) as waktu, COUNT(*) as total 
                FROM trx_peminjaman 
                WHERE status IN ('Dikembalikan', 'Disetujui') ";

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tanggal_pengembalian) = $bulan AND YEAR(tanggal_pengembalian) = $tahun ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tanggal_pengembalian) = $tahun ";
        } else {
            $sql .= "AND YEAR(tanggal_pengembalian) BETWEEN $loopStart AND $loopEnd ";
        }
        $sql .= "GROUP BY $groupBy(tanggal_pengembalian)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $data['pengembalian'][$row['waktu']] = intval($row['total']);
        }

        // --- QUERY 3: Barang Bagus (Berdasarkan Tgl Pengadaan) ---
        $sql = "SELECT $groupBy(tgl_pengadaan_barang) as waktu, SUM(jumlah_barang) as total 
                FROM trx_barang 
                WHERE id_kondisi_barang = 1 "; // 1 = Baik

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengadaan_barang) = $bulan AND YEAR(tgl_pengadaan_barang) = $tahun ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengadaan_barang) = $tahun ";
        } else {
            $sql .= "AND YEAR(tgl_pengadaan_barang) BETWEEN $loopStart AND $loopEnd ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengadaan_barang)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $data['bagus'][$row['waktu']] = intval($row['total']);
        }

        // --- QUERY 4: Barang Rusak ---
        $sql = "SELECT $groupBy(tgl_pengadaan_barang) as waktu, SUM(jumlah_barang) as total 
                FROM trx_barang 
                WHERE id_kondisi_barang != 1 "; // Rusak

        if ($mode == 'harian') {
            $sql .= "AND MONTH(tgl_pengadaan_barang) = $bulan AND YEAR(tgl_pengadaan_barang) = $tahun ";
        } elseif ($mode == 'bulanan') {
            $sql .= "AND YEAR(tgl_pengadaan_barang) = $tahun ";
        } else {
            $sql .= "AND YEAR(tgl_pengadaan_barang) BETWEEN $loopStart AND $loopEnd ";
        }
        $sql .= "GROUP BY $groupBy(tgl_pengadaan_barang)";

        $this->db->query($sql);
        foreach ($this->db->resultSet() as $row) {
            $data['rusak'][$row['waktu']] = intval($row['total']);
        }

        return [
            'labels' => $labels,
            'peminjaman' => array_values($data['peminjaman']),
            'pengembalian' => array_values($data['pengembalian']),
            'bagus' => array_values($data['bagus']),
            'rusak' => array_values($data['rusak']),
        ];
    }
}