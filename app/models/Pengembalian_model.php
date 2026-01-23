<?php

class Pengembalian_model
{
    private $table = 'trx_peminjaman';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

<<<<<<< HEAD
    // FUNGSI INI YANG HILANG: Mengambil semua data peminjaman untuk diproses petugas
    public function getAllRiwayatForPetugas()
    {
        // Tambahkan WHERE status = 'Disetujui'
        $this->db->query("SELECT * FROM " . $this->table . " WHERE status = 'Disetujui' ORDER BY id_peminjaman DESC");
=======
    // Mengambil semua data peminjaman yang sudah disetujui untuk diproses petugas
    public function getAllRiwayatForPetugas()
    {
        // Ambil semua peminjaman yang statusnya Disetujui atau Dikembalikan
        // LEFT JOIN dengan pengembalian untuk tahu mana yang sudah di-ACC
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
            WHERE p.status IN ('Disetujui', 'Dikembalikan')
            ORDER BY 
                CASE WHEN pen.id_pengembalian IS NULL THEN 0 ELSE 1 END,
                p.id_peminjaman DESC");
>>>>>>> 1abea17a8ce7e7dddc08e8c4e6093bc993c5efc6
        return $this->db->resultSet();
    }

    public function getRiwayatById($id)
    {
<<<<<<< HEAD
        $this->db->query("SELECT p.*, 
                             k.tgl_kembali, 
                             k.keterangan, 
                             k.detail_masalah, 
                             k.id_user AS id_petugas,
                             du.nama_user AS nama_petugas
                      FROM trx_peminjaman p
                      LEFT JOIN trx_pengembalian k ON p.id_peminjaman = k.id_peminjaman 
                      LEFT JOIN trx_data_user du ON k.id_user = du.id_user -- JOIN untuk ambil nama petugas
                      WHERE p.id_peminjaman = :id");

=======
        // Query lengkap dengan LEFT JOIN untuk mendapatkan semua data
        $this->db->query("SELECT 
                p.*,
                pen.id_pengembalian,
                pen.tgl_pengembalian_aktual,
                pen.keterangan,
                pen.detail_masalah,
                pen.id_petugas,
                pen.status_pengembalian,
                pen.bukti_foto,
                petugas.nama_user as nama_petugas,
                u.nama_user as nama_peminjam,
                dp.id_detail,
                dp.jumlah,
                dp.keterangan_barang,
                jb.sub_barang as nama_barang,
                jb.id_jenis_barang
            FROM trx_peminjaman p
            LEFT JOIN trx_pengembalian pen ON p.id_peminjaman = pen.id_peminjaman
            LEFT JOIN trx_detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
            LEFT JOIN mst_jenis_barang jb ON dp.id_jenis_barang = jb.id_jenis_barang
            LEFT JOIN trx_data_user petugas ON pen.id_petugas = petugas.id_user
            LEFT JOIN trx_user tu ON p.id_user = tu.id_user
            LEFT JOIN trx_data_user u ON tu.id_user = u.id_user
            WHERE p.id_peminjaman = :id
            LIMIT 1");
>>>>>>> 1abea17a8ce7e7dddc08e8c4e6093bc993c5efc6
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function inputPengembalianAsisten($data)
    {
<<<<<<< HEAD
        // Perhatikan: Kita sekarang memasukkan 'id_user' ke database
        $query = "INSERT INTO trx_pengembalian 
              (id_peminjaman, tgl_kembali, status_pengembalian, keterangan, detail_masalah, id_user) 
              VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :id_user)";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
        $this->db->bind('tgl', date('Y-m-d'));
        $this->db->bind('status', 'Pending');
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('detail', $data['detail_masalah']);

        // --- BAGIAN PENTING: SINKRONISASI USER ---
        // Ambil ID dari session user yang sedang login (Asisten/Korlab)
        $this->db->bind('id_user', $_SESSION['id_user']);
        // -----------------------------------------

        $this->db->execute();

        // Update status peminjaman (tetap sama)
        $this->db->query("UPDATE trx_peminjaman SET status = 'dicek' WHERE id_peminjaman = :id");
=======
        // 1. Simpan pengecekan ke trx_pengembalian
        $query = "INSERT INTO trx_pengembalian 
                  (id_peminjaman, tgl_pengembalian_aktual, status_pengembalian, keterangan, detail_masalah, id_petugas) 
                  VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :id_petugas)";

        $this->db->query($query);
        $this->db->bind('id_peminjaman', $data['id_peminjaman']);
        $this->db->bind('tgl', date('Y-m-d')); // Tanggal hari ini
        $this->db->bind('status', $data['status_pengembalian'] ?? 'Dikembalikan');
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('detail', $data['detail_masalah'] ?? '-');
        $this->db->bind('id_petugas', $_SESSION['id_user']);
        $this->db->execute();

        // 2. Update status utama menjadi 'Dikembalikan'
        $this->db->query("UPDATE trx_peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = :id");
>>>>>>> 1abea17a8ce7e7dddc08e8c4e6093bc993c5efc6
        $this->db->bind('id', $data['id_peminjaman']);
        $this->db->execute();

        return $this->db->rowCount();
    }
<<<<<<< HEAD
}
=======

    // Method untuk update atau insert pengembalian (toggle status)
    public function updateOrInsertPengembalian($data)
    {
        try {
            // Cek apakah sudah ada record pengembalian
            $this->db->query("SELECT id_pengembalian FROM trx_pengembalian WHERE id_peminjaman = :id_peminjaman");
            $this->db->bind('id_peminjaman', $data['id_peminjaman']);
            $existing = $this->db->single();

            $affected = 0;

            if ($existing) {
                // UPDATE existing record
                $query = "UPDATE trx_pengembalian SET 
                          tgl_pengembalian_aktual = :tgl_pengembalian_aktual,
                          status_pengembalian = :status_pengembalian,
                          keterangan = :keterangan,
                          detail_masalah = :detail_masalah,
                          id_petugas = :id_petugas";

                // Tambah bukti_foto jika ada
                if (!empty($data['bukti_foto'])) {
                    $query .= ", bukti_foto = :bukti_foto";
                }

                $query .= " WHERE id_peminjaman = :id_peminjaman";

                $this->db->query($query);
                $this->db->bind('tgl_pengembalian_aktual', !empty($data['tgl_pengembalian_aktual']) ? $data['tgl_pengembalian_aktual'] : date('Y-m-d'));
                $this->db->bind('status_pengembalian', $data['status_pengembalian']);
                $this->db->bind('keterangan', !empty($data['keterangan']) ? $data['keterangan'] : '-');
                $this->db->bind('detail_masalah', !empty($data['detail_masalah']) ? $data['detail_masalah'] : '-');
                $this->db->bind('id_petugas', $data['id_petugas']);
                $this->db->bind('id_peminjaman', $data['id_peminjaman']);

                if (!empty($data['bukti_foto'])) {
                    $this->db->bind('bukti_foto', 'uploads/pengembalian/' . $data['bukti_foto']);
                }

                $this->db->execute();
                $affected = 1; // Asumsi berhasil karena tidak ada exception
            } else {
                // INSERT new record
                $query = "INSERT INTO trx_pengembalian 
                          (id_peminjaman, tgl_pengembalian_aktual, status_pengembalian, keterangan, detail_masalah, bukti_foto, id_petugas) 
                          VALUES (:id_peminjaman, :tgl, :status, :keterangan, :detail, :bukti_foto, :id_petugas)";

                $this->db->query($query);
                $this->db->bind('id_peminjaman', $data['id_peminjaman']);
                $this->db->bind('tgl', !empty($data['tgl_pengembalian_aktual']) ? $data['tgl_pengembalian_aktual'] : date('Y-m-d'));
                $this->db->bind('status', $data['status_pengembalian']);
                $this->db->bind('keterangan', !empty($data['keterangan']) ? $data['keterangan'] : '-');
                $this->db->bind('detail', !empty($data['detail_masalah']) ? $data['detail_masalah'] : '-');
                $this->db->bind('bukti_foto', !empty($data['bukti_foto']) ? 'uploads/pengembalian/' . $data['bukti_foto'] : null);
                $this->db->bind('id_petugas', $data['id_petugas']);
                $this->db->execute();
                $affected = 1;
            }

            // Update status peminjaman sesuai dengan status_pengembalian
            $status_peminjaman = ($data['status_pengembalian'] == 'Dikembalikan') ? 'Dikembalikan' : 'Disetujui';
            $this->db->query("UPDATE trx_peminjaman SET status = :status WHERE id_peminjaman = :id");
            $this->db->bind('status', $status_peminjaman);
            $this->db->bind('id', $data['id_peminjaman']);
            $this->db->execute();

            return $affected;
        } catch (Exception $e) {
            // Log error untuk debugging
            error_log("Error updateOrInsertPengembalian: " . $e->getMessage());
            return 0;
        }
    }

    // Method untuk mendapatkan semua jenis barang
    public function getAllJenisBarang()
    {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC");
        return $this->db->resultSet();
    }
}
>>>>>>> 1abea17a8ce7e7dddc08e8c4e6093bc993c5efc6
