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

        $ket_header = !empty($data['keterangan_peminjaman']) ? $data['keterangan_peminjaman'] : "-";

        // 1. Simpan Header Peminjaman
        $queryHeader = "INSERT INTO trx_peminjaman
              (id_user, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, 
               tanggal_pengembalian, keterangan_peminjaman, status, file_surat) 
              VALUES 
              (:id_user, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, 
               :tanggal_pengembalian, :ket, :status, :file_surat)";

        $this->db->query($queryHeader);
        $this->db->bind('id_user', $_SESSION['id_user']);
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

        // --- LOGIKA PENGGABUNGAN (MERGING) DISINI ---
        $merged_items = [];

        foreach ($data['id_jenis_barang'] as $i => $id_jenis) {
            // Ambil ID Unit (spesifikasi), jika kosong atau 'Lainnya' set ke NULL
            $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : 'NULL';
            $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? (int)$data['jumlah_peminjaman'][$i] : 1;

            // Buat kunci unik berdasarkan gabungan ID Jenis Barang dan ID Unit
            $key = $id_jenis . "_" . $raw_unit;

            if (isset($merged_items[$key])) {
                // Jika kunci sudah ada, tambahkan jumlahnya saja
                $merged_items[$key]['jumlah'] += $jumlah;
            } else {
                // Jika kunci belum ada, buat entri baru
                $merged_items[$key] = [
                    'id_jenis' => $id_jenis,
                    'id_unit'  => ($raw_unit === 'NULL' || $raw_unit === 'Lainnya') ? null : $raw_unit,
                    'jumlah'   => $jumlah
                ];
            }
        }

        // 2. Simpan Detail Peminjaman yang sudah digabung
        $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, id_barang, jumlah) 
                    VALUES (:id_p, :id_b, :id_unit, :jml)";

        $barang_tersimpan = 0;

        foreach ($merged_items as $item) {
            $this->db->query($queryDetail);
            $this->db->bind('id_p', $id_peminjaman_baru);
            $this->db->bind('id_b', $item['id_jenis']);
            $this->db->bind('id_unit', $item['id_unit']);
            $this->db->bind('jml', $item['jumlah']);

            $this->db->execute();
            $barang_tersimpan++;
        }

        return $barang_tersimpan;
    }

    // public function postDataPeminjaman($data)
    // {
    //     if (empty($data['tanggal_pengajuan'])) {
    //         $data['tanggal_pengajuan'] = date('Y-m-d');
    //     }

    //     $ket_header = !empty($data['keterangan_peminjaman']) ? $data['keterangan_peminjaman'] : "-";

    //     $queryHeader = "INSERT INTO trx_peminjaman
    //               (id_user, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, 
    //                tanggal_pengembalian, keterangan_peminjaman, status, file_surat) 
    //               VALUES 
    //               (:id_user, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, 
    //                :tanggal_pengembalian, :ket, :status, :file_surat)";

    //     $this->db->query($queryHeader);
    //     $this->db->bind('id_user', $_SESSION['id_user']);
    //     $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
    //     $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
    //     $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
    //     $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
    //     $this->db->bind('ket', $ket_header);
    //     $this->db->bind('status', 'Melengkapi Surat');
    //     $this->db->bind('file_surat', null);

    //     $this->db->execute();
    //     $id_peminjaman_baru = $this->db->lastInsertId();

    //     if (!isset($data['id_jenis_barang']) || !is_array($data['id_jenis_barang'])) {
    //         return 1;
    //     }

    //     $jumlah_data = count($data['id_jenis_barang']);

    //     $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, id_barang, jumlah) 
    //                     VALUES (:id_p, :id_b, :id_unit, :jml)";

    //     $barang_tersimpan = 0;

    //     for ($i = 0; $i < $jumlah_data; $i++) {
    //         if (!empty($data['id_jenis_barang'][$i])) {
    //             $this->db->query($queryDetail);
    //             $this->db->bind('id_p', $id_peminjaman_baru);
    //             $this->db->bind('id_b', $data['id_jenis_barang'][$i]);

    //             $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : null;

    //             if (is_numeric($raw_unit) && $raw_unit > 0) {
    //                 $id_unit = $raw_unit;
    //             } else {
    //                 $id_unit = null;
    //             }

    //             $this->db->bind('id_unit', $id_unit);

    //             $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? $data['jumlah_peminjaman'][$i] : 1;
    //             $this->db->bind('jml', $jumlah);

    //             $this->db->execute();
    //             $barang_tersimpan++;
    //         }
    //     }

    //     return $barang_tersimpan;
    // }

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
                GROUP BY tp.id_peminjaman, tdu.nama_user, tdu.nim_nip, peng.status_pengembalian"; // Perbaikan di sini

        $this->db->query($query);
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }

    // public function getDetailValidasiDataPeminjaman($id_peminjaman)
    // {
    //     $query = "SELECT tp.*, 
    //                   tdu.nama_user, 
    //                   tdu.nim_nip,
    //                   GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang,
    //                   SUM(tdp.jumlah) as jumlah_peminjaman,
    //                   tpt.alasan_penolakan,
    //                   peng.status_pengembalian  -- <--- Tambahan Kolom Ini

    //           FROM trx_peminjaman tp
    //           JOIN trx_data_user tdu ON tp.id_user = tdu.id_user  
    //           LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
    //           LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
    //           LEFT JOIN trx_pengembalian_tolak tpt ON tp.id_peminjaman = tpt.id_peminjaman

    //           -- JOIN BARU UNTUK CEK STATUS PENGEMBALIAN
    //           LEFT JOIN trx_pengembalian peng ON tp.id_peminjaman = peng.id_peminjaman

    //           WHERE tp.id_peminjaman = :id_peminjaman
    //           GROUP BY tp.id_peminjaman";

    //     $this->db->query($query);
    //     $this->db->bind("id_peminjaman", $id_peminjaman);
    //     return $this->db->single();
    // }

    public function getUbah($id_peminjaman)
    {
        $tampilView = "SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman;";
        $this->db->query($tampilView);
        $this->db->bind("id_peminjaman", $id_peminjaman);

        return $this->db->single();
    }

    public function ubahDataPeminjaman($data)
    {
        // 1. UPDATE HEADER PEMINJAMAN
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
        $rowCountHeader = $this->db->rowCount(); // Simpan rowCount header

        // 2. HAPUS SEMUA DETAIL LAMA
        // Kita hapus dulu semua item lama agar bersih, lalu insert ulang yang baru (termasuk yang diedit)
        $this->db->query("DELETE FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        $detail_inserted = 0;

        // 3. PROSES PENGGABUNGAN (MERGING) DATA BARU
        if (isset($data['id_jenis_barang']) && is_array($data['id_jenis_barang'])) {

            $merged_items = [];

            // Loop awal untuk menggabungkan item yang sama
            foreach ($data['id_jenis_barang'] as $i => $id_jenis) {
                if (empty($id_jenis)) continue; // Lewati jika kosong

                // Ambil ID Unit (spesifikasi), jika kosong atau 'Lainnya' set ke NULL
                $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : 'NULL';
                $jumlah = !empty($data['jumlah_peminjaman'][$i]) ? (int)$data['jumlah_peminjaman'][$i] : 1;

                // Buat kunci unik: ID_JENIS + ID_UNIT
                // Contoh: "5_12" (Jenis 5, Unit 12) atau "5_NULL" (Jenis 5, Tanpa Unit spesifik)
                $key = $id_jenis . "_" . $raw_unit;

                if (isset($merged_items[$key])) {
                    // Jika kunci sudah ada, tambahkan jumlahnya saja
                    $merged_items[$key]['jumlah'] += $jumlah;
                } else {
                    // Jika belum ada, buat entri baru
                    $merged_items[$key] = [
                        'id_jenis' => $id_jenis,
                        'id_unit'  => ($raw_unit === 'NULL' || $raw_unit === 'Lainnya') ? null : $raw_unit,
                        'jumlah'   => $jumlah
                    ];
                }
            }

            // 4. INSERT DATA YANG SUDAH DIGABUNG
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

        // --- UPDATE STATUS BARANG SAAT DIKEMBALIKAN ---
        if ($status == 'dikembalikan') {
            
            // 1. Ambil detail barang yang dipinjam
            $this->db->query("SELECT id_barang FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
            $this->db->bind('id', $id_peminjaman);
            $items = $this->db->resultSet();

            // 2. Loop update setiap barang
            foreach($items as $item) {
                if(!empty($item['id_barang'])) {
                    
                    // Kita perlu tahu kondisinya dari tabel pengembalian (opsional, tapi lebih akurat)
                    // Untuk simplifikasi: Kita set Default 'Stay' (3).
                    // Jika Rusak, biasanya laboran sudah update manual via menu Edit Pengembalian.
                    
                    $queryRestore = "UPDATE trx_barang 
                                     SET status_peminjaman = 'Bisa', 
                                         id_status = 3  -- ID 3 = Stay (Ada di Lab)
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
        // Menambahkan tdu.nama_user ke dalam GROUP BY agar lolos validasi ONLY_FULL_GROUP_BY
        $query = "SELECT tp.*, 
                        tdu.nama_user, 
                        GROUP_CONCAT(mjb.sub_barang SEPARATOR ', ') as sub_barang 
                FROM trx_peminjaman tp
                JOIN trx_data_user tdu ON tp.id_user = tdu.id_user  
                LEFT JOIN trx_detail_peminjaman tdp ON tp.id_peminjaman = tdp.id_peminjaman
                LEFT JOIN mst_jenis_barang mjb ON tdp.id_jenis_barang = mjb.id_jenis_barang
                
                WHERE 
                    tp.status IN ('diproses', 'disetujui', 'Tolak Pengembalian') 
                
                GROUP BY tp.id_peminjaman, tdu.nama_user -- Perbaikan di sini
                
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
        // Menggunakan ID User lebih akurat daripada Nama User
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
            d.jumlah, 
            mjb.sub_barang as nama_barang, 
            
            -- Ambil data spesifik jika unit dipilih
            ms.spesifikasi_barang,
            ms.foto_barang,
            ms.kode_barang as kode_induk,
            
            -- Detail Unit Fisik
            tb.urutan_unit,
            ms.kode_barang as qr_path, -- Di DB trx_barang kolom kode kadang null, pakai urutan_unit
            mkb.kondisi_barang as kondisi_saat_ini

          FROM trx_detail_peminjaman d 
          JOIN mst_jenis_barang mjb ON d.id_jenis_barang = mjb.id_jenis_barang 
          
          -- LEFT JOIN agar jika barang dihapus/kosong, data peminjaman tetap tampil
          LEFT JOIN trx_barang tb ON d.id_barang = tb.id_barang
          LEFT JOIN mst_spesifikasi ms ON tb.id_spesifikasi = ms.id_spesifikasi
          LEFT JOIN mst_kondisi_barang mkb ON tb.id_kondisi_barang = mkb.id_kondisi_barang
          
          WHERE d.id_peminjaman = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);

        return $this->db->resultSet();
    }

    public function simpanTolakPengembalian($id_peminjaman, $alasan)
    {
        try {
            $this->db->beginTransaction();

            // A. UPDATE STATUS UTAMA DI TRX_PEMINJAMAN
            // Status jadi 'Tolak Pengembalian'
            $queryMain = "UPDATE trx_peminjaman SET 
                          status = 'Tolak Pengembalian', 
                          keterangan_tolak = :ket 
                          WHERE id_peminjaman = :id";

            $pesan_lengkap = "[MASALAH PENGEMBALIAN] " . $alasan;

            $this->db->query($queryMain);
            $this->db->bind('ket', $pesan_lengkap);
            $this->db->bind('id', $id_peminjaman);
            $this->db->execute();

            // B. UPDATE HEADER PENGEMBALIAN (trx_pengembalian)
            // Pastikan status di sini jadi 'Periksa Ulang' agar form edit terbuka lagi
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
                // Buat baru jika belum ada
                $this->db->query("INSERT INTO trx_pengembalian (id_peminjaman, status_pengembalian) VALUES (:id, 'Periksa Ulang')");
                $this->db->bind('id', $id_peminjaman);
                $this->db->execute();
                $id_pengembalian = $this->db->lastInsertId();
            }

            // C. CATAT LOG RIWAYAT (PENTING)
            // Agar tercatat siapa asisten yang melaporkan masalah ini
            // $this->db->query("INSERT INTO trx_pemeriksa_pengembalian (id_pengembalian, id_user) VALUES (:idp, :idu)");
            // $this->db->bind('idp', $id_pengembalian);
            // $this->db->bind('idu', $_SESSION['id_user']);
            // $this->db->execute();

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
            $ttdWidth = 35; // Lebar tanda tangan dalam mm

            // --- PERBAIKAN LOGIKA DI SINI ---

            // 1. Cek Apakah Halaman Ini Adalah Halaman TTD Fatimah?
            if ($i == $data['fatimah_page']) {
                $fx = $widthMM * $data['fatimah_x'];
                $fy = $heightMM * $data['fatimah_y'];

                if (file_exists($pathFatimah)) {
                    $pdf->Image($pathFatimah, $fx, $fy, $ttdWidth);
                }
            }

            // 2. Cek Apakah Halaman Ini Adalah Halaman TTD Huzain?
            // (Dipisah if-nya supaya bisa support jika mereka di halaman yang sama maupun beda)
            if ($i == $data['huzain_page']) {
                $hx = $widthMM * $data['huzain_x'];
                $hy = $heightMM * $data['huzain_y'];

                if (file_exists($pathHuzain)) {
                    $pdf->Image($pathHuzain, $hx, $hy, $ttdWidth);
                }
            }
        }

        $pdf->Output($pathAsli, 'F');

        // $query = "UPDATE trx_peminjaman SET validasi_kalab='1', validasi_laboran='1', status='disetujui' WHERE id_peminjaman=:id";
        // $this->db->query($query);
        // $this->db->bind('id', $id);
        // $this->db->execute();

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

    // Tambahkan di Peminjaman_model.php

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
        try {
            // Ambil semua request barang
            $this->db->query("SELECT * FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
            $this->db->bind('id', $id_peminjaman);
            $requests = $this->db->resultSet();

            foreach ($requests as $req) {
                // ... (Logika lewati jika id_barang sudah ada - sama seperti sebelumnya) ...
                if (!empty($req['id_barang']) && $req['jumlah'] == 1) {
                    // UPDATE STATUS JADI DIPINJAM (ID 1)
                    $this->db->query("UPDATE trx_barang SET status_peminjaman = 'Tidak Bisa', id_status = 1 WHERE id_barang = :idb");
                    $this->db->bind('idb', $req['id_barang']);
                    $this->db->execute();
                    continue;
                }

                $qty_butuh = $req['jumlah'];
                $id_jenis = $req['id_jenis_barang'];

                // Cari Unit Tersedia
                // Filter: id_status BUKAN 1 (Dipinjam) dan BUKAN 4 (Rusak)
                $queryCari = "SELECT tb.id_barang 
                              FROM trx_barang tb
                              JOIN mst_spesifikasi ms ON tb.id_spesifikasi = ms.id_spesifikasi
                              JOIN mst_kondisi_barang mkb ON tb.id_kondisi_barang = mkb.id_kondisi_barang
                              WHERE ms.id_jenis_barang = :jenis
                              AND tb.status_peminjaman = 'Bisa'
                              AND mkb.kondisi_barang = 'Baik'
                              ORDER BY tb.urutan_unit ASC
                              LIMIT :limit";

                $this->db->query($queryCari);
                $this->db->bind('jenis', $id_jenis);
                $this->db->bind('limit', $qty_butuh, PDO::PARAM_INT);
                $candidates = $this->db->resultSet();

                if (count($candidates) < $qty_butuh) {
                    throw new Exception("Stok fisik tidak cukup.");
                }

                // Hapus baris lama
                $this->db->query("DELETE FROM trx_detail_peminjaman WHERE id_detail = :id_detail");
                $this->db->bind('id_detail', $req['id_detail']);
                $this->db->execute();

                foreach ($candidates as $unit) {
                    // Insert baris baru
                    $queryInsert = "INSERT INTO trx_detail_peminjaman 
                                    (id_peminjaman, id_jenis_barang, id_barang, jumlah) 
                                    VALUES (:idp, :idj, :idb, 1)";
                    $this->db->query($queryInsert);
                    $this->db->bind('idp', $id_peminjaman);
                    $this->db->bind('idj', $id_jenis);
                    $this->db->bind('idb', $unit['id_barang']);
                    $this->db->execute();

                    // --- PERBAIKAN DISINI ---
                    // Ubah status_peminjaman = 'Tidak Bisa'
                    // Ubah id_status = 1 ('Dipinjam')
                    $queryUpdateBarang = "UPDATE trx_barang 
                                          SET status_peminjaman = 'Tidak Bisa', 
                                              id_status = 1 
                                          WHERE id_barang = :idb";
                    $this->db->query($queryUpdateBarang);
                    $this->db->bind('idb', $unit['id_barang']);
                    $this->db->execute();
                }
            }

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
}
