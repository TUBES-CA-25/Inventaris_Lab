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
        // Set tanggal pengajuan otomatis hari ini jika kosong
        if (empty($data['tanggal_pengajuan'])) {
            $data['tanggal_pengajuan'] = date('Y-m-d'); // Gunakan format Y-m-d untuk Database MySQL
        }

        $query = "INSERT INTO trx_peminjaman
                  (nama_peminjam, judul_kegiatan, tanggal_pengajuan, tanggal_peminjaman, 
                   tanggal_pengembalian, id_jenis_barang, jumlah_peminjaman, keterangan_peminjaman, status) 
                  VALUES 
                  (:nama_peminjam, :judul_kegiatan, :tanggal_pengajuan, :tanggal_peminjaman, 
                   :tanggal_pengembalian, :id_jenis_barang, :jumlah_peminjaman, :keterangan_peminjaman, :status)";

        $this->db->query($query);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_pengajuan', $data['tanggal_pengajuan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();
        return $this->db->rowCount();
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
        WHERE 1=1"; // Trik '1=1' memudahkan penyambungan string query "AND"

        if (!empty($id_jenis_barang)) {
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        }

        if (!empty($status)) {
            $query .= " AND b.status = :status";
        }

        // Urutkan dari yang terbaru (opsional, tapi bagus untuk UX)
        $query .= " ORDER BY b.tanggal_pengajuan DESC";

        $this->db->query($query);

        // Binding parameters jika ada
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
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->single();
    }

    public function getDetailValidasiDataPeminjaman($id_peminjaman)
    {
        // Ubah query untuk JOIN ke tabel mst_jenis_barang agar dapat nama barangnya
        $query = "SELECT trx_peminjaman.*, mst_jenis_barang.sub_barang 
                  FROM trx_peminjaman 
                  JOIN mst_jenis_barang ON trx_peminjaman.id_jenis_barang = mst_jenis_barang.id_jenis_barang 
                  WHERE trx_peminjaman.id_peminjaman = :id_peminjaman";

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
        $queryPeminjaman = "UPDATE trx_peminjaman 
                            SET nama_peminjam = :nama_peminjam, 
                                judul_kegiatan = :judul_kegiatan, 
                                tanggal_peminjaman = :tanggal_peminjaman, 
                                tanggal_pengembalian = :tanggal_pengembalian, 
                                id_jenis_barang = :id_jenis_barang, 
                                jumlah_peminjaman = :jumlah_peminjaman, 
                                keterangan_peminjaman = :keterangan_peminjaman, 
                                status = :status 
                            WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($queryPeminjaman);
        $this->db->bind('nama_peminjam', $data['nama_peminjam']);
        $this->db->bind('judul_kegiatan', $data['judul_kegiatan']);
        $this->db->bind('tanggal_peminjaman', $data['tanggal_peminjaman']);
        $this->db->bind('tanggal_pengembalian', $data['tanggal_pengembalian']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->bind('jumlah_peminjaman', $data['jumlah_peminjaman']);
        $this->db->bind('keterangan_peminjaman', $data['keterangan_peminjaman']);
        $this->db->bind('status', $data['status']); // Bisa diubah hanya pada ubahDataPeminjaman
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);

        $this->db->execute();

        return $this->db->rowCount();
    }


    public function getDetailDataPeminjaman($id_peminjaman)
    {
        $this->db->query("SELECT * FROM trx_peminjaman WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind("id_peminjaman", $id_peminjaman);
        return $this->db->single();
    }


    public function getAllBarang()
    {
        // Sesuaikan nama tabel barang Anda (misal: mst_jenis_barang atau mst_barang)
        // Pastikan select kolom gambar juga
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }

    public function cariBarang($keyword)
    {
        $this->db->query("SELECT * FROM mst_jenis_barang WHERE sub_barang LIKE :keyword");
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function getBarangWhereIn($id_array)
    {
        if (empty($id_array)) return [];

        // Buat placeholder (?,?,?) dinamis
        $placeholders = implode(',', array_fill(0, count($id_array), '?'));

        $query = "SELECT * FROM mst_jenis_barang WHERE id_jenis_barang IN ($placeholders)";
        $this->db->query($query);

        // Bind value loop
        foreach ($id_array as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        return $this->db->resultSet();
    }



    public function updateStatusValidasi($id_peminjaman, $status, $catatan = null)
    {
        // 1. Query Dasar Update Status
        $query = "UPDATE trx_peminjaman SET status = :status";

        // 2. Logika Tambahan
        // Jika statusnya 'ditolak', kita update kolom keterangan
        if ($status == 'ditolak') {
            $query .= ", keterangan_peminjaman = :keterangan";
        }

        // Opsional: Jika status 'dikembalikan', kita bisa kosongkan keterangan (jika sebelumnya ada)
        // atau biarkan saja. Di sini kita biarkan saja query standarnya.

        $query .= " WHERE id_peminjaman = :id_peminjaman";

        $this->db->query($query);
        $this->db->bind('status', $status);
        $this->db->bind('id_peminjaman', $id_peminjaman);

        // 3. Bind Keterangan (Hanya jika Ditolak)
        if ($status == 'ditolak') {
            $pesan = "[DITOLAK] " . $catatan;
            $this->db->bind('keterangan', $pesan);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    // AMBIL DATA KHUSUS: DISETUJUI (Sedang Dipinjam) & DITOLAK (Bermasalah)
    public function getValidasiGabungan()
    {
        $query = "SELECT trx_peminjaman.*, mst_jenis_barang.sub_barang 
                  FROM trx_peminjaman 
                  JOIN mst_jenis_barang ON trx_peminjaman.id_jenis_barang = mst_jenis_barang.id_jenis_barang
                  
                  -- FILTER KUNCI: Hanya ambil yang Disetujui atau Ditolak
                  WHERE trx_peminjaman.status IN ('disetujui', 'ditolak') 
                  
                  -- Urutkan: 
                  -- 1. Yang 'disetujui' (Aktif) ditaruh paling atas agar terpantau
                  -- 2. Sisanya berdasarkan tanggal terbaru
                  ORDER BY 
                    CASE WHEN status = 'disetujui' THEN 1 ELSE 2 END ASC,
                    tanggal_pengajuan DESC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    // 2. Hitung Jumlah untuk Card Statistik
    public function hitungStatus($status)
    {
        // Query menghitung baris (COUNT) dimana status sesuai parameter
        $this->db->query("SELECT COUNT(*) as total FROM trx_peminjaman WHERE status = :status");
        $this->db->bind('status', $status);

        $result = $this->db->single();

        // Kembalikan angkanya (jika null, kembalikan 0)
        return isset($result['total']) ? $result['total'] : 0;
    }

    public function getPeminjamanTerbaruUser($nama_user)
    {
        $query = "SELECT tp.*, mjb.sub_barang 
                  FROM trx_peminjaman tp
                  JOIN mst_jenis_barang mjb ON tp.id_jenis_barang = mjb.id_jenis_barang
                  WHERE tp.nama_peminjam = :nama 
                  
                  -- BAGIAN INI YANG DIUBAH:
                  -- Ubah 'diproses' menjadi 'Melengkapi Surat'
                  -- Agar tombol 'Isi Otomatis' muncul saat user diarahkan ke halaman template
                  AND tp.status = 'Melengkapi Surat'
                  
                  ORDER BY tp.id_peminjaman DESC";

        $this->db->query($query);
        $this->db->bind('nama', $nama_user);
        return $this->db->resultSet();
    }


    // =====================================================================
    // TAMBAHAN BARU UNTUK FITUR SURAT (Wajib Ditambahkan)
    // =====================================================================

    /**
     * 1. Ambil Detail Peminjaman + Nama Barang
     * Digunakan agar di Surat muncul nama barangnya (bukan cuma ID).
     */
    public function getDetailPeminjaman($id_peminjaman) 
    {
        // Join ke mst_jenis_barang untuk ambil nama barang (sub_barang)
        $query = "SELECT tp.*, mjb.sub_barang as nama_barang, mjb.kode_sub
                  FROM trx_peminjaman tp
                  JOIN mst_jenis_barang mjb ON tp.id_jenis_barang = mjb.id_jenis_barang
                  WHERE tp.id_peminjaman = :id";
        
        $this->db->query($query);
        $this->db->bind('id', $id_peminjaman);
        return $this->db->single();
    }

    /**
     * 2. Ambil Data User Lengkap (HP, Alamat)
     * Karena trx_peminjaman tidak menyimpan No HP, kita ambil dari trx_data_user
     * berdasarkan ID User yang sedang login di Session.
     */
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

    /**
     * 3. Simpan File Surat & Update Status
     * Khusus untuk menyimpan nama file yang baru diupload user.
     */
    public function updateSuratPeminjaman($id, $namaFile) 
    {
        // Pastikan Anda sudah menjalankan perintah SQL: 
        // ALTER TABLE trx_peminjaman ADD file_surat VARCHAR(255) DEFAULT NULL;
        
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
}
