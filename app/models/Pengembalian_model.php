<?php

class Pengembalian_model
{
    private $table = 'trx_peminjaman';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllRiwayatForPetugas()
    {
        $this->db->query("SELECT 
                p.*,
                u.nama_user as nama_peminjam,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                pen.status_pengembalian,
                pen.keterangan as keterangan_pengembalian,
                CASE 
                    WHEN pen.id_pengembalian IS NOT NULL THEN 'Sudah Di-ACC'
                    WHEN p.status = 'Dikembalikan' THEN 'Sudah Di-ACC'
                    ELSE 'Belum Di-ACC'
                END as status_acc
            FROM " . $this->table . " p
            LEFT JOIN trx_user tu ON p.id_user = tu.id_user
            LEFT JOIN trx_data_user u ON tu.id_user = u.id_user
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            WHERE p.status IN ('Disetujui', 'tolak pengembalian')
            ORDER BY 
                CASE WHEN pen.id_pengembalian IS NULL THEN 0 ELSE 1 END,
                p.id_peminjaman DESC");
        return $this->db->resultSet();
    }

    public function getRiwayatById($id_peminjaman)
    {
        $this->db->query("SELECT 
                p.*,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                pen.keterangan as keterangan_header,
                pen.detail_masalah,
                pen.status_pengembalian,
                pen.bukti_foto,
                
                -- HAPUS pen.id_petugas DARI SINI KARENA SUDAH DIHAPUS DI DB --
                
                u.nama_user as nama_peminjam,
                
                -- KITA MENGANDALKAN INI UNTUK DAFTAR PEMERIKSA --
                GROUP_CONCAT(
                    CONCAT(DATE_FORMAT(tpp.waktu_periksa, '%d %M %Y %H:%i'), ' - ', petugas.nama_user)
                    ORDER BY tpp.waktu_periksa DESC
                    SEPARATOR '||'
                ) as log_history

            FROM trx_peminjaman p
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            LEFT JOIN trx_user tu ON p.id_user = tu.id_user
            LEFT JOIN trx_data_user u ON tu.id_user = u.id_user
            
            -- Join ke tabel history
            LEFT JOIN trx_pemeriksa_pengembalian tpp ON pen.id_pengembalian = tpp.id_pengembalian
            LEFT JOIN trx_data_user petugas ON tpp.id_user = petugas.id_user
            
            WHERE p.id_peminjaman = :id
            GROUP BY p.id_peminjaman
            LIMIT 1");
            
        $this->db->bind('id', $id_peminjaman);
        return $this->db->single();
    }

    // --- LOGIC UTAMA: MENYIMPAN HEADER + DETAIL BARANG ---
    public function updateOrInsertPengembalian($data)
    {
        try {
            $this->db->beginTransaction();

            // 1. CEK DATA LAMA
            $this->db->query("SELECT id_pengembalian FROM trx_pengembalian WHERE id_peminjaman = :id");
            $this->db->bind('id', $data['id_peminjaman']);
            $existing = $this->db->single();
            
            $id_pengembalian = null;

            // PERBAIKAN: Hapus 'id_petugas' dari Query Header
            // Kita hanya update data inti pengembalian

            if ($existing) {
                // UPDATE HEADER (Tanpa id_petugas)
                $id_pengembalian = $existing['id_pengembalian'];
                
                $query = "UPDATE trx_pengembalian SET 
                          tgl_pengembalian_aktual = :tgl, 
                          status_pengembalian = :st, 
                          keterangan = :ket,
                          detail_masalah = :detail,
                          bukti_foto = COALESCE(:foto, bukti_foto) 
                          WHERE id_pengembalian = :id";
                
                $this->db->query($query);
                $this->db->bind('tgl', $data['tgl_pengembalian_aktual']);
                $this->db->bind('st', $data['status_pengembalian']);
                $this->db->bind('ket', $data['keterangan'] ?? '-');
                $this->db->bind('detail', $data['detail_masalah'] ?? '-');
                $this->db->bind('foto', !empty($data['bukti_foto']) ? 'uploads/pengembalian/' . $data['bukti_foto'] : null);
                $this->db->bind('id', $id_pengembalian);
                $this->db->execute();

            } else {
                // INSERT HEADER BARU (Tanpa id_petugas)
                $query = "INSERT INTO trx_pengembalian 
                          (id_peminjaman, tgl_pengembalian_aktual, status_pengembalian, keterangan, detail_masalah, bukti_foto) 
                          VALUES (:idp, :tgl, :st, :ket, :detail, :foto)";
                
                $this->db->query($query);
                $this->db->bind('idp', $data['id_peminjaman']);
                $this->db->bind('tgl', $data['tgl_pengembalian_aktual']);
                $this->db->bind('st', $data['status_pengembalian']);
                $this->db->bind('ket', $data['keterangan'] ?? '-');
                $this->db->bind('detail', $data['detail_masalah'] ?? '-');
                $this->db->bind('foto', !empty($data['bukti_foto']) ? 'uploads/pengembalian/' . $data['bukti_foto'] : null);
                $this->db->execute();
                $id_pengembalian = $this->db->lastInsertId();
            }

            // 2. CATAT LOG RIWAYAT (INI YANG UTAMA)
            // Setiap kali simpan, kita catat siapa pelakunya di sini
            
            // Pastikan ambil variabel yang benar dari Controller/View
            $id_petugas_aksi = isset($data['id_petugas']) ? $data['id_petugas'] : $_SESSION['id_user'];

            $queryLog = "INSERT INTO trx_pemeriksa_pengembalian (id_pengembalian, id_user) 
                         VALUES (:id_peng, :id_user)";
            $this->db->query($queryLog);
            $this->db->bind('id_peng', $id_pengembalian);
            $this->db->bind('id_user', $id_petugas_aksi);
            $this->db->execute();

            // 3. UPDATE KONDISI BARANG (Tetap Sama)
            if (isset($data['kondisi']) && is_array($data['kondisi'])) {
                foreach ($data['kondisi'] as $id_detail_pinjam => $kondisi_item) {
                    $ket_item = $data['ket_item'][$id_detail_pinjam] ?? '-';
                    
                    $this->db->query("SELECT id_detail_pengembalian FROM trx_detail_pengembalian WHERE id_pengembalian = :id_peng AND id_detail_peminjaman = :id_dp");
                    $this->db->bind('id_peng', $id_pengembalian);
                    $this->db->bind('id_dp', $id_detail_pinjam);
                    $cekItem = $this->db->single();

                    if ($cekItem) {
                        $this->db->query("UPDATE trx_detail_pengembalian SET kondisi_barang=:k, keterangan_kondisi=:kk WHERE id_detail_pengembalian=:id");
                        $this->db->bind('id', $cekItem['id_detail_pengembalian']);
                    } else {
                        $this->db->query("INSERT INTO trx_detail_pengembalian (id_pengembalian, id_detail_peminjaman, jumlah_kembali, kondisi_barang, keterangan_kondisi) VALUES (:idp, :idd, 1, :k, :kk)");
                        $this->db->bind('idp', $id_pengembalian);
                        $this->db->bind('idd', $id_detail_pinjam);
                    }
                    $this->db->bind('k', $kondisi_item);
                    $this->db->bind('kk', $ket_item);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return 1;
        } catch (Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }

    public function getAllJenisBarang()
    {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }

    // Ambil barang yang SUDAH dikembalikan (Join ke trx_detail_pengembalian)
    // [UPDATE PENTING] Mengambil Semua Barang Pinjaman + Status Kembali (Jika ada)
    public function getBarangPengembalian($id_peminjaman, $id_pengembalian = null)
    {
        // Kuncinya: Mulai dari trx_detail_peminjaman (dp)
        // Lalu LEFT JOIN ke trx_detail_pengembalian (tk)
        // Ini menjamin SEMUA barang yang dipinjam akan tampil di list

        $query = "SELECT 
                    dp.id_detail as id_detail_peminjaman,
                    dp.jumlah as jumlah_pinjam,
                    
                    -- Data Barang
                    b.kode_barang,
                    jb.sub_barang as nama_barang,
                    
                    -- Data Pengembalian (Bisa NULL jika belum diinput)
                    tk.id_detail_pengembalian,
                    tk.jumlah_kembali,
                    tk.kondisi_barang,
                    tk.keterangan_kondisi

                  FROM trx_detail_peminjaman dp
                  JOIN trx_barang b ON dp.id_barang = b.id_barang
                  JOIN mst_jenis_barang jb ON b.id_jenis_barang = jb.id_jenis_barang
                  
                  -- Hubungkan ke tabel pengembalian (jika ada)
                  LEFT JOIN trx_detail_pengembalian tk ON dp.id_detail = tk.id_detail_peminjaman 
                                                       AND tk.id_pengembalian = :id_pengembalian
                  
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        $this->db->bind('id_pengembalian', $id_pengembalian); // Bisa null
        return $this->db->resultSet();
    }

    // Preview barang yang MASIH dipinjam (Join ke trx_detail_peminjaman saja)
    public function getBarangPinjamPreview($id_peminjaman)
    {
        $query = "SELECT 
                    dp.id_detail,
                    dp.jumlah as jumlah_kembali, 
                    'Dipinjam' as kondisi_barang,
                    '-' as keterangan_kondisi,
                    b.kode_barang,
                    jb.sub_barang as nama_barang
                  FROM trx_detail_peminjaman dp
                  LEFT JOIN trx_barang b ON dp.id_barang = b.id_barang
                  LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->resultSet();
    }

    // Ambil Items untuk FORM EDIT (Join gabungan)
    public function getItemsForForm($id_peminjaman)
    {
        $query = "SELECT 
                    dp.id_detail,
                    dp.id_barang,
                    dp.jumlah,
                    jb.sub_barang as nama_barang,
                    b.kode_barang,
                    b.spesifikasi_barang,  -- TAMBAHKAN INI
                    tk.kondisi_barang as kondisi_existing,
                    tk.keterangan_kondisi as ket_existing,
                    tk.jumlah_kembali as jml_existing
                  FROM trx_detail_peminjaman dp
                  LEFT JOIN trx_barang b ON dp.id_barang = b.id_barang
                  LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
                  LEFT JOIN trx_pengembalian header ON header.id_peminjaman = dp.id_peminjaman
                  LEFT JOIN trx_detail_pengembalian tk ON tk.id_detail_peminjaman = dp.id_detail AND tk.id_pengembalian = header.id_pengembalian
                  WHERE dp.id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->resultSet();
    }
}
