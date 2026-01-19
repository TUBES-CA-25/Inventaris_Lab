<?php

class Detail_barang_model
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    // --- GET DATA MASTER ---
    public function getSubBarang()
    {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang");
        return $this->db->resultSet();
    }
    public function getMerekBarang()
    {
        $this->db->query("SELECT * FROM mst_merek_barang ORDER BY nama_merek_barang");
        return $this->db->resultSet();
    }
    public function getKondisiBarang()
    {
        $this->db->query("SELECT * FROM mst_kondisi_barang ORDER BY kondisi_barang");
        return $this->db->resultSet();
    }
    public function getSatuan()
    {
        $this->db->query("SELECT * FROM mst_satuan ORDER BY nama_satuan");
        return $this->db->resultSet();
    }
    public function getStatus()
    {
        $this->db->query("SELECT * FROM mst_status ORDER BY status");
        return $this->db->resultSet();
    }
    public function getLokasiPenyimpanan()
    {
        $this->db->query("SELECT * FROM mst_lokasi_penyimpanan ORDER BY nama_lokasi_penyimpanan");
        return $this->db->resultSet();
    }

    // --- GET DATA BARANG FILTER ---
    public function getDataBarangByFilters($id_merek_barang, $id_jenis_barang, $id_lokasi)
    {
        $query = "SELECT 
            b.id_barang, b.foto_barang, b.spesifikasi_barang, b.tgl_pengadaan_barang,
            b.keterangan_label, b.deskripsi_detail_lokasi, b.status_peminjaman,
            b.kode_barang, b.qr_code, b.jumlah_barang,
            j.sub_barang, m.nama_merek_barang, k.kondisi_barang, 
            s.status, l.nama_lokasi_penyimpanan, n.nama_satuan
        FROM trx_barang b
        JOIN mst_jenis_barang j ON b.id_jenis_barang = j.id_jenis_barang
        JOIN mst_merek_barang m ON b.id_merek_barang = m.id_merek_barang
        JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
        JOIN mst_status s ON b.id_status = s.id_status
        JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
        JOIN mst_satuan n ON b.id_satuan = n.id_satuan
        WHERE 1=1";

        if (!empty($id_merek_barang))
            $query .= " AND b.id_merek_barang = :id_merek_barang";
        if (!empty($id_jenis_barang))
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        if (!empty($id_lokasi))
            $query .= " AND b.id_lokasi_penyimpanan = :id_lokasi";

        $this->db->query($query);
        if (!empty($id_merek_barang))
            $this->db->bind(':id_merek_barang', $id_merek_barang);
        if (!empty($id_jenis_barang))
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        if (!empty($id_lokasi))
            $this->db->bind(':id_lokasi', $id_lokasi);
        return $this->db->resultSet();
    }

    // --- HELPER: INPUT DATA BARU KE MASTER (UNIVERSAL) ---
    // --- HELPER: INPUT DATA BARU KE MASTER (UNIVERSAL) ---
    private function insertNewMasterData($table, $column, $value, $extraInput = null)
    {
        // 1. Cek Data Ganda
        $check = "SELECT * FROM $table WHERE $column = :value";
        $this->db->query($check);
        $this->db->bind('value', $value);
        $existing = $this->db->single();

        if ($existing) {
            $pk = "id_" . str_replace("mst_", "", $table);
            return $existing[$pk];
        } else {
            $query = "";

            // Logic Insert Berdasarkan Tabel
            if ($table == 'mst_jenis_barang') {
                // Bersihkan input (Hanya huruf a-z, jadikan kapital)
                $cleanVal = strtoupper(preg_replace("/[^A-Za-z]/", '', $value));

                $grup = !empty($extraInput) ? strtoupper($extraInput) : 'C';

                // [PERUBAHAN DI SINI]
                // Mengambil 3 huruf pertama dari nama barang
                $shortCode = substr($cleanVal, 0, 3);

                // Penanganan jika nama barang kurang dari 3 huruf (Misal: "PC" -> "PCX")
                if (strlen($shortCode) < 3) {
                    $shortCode = str_pad($shortCode, 3, 'X');
                }

                $query = "INSERT INTO mst_jenis_barang (sub_barang, grup_sub, kode_sub, kode_jenis_barang) 
                          VALUES (:val, :grup, :kodesub, :kodejenis)";
                $this->db->query($query);
                $this->db->bind('val', $value);
                $this->db->bind('grup', $grup);
                $this->db->bind('kodesub', $shortCode);
                $this->db->bind('kodejenis', $grup . '/' . $shortCode); // Hasil: C/MOU

            } else if ($table == 'mst_merek_barang') {
                // ... (Kode Merek Tetap Sama) ...
                $kodeMerek = !empty($extraInput) ? $extraInput : rand(100, 999);
                $query = "INSERT INTO mst_merek_barang (nama_merek_barang, kode_merek_barang) 
                          VALUES (:val, :kode)";
                $this->db->query($query);
                $this->db->bind('val', $value);
                $this->db->bind('kode', $kodeMerek);

            } else if ($table == 'mst_lokasi_penyimpanan') {
                $query = "INSERT INTO mst_lokasi_penyimpanan (nama_lokasi_penyimpanan) VALUES (:val)";
                $this->db->query($query);
                $this->db->bind('val', $value);

            } else if ($table == 'mst_status') {
                $query = "INSERT INTO mst_status (status) VALUES (:val)";
                $this->db->query($query);
                $this->db->bind('val', $value);

            } else if ($table == 'mst_satuan') {
                $query = "INSERT INTO mst_satuan (nama_satuan) VALUES (:val)";
                $this->db->query($query);
                $this->db->bind('val', $value);
            }

            $this->db->execute();
            return $this->db->lastInsertId();
        }
    }

    // --- TAMBAH BARANG ---
    public function postDataBarang($data)
    {
        // 1. INPUT JENIS BARU
        if ($data['sub_barang'] == 'NEW' && !empty($data['sub_barang_baru'])) {
            $manualGroup = !empty($data['grup_sub_baru']) ? $data['grup_sub_baru'] : null;
            $data['sub_barang'] = $this->insertNewMasterData('mst_jenis_barang', 'sub_barang', $data['sub_barang_baru'], $manualGroup);
        }

        // 2. INPUT MEREK BARU
        if ($data['nama_merek_barang'] == 'NEW' && !empty($data['nama_merek_baru'])) {
            $manualCode = !empty($data['kode_merek_baru']) ? $data['kode_merek_baru'] : null;
            $data['nama_merek_barang'] = $this->insertNewMasterData('mst_merek_barang', 'nama_merek_barang', $data['nama_merek_baru'], $manualCode);
        }

        // 3. INPUT LOKASI BARU
        if ($data['lokasi_penyimpanan'] == 'NEW' && !empty($data['lokasi_baru'])) {
            $data['lokasi_penyimpanan'] = $this->insertNewMasterData('mst_lokasi_penyimpanan', 'nama_lokasi_penyimpanan', $data['lokasi_baru']);
        }

        // 4. INPUT STATUS BARU
        if ($data['status'] == 'NEW' && !empty($data['status_baru'])) {
            $data['status'] = $this->insertNewMasterData('mst_status', 'status', $data['status_baru']);
        }

        // 5. INPUT SATUAN BARU
        if ($data['satuan'] == 'NEW' && !empty($data['satuan_baru'])) {
            $data['satuan'] = $this->insertNewMasterData('mst_satuan', 'nama_satuan', $data['satuan_baru']);
        }

        // 6. UPLOAD FOTO & SIMPAN BARANG
        $ukuranFile = $_FILES['foto_barang']['size'];
        $limit = 2 * 1024 * 1024;

        if ($ukuranFile <= $limit) {

            // Kode untuk Nomor Inventaris
            $this->db->query("SELECT kode_jenis_barang FROM mst_jenis_barang WHERE id_jenis_barang = :id");
            $this->db->bind('id', $data['sub_barang']);
            $kodeJenis = $this->db->single();
            $kodeJenisBarangString = $kodeJenis['kode_jenis_barang'] ?? 'XXX';

            $this->db->query("SELECT kode_merek_barang FROM mst_merek_barang WHERE id_merek_barang = :id");
            $this->db->bind('id', $data['nama_merek_barang']);
            $kodeMerek = $this->db->single();
            $kodeMerekBarangString = $kodeMerek['kode_merek_barang'] ?? '000';

            // Proses File
            $uploadDirectory = '../public/img/foto-barang/';
            $uploadedFile = $_FILES['foto_barang']['tmp_name'];
            $namaFileUnik = uniqid() . '_' . $_FILES['foto_barang']['name'];
            $fotoBarang = $uploadDirectory . $namaFileUnik;
            move_uploaded_file($uploadedFile, $fotoBarang);

            // 7. INSERT DATA
            $queryBarang = "INSERT INTO trx_barang (
                foto_barang, id_jenis_barang, id_merek_barang, id_kondisi_barang, 
                jumlah_barang, id_satuan, spesifikasi_barang, tgl_pengadaan_barang, 
                keterangan_label, id_lokasi_penyimpanan, deskripsi_detail_lokasi, 
                id_status, status_peminjaman, kode_barang
            ) VALUES (
                :foto_barang, :id_jenis_barang, :id_merek_barang, :id_kondisi_barang, 
                :jumlah_barang, :id_satuan, :spesifikasi_barang, :tgl_pengadaan_barang, 
                :keterangan_label, :id_lokasi_penyimpanan, :deskripsi_detail_lokasi, 
                :id_status, :status_peminjaman, :kode_barang
            )";

            // Set default 'Belum' jika input keterangan_label dihapus dari form
            $labelDefault = !empty($data['keterangan_label']) ? $data['keterangan_label'] : 'Belum';

            $this->db->query($queryBarang);
            $this->db->bind('foto_barang', $fotoBarang);
            $this->db->bind('id_jenis_barang', $data['sub_barang']);
            $this->db->bind('id_merek_barang', $data['nama_merek_barang']);
            $this->db->bind('id_kondisi_barang', $data['kondisi_barang']);
            $this->db->bind('jumlah_barang', $data['jumlah_barang']);
            $this->db->bind('id_satuan', $data['satuan']);
            $this->db->bind('spesifikasi_barang', $data['spesifikasi_barang']);
            $this->db->bind('tgl_pengadaan_barang', $data['tgl_pengadaan_barang']);
            $this->db->bind('keterangan_label', $labelDefault);
            $this->db->bind('id_lokasi_penyimpanan', $data['lokasi_penyimpanan']);
            $this->db->bind('deskripsi_detail_lokasi', $data['deskripsi_detail_lokasi']);
            $this->db->bind('id_status', $data['status']);
            $this->db->bind('status_peminjaman', $data['status_pinjam']);

            // 8. GENERATE KODE & QR
            function angkaRomawi($number)
            {
                $romans = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
                $res = '';
                foreach ($romans as $r => $v) {
                    $matches = intval($number / $v);
                    $res .= str_repeat($r, $matches);
                    $number %= $v;
                }
                return $res;
            }
            $month = date('m', strtotime($data['tgl_pengadaan_barang']));
            $romanMonth = angkaRomawi($month);

            $kodeBarang = date('Y', strtotime($data['tgl_pengadaan_barang'])) . '/' . $romanMonth . '/' . $kodeJenisBarangString . '/' . $kodeMerekBarangString . '/' . $data['barang_ke'] . '/' . $data['total_barang'];

            $this->db->bind('kode_barang', $kodeBarang);
            $this->db->execute();
            $idbarang = $this->db->lastInsertId();

            // 9. QR CODE
            $this->db->query("SELECT * FROM detail_barang WHERE id_barang = :id_barang");
            $this->db->bind("id_barang", $idbarang);
            $det = $this->db->single();

            $qrContent = "Kode: " . $kodeBarang . "\n" .
                "Jenis: " . $det['sub_barang'] . "\n" .
                "Merek: " . $det['nama_merek_barang'] . "\n" .
                "Spesifikasi: " . $data['spesifikasi_barang'] . "\n" .
                "Lokasi: " . $det['nama_lokasi_penyimpanan'];

            $pathQr = '../public/img/qr-code/';
            if (!file_exists($pathQr))
                mkdir($pathQr, 0777, true);

            $uniqueFileName = uniqid("code_") . ".png";
            QRcode::png($qrContent, $pathQr . $uniqueFileName, "M", 4, 4);

            $this->db->query("UPDATE trx_barang SET qr_code = :qr WHERE id_barang = :id");
            $this->db->bind('qr', $pathQr . $uniqueFileName);
            $this->db->bind('id', $idbarang);
            $this->db->execute();

            return $this->db->rowCount();
        } else {
            return 0;
        }
    }

    public function getDataBarang()
    {
        $this->db->query("SELECT * FROM detail_barang");
        return $this->db->resultSet();
    }
    public function getDetailDataBarang($id_barang)
    {
        $query = "SELECT b.*, b.spesifikasi_barang, j.sub_barang, m.nama_merek_barang, 
                  s.nama_satuan, l.nama_lokasi_penyimpanan, k.kondisi_barang, st.status
                  FROM trx_barang b
                  JOIN mst_jenis_barang j ON b.id_jenis_barang = j.id_jenis_barang
                  JOIN mst_merek_barang m ON b.id_merek_barang = m.id_merek_barang
                  JOIN mst_satuan s ON b.id_satuan = s.id_satuan
                  JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
                  JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
                  JOIN mst_status st ON b.id_status = st.id_status
                  WHERE b.id_barang = :id_barang";
        $this->db->query($query);
        $this->db->bind("id_barang", $id_barang);
        return $this->db->single();
    }
    public function hapusBarang($id_barang)
    {
        try {
            $this->db->query("SELECT foto_barang, qr_code FROM trx_barang WHERE id_barang = :id");
            $this->db->bind("id", $id_barang);
            $res = $this->db->single();

            if ($res) {
                // Hapus foto barang jika ada
                if (!empty($res['foto_barang']) && file_exists($res['foto_barang'])) {
                    @unlink($res['foto_barang']); // @ untuk suppress error jika gagal
                }

                // Hapus QR code jika ada
                if (!empty($res['qr_code']) && file_exists($res['qr_code'])) {
                    @unlink($res['qr_code']); // @ untuk suppress error jika gagal
                }
            }

            $this->db->query("DELETE FROM trx_barang WHERE id_barang = :id");
            $this->db->bind("id", $id_barang);
            $this->db->execute();

            return $this->db->rowCount();
        } catch (Exception $e) {
            // Log error jika perlu
            error_log("Error hapusBarang: " . $e->getMessage());
            return 0;
        }
    }
    public function getUbah($id_barang)
    {
        $this->db->query("SELECT * FROM trx_barang WHERE id_barang = :id");
        $this->db->bind("id", $id_barang);
        return $this->db->single();
    }
    public function ubahBarang($data)
    {
        $this->db->query("SELECT qr_code FROM trx_barang WHERE id_barang = :id");
        $this->db->bind("id", $data['id_barang']);
        $row = $this->db->single();
        if ($row && file_exists($row['qr_code']))
            unlink($row['qr_code']);

        $fotoBarang = $data['foto_lama'];
        if ($_FILES['foto_barang']['error'] === 0) {
            $path = '../public/img/foto-barang/';
            $fotoBarang = $path . uniqid() . '_' . $_FILES['foto_barang']['name'];
            move_uploaded_file($_FILES['foto_barang']['tmp_name'], $fotoBarang);
        }

        $query = "UPDATE trx_barang SET
            foto_barang = :foto, id_jenis_barang = :jenis, id_merek_barang = :merek,
            id_kondisi_barang = :kondisi, jumlah_barang = :jml, id_satuan = :satuan,
            spesifikasi_barang = :spek, tgl_pengadaan_barang = :tgl, keterangan_label = :ket,
            id_lokasi_penyimpanan = :lokasi, deskripsi_detail_lokasi = :det_lokasi,
            id_status = :stat, status_peminjaman = :stat_pinjam
            WHERE id_barang = :id";

        $this->db->query($query);
        $this->db->bind('foto', $fotoBarang);
        $this->db->bind('jenis', $data['sub_barang']);
        $this->db->bind('merek', $data['nama_merek_barang']);
        $this->db->bind('kondisi', $data['kondisi_barang']);
        $this->db->bind('jml', $data['jumlah_barang']);
        $this->db->bind('satuan', $data['satuan']);
        $this->db->bind('spek', $data['spesifikasi_barang']);
        $this->db->bind('tgl', $data['tgl_pengadaan_barang']);
        $this->db->bind('ket', $data['keterangan_label']);
        $this->db->bind('lokasi', $data['lokasi_penyimpanan']);
        $this->db->bind('det_lokasi', $data['deskripsi_detail_lokasi']);
        $this->db->bind('stat', $data['status']);
        $this->db->bind('stat_pinjam', $data['status_pinjam']);
        $this->db->bind('id', $data['id_barang']);

        $this->db->execute();
        return $this->db->rowCount();
    }
    public function cariDataBarang()
    {
        $keyword = $_POST['keyword'];
        $query = "SELECT * FROM detail_barang
            WHERE 
                sub_barang LIKE :keyword
                OR nama_merek_barang LIKE :keyword
                OR nama_lokasi_penyimpanan LIKE :keyword
                OR status_peminjaman LIKE :keyword
                OR tgl_pengadaan_barang LIKE :keyword
                OR kondisi_barang LIKE :keyword
                OR kode_barang LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function cetak($data)
    {
        $ids = is_array($data) ? $data : [$data];
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $query = "SELECT * FROM detail_barang WHERE id_barang IN ($placeholders)";
        $this->db->query($query);
        foreach ($ids as $k => $id) {
            $this->db->bind($k + 1, $id);
        }
        return $this->db->resultSet();
    }

    // --- HAPUS DATA MASTER (Dengan Pengecekan) ---
    public function hapusDataMaster($table, $id)
    {
        $pkColumn = "";
        $fkColumn = "";

        switch ($table) {
            case 'mst_jenis_barang':
                $pkColumn = 'id_jenis_barang';
                $fkColumn = 'id_jenis_barang';
                break;
            case 'mst_merek_barang':
                $pkColumn = 'id_merek_barang';
                $fkColumn = 'id_merek_barang';
                break;
            case 'mst_lokasi_penyimpanan':
                $pkColumn = 'id_lokasi_penyimpanan';
                $fkColumn = 'id_lokasi_penyimpanan';
                break;
            case 'mst_status':
                $pkColumn = 'id_status';
                $fkColumn = 'id_status';
                break;
            case 'mst_satuan':
                $pkColumn = 'id_satuan';
                $fkColumn = 'id_satuan';
                break;
            default:
                return 0;
        }

        $checkQuery = "SELECT COUNT(*) as count FROM trx_barang WHERE $fkColumn = :id";
        $this->db->query($checkQuery);
        $this->db->bind('id', $id);
        $result = $this->db->single();

        if ($result['count'] > 0) {
            return -1; // Kode Error: Data sedang dipakai
        }

        $deleteQuery = "DELETE FROM $table WHERE $pkColumn = :id";
        $this->db->query($deleteQuery);
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }
}