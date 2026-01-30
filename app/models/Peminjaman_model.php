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

        $this->db->query("DELETE FROM trx_detail_peminjaman WHERE id_peminjaman = :id");
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        $detail_inserted = 0;

        if (isset($data['id_jenis_barang']) && is_array($data['id_jenis_barang'])) {
            $queryDetail = "INSERT INTO trx_detail_peminjaman (id_peminjaman, id_jenis_barang, id_barang, jumlah) 
                            VALUES (:id_p, :id_b, :id_unit, :jml)";

            $jumlah_data = count($data['id_jenis_barang']);
            for ($i = 0; $i < $jumlah_data; $i++) {
                if (!empty($data['id_jenis_barang'][$i])) {
                    $this->db->query($queryDetail);
                    $this->db->bind('id_p', $data['id_peminjaman']);
                    $this->db->bind('id_b', $data['id_jenis_barang'][$i]);

                    $raw_unit = !empty($data['unit_selected'][$i]) ? $data['unit_selected'][$i] : null;

                    if (is_numeric($raw_unit) && $raw_unit > 0) {
                        $id_unit = $raw_unit;
                    } else {
                        $id_unit = null;
                    }
                    $this->db->bind('id_unit', $id_unit);

                    $jml = !empty($data['jumlah_peminjaman'][$i]) ? $data['jumlah_peminjaman'][$i] : 1;
                    $this->db->bind('jml', $jml);

                    $this->db->execute();
                    $detail_inserted++;
                }
            }
        }

        return $this->db->rowCount() + $detail_inserted;
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
                (SELECT tb.foto_barang 
                 FROM trx_barang tb 
                 WHERE tb.id_jenis_barang = mjb.id_jenis_barang 
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

        // Jika status adalah Tolak Peminjaman, simpan alasan ke kolom keterangan_tolak
        if ($status == 'tolak peminjaman') {
            $query .= ", keterangan_tolak = :keterangan";
        }

        $query .= " WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('status', $status);
        $this->db->bind('id_peminjaman', $id_peminjaman);

        if ($status == 'tolak peminjaman') {
            // Tetap menggunakan variabel $pesan sesuai struktur Anda
            $pesan = empty($catatan) ? '-' : $catatan;
            $this->db->bind('keterangan', $pesan);
        }

        $this->db->execute();
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
                d.id_jenis_barang, 
                d.jumlah, 
                d.id_barang,
                
                mjb.sub_barang as nama_barang, 
                COALESCE(tb.kode_barang, mjb.kode_sub) as kode_barang,
                -- Mengambil spesifikasi dari trx_barang (tb)
                tb.spesifikasi_barang as spesifikasi_barang, 
                tb.foto_barang,
                
                p_header.status_pengembalian, 
                tk.kondisi_barang as kondisi_kembali,
                tk.keterangan_kondisi as ket_kembali
                
              FROM trx_detail_peminjaman d 
              JOIN mst_jenis_barang mjb ON d.id_jenis_barang = mjb.id_jenis_barang 
              LEFT JOIN trx_barang tb ON d.id_barang = tb.id_barang
              
              -- Join ke data pengembalian (jika ada)
              LEFT JOIN trx_pengembalian p_header ON d.id_peminjaman = p_header.id_peminjaman
              LEFT JOIN trx_detail_pengembalian tk ON p_header.id_pengembalian = tk.id_pengembalian 
                   AND d.id_detail = tk.id_detail_peminjaman
              
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
        $query = "SELECT trx_barang.id_barang, kode_barang, kondisi_barang, spesifikasi_barang 
              FROM trx_barang 
              JOIN mst_kondisi_barang ON trx_barang.id_kondisi_barang = mst_kondisi_barang.id_kondisi_barang
              WHERE id_jenis_barang = :id 
              AND status_peminjaman = 'Bisa'";

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
}