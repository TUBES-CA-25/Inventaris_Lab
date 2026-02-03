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
            if(isset($data['peminjaman'][$idx])) $data['peminjaman'][$idx] = intval($row['total']);
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
            if(isset($data['pengembalian'][$idx])) $data['pengembalian'][$idx] = intval($row['total']);
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
            if(isset($data['bagus'][$idx])) $data['bagus'][$idx] = intval($row['total']);
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
            if(isset($data['rusak'][$idx])) $data['rusak'][$idx] = intval($row['total']);
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