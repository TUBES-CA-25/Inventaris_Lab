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
        $this->db->query("SELECT id_jenis_barang, sub_barang, grup_sub, kode_sub, kode_jenis_barang FROM mst_jenis_barang ORDER BY sub_barang");
        return $this->db->resultSet();
    }
    public function getMerekBarang()
    {
        $this->db->query("SELECT id_merek_barang, nama_merek_barang, kode_merek_barang FROM mst_merek_barang ORDER BY nama_merek_barang");
        return $this->db->resultSet();
    }
    public function getKondisiBarang()
    {
        $this->db->query("SELECT id_kondisi_barang, kondisi_barang FROM mst_kondisi_barang ORDER BY kondisi_barang");
        return $this->db->resultSet();
    }
    public function getSatuan()
    {
        $this->db->query("SELECT id_satuan, nama_satuan FROM mst_satuan ORDER BY nama_satuan");
        return $this->db->resultSet();
    }
    public function getStatus()
    {
        $this->db->query("SELECT id_status, status FROM mst_status ORDER BY status");
        return $this->db->resultSet();
    }
    public function getLokasiPenyimpanan()
    {
        $this->db->query("SELECT id_lokasi_penyimpanan, nama_lokasi_penyimpanan FROM mst_lokasi_penyimpanan ORDER BY nama_lokasi_penyimpanan");
        return $this->db->resultSet();
    }

    // Helper function untuk konversi bulan ke romawi
    private function getRomanMonth($monthNumber)
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romans[(int) $monthNumber] ?? 'I';
    }

    public function getDataBarangByFilters($id_merek_barang, $id_jenis_barang, $id_lokasi)
    {
        $query = "SELECT 
            MAX(b.id_barang) as id_barang,
            b.id_spesifikasi,
            spek.foto_barang, 
            spek.spesifikasi_barang,
            spek.kode_barang,  
            spek.jumlah_total, 
            spek.qr_code_spesifikasi as qr_code,
            COUNT(b.id_barang) as jumlah_barang, 
            MAX(b.tgl_pengadaan_barang) as tgl_pengadaan_barang,
            MAX(b.keterangan_label) as keterangan_label, 
            MAX(b.deskripsi_detail_lokasi) as deskripsi_detail_lokasi, 
            MAX(b.status_peminjaman) as status_peminjaman,
            j.sub_barang, 
            m.nama_merek_barang, 
            MAX(k.kondisi_barang) as kondisi_barang, 
            MAX(s.status) as status, 
            MAX(l.nama_lokasi_penyimpanan) as nama_lokasi_penyimpanan, 
            n.nama_satuan

        FROM trx_barang b
        JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
        JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
        JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
        JOIN mst_satuan n ON spek.id_satuan = n.id_satuan
        JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
        JOIN mst_status s ON b.id_status = s.id_status
        JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
        WHERE 1=1";

        if (!empty($id_merek_barang))
            $query .= " AND spek.id_merek_barang = :id_merek_barang";
        if (!empty($id_jenis_barang))
            $query .= " AND spek.id_jenis_barang = :id_jenis_barang";
        if (!empty($id_lokasi))
            $query .= " AND b.id_lokasi_penyimpanan = :id_lokasi";

        $query .= " GROUP BY b.id_spesifikasi";

        $this->db->query($query);

        if (!empty($id_merek_barang))
            $this->db->bind(':id_merek_barang', $id_merek_barang);
        if (!empty($id_jenis_barang))
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        if (!empty($id_lokasi))
            $this->db->bind(':id_lokasi', $id_lokasi);

        return $this->db->resultSet();
    }

    private function insertNewMasterData($table, $column, $value, $extraInput = null)
    {
        // 1. Cek Data Ganda
        $pk = "id_" . str_replace("mst_", "", $table);
        $check = "SELECT $pk FROM $table WHERE $column = :value";
        $this->db->query($check);
        $this->db->bind('value', $value);
        $existing = $this->db->single();

        if ($existing) {
            $pk = "id_" . str_replace("mst_", "", $table);
            return $existing[$pk];
        } else {
            $query = "";

            if ($table == 'mst_jenis_barang') {
                // Bersihkan input (Hanya huruf a-z, jadikan kapital)
                $cleanVal = strtoupper(preg_replace("/[^A-Za-z]/", '', $value));

                $grup = !empty($extraInput) ? strtoupper($extraInput) : 'C';

                $shortCode = substr($cleanVal, 0, 3);

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

    public function postDataBarang($data)
    {
        // 1. INPUT MASTER DATA (Sama seperti sebelumnya)
        if ($data['sub_barang'] == 'NEW' && !empty($data['sub_barang_baru'])) {
            $manualGroup = !empty($data['grup_sub_baru']) ? $data['grup_sub_baru'] : null;
            $data['sub_barang'] = $this->insertNewMasterData('mst_jenis_barang', 'sub_barang', $data['sub_barang_baru'], $manualGroup);
        }
        if ($data['nama_merek_barang'] == 'NEW' && !empty($data['nama_merek_baru'])) {
            $manualCode = !empty($data['kode_merek_baru']) ? $data['kode_merek_baru'] : null;
            $data['nama_merek_barang'] = $this->insertNewMasterData('mst_merek_barang', 'nama_merek_barang', $data['nama_merek_baru'], $manualCode);
        }
        if ($data['lokasi_penyimpanan'] == 'NEW' && !empty($data['lokasi_baru'])) {
            $data['lokasi_penyimpanan'] = $this->insertNewMasterData('mst_lokasi_penyimpanan', 'nama_lokasi_penyimpanan', $data['lokasi_baru']);
        }
        if ($data['status'] == 'NEW' && !empty($data['status_baru'])) {
            $data['status'] = $this->insertNewMasterData('mst_status', 'status', $data['status_baru']);
        }
        if ($data['satuan'] == 'NEW' && !empty($data['satuan_baru'])) {
            $data['satuan'] = $this->insertNewMasterData('mst_satuan', 'nama_satuan', $data['satuan_baru']);
        }

        // 2. UPLOAD FOTO & PREPARE VARIABEL NAMA
        $ukuranFile = $_FILES['foto_barang']['size'];
        $limit = 2 * 1024 * 1024;

        if ($ukuranFile <= $limit) {

            // Ambil Nama-nama untuk Label QR
            $this->db->query("SELECT sub_barang, kode_jenis_barang FROM mst_jenis_barang WHERE id_jenis_barang = :id");
            $this->db->bind('id', $data['sub_barang']);
            $rowJenis = $this->db->single();
            $namaJenis = $rowJenis['sub_barang'];
            $kodeJenisString = $rowJenis['kode_jenis_barang'] ?? 'XXX';

            $this->db->query("SELECT nama_merek_barang, kode_merek_barang FROM mst_merek_barang WHERE id_merek_barang = :id");
            $this->db->bind('id', $data['nama_merek_barang']);
            $rowMerek = $this->db->single();
            $namaMerek = $rowMerek['nama_merek_barang'];
            $kodeMerekString = $rowMerek['kode_merek_barang'] ?? '000';

            $this->db->query("SELECT nama_lokasi_penyimpanan FROM mst_lokasi_penyimpanan WHERE id_lokasi_penyimpanan = :id");
            $this->db->bind('id', $data['lokasi_penyimpanan']);
            $namaLokasi = $this->db->single()['nama_lokasi_penyimpanan'];

            // Ambil Kondisi Text (untuk list detail)
            $this->db->query("SELECT kondisi_barang FROM mst_kondisi_barang WHERE id_kondisi_barang = :id");
            $this->db->bind('id', $data['kondisi_barang']);
            $namaKondisi = $this->db->single()['kondisi_barang'];

            // Upload
            $uploadDirectory = '../public/img/foto-barang/';
            $uploadedFile = $_FILES['foto_barang']['tmp_name'];
            $namaFileUnik = uniqid() . '_' . $_FILES['foto_barang']['name'];
            $fotoBarang = $uploadDirectory . $namaFileUnik;
            move_uploaded_file($uploadedFile, $fotoBarang);

            // 3. INSERT HEADER (MST_SPESIFIKASI)
            $bulanAngka = date('m', strtotime($data['tgl_pengadaan_barang']));
            $bulanRomawi = $this->getRomanMonth($bulanAngka);
            $tahun = date('Y', strtotime($data['tgl_pengadaan_barang']));
            $kodeInisial = $tahun . '/' . $bulanRomawi . '/' . $kodeJenisString . '/' . $kodeMerekString;
            $totalInput = (int) $data['jumlah_barang'];

            $querySpek = "INSERT INTO mst_spesifikasi 
                          (spesifikasi_barang, foto_barang, id_jenis_barang, id_merek_barang, id_satuan, kode_barang, jumlah_total) 
                          VALUES 
                          (:spek, :foto, :id_jenis, :id_merek, :id_satuan, :kode_barang, :jml_total)";

            $this->db->query($querySpek);
            $this->db->bind('spek', $data['spesifikasi_barang']);
            $this->db->bind('foto', $fotoBarang);
            $this->db->bind('id_jenis', $data['sub_barang']);
            $this->db->bind('id_merek', $data['nama_merek_barang']);
            $this->db->bind('id_satuan', $data['satuan']);
            $this->db->bind('kode_barang', $kodeInisial);
            $this->db->bind('jml_total', $totalInput);
            $this->db->execute();

            $idSpesifikasi = $this->db->lastInsertId();

            // Variabel penampung string detail untuk QR Master
            $listDetailString = "";

            // 4. LOOPING INSERT DETAIL & BUILD STRING
            $berhasil = 0;
            $pathQr = '../public/img/qr-code/';
            if (!file_exists($pathQr))
                mkdir($pathQr, 0777, true);

            for ($i = 1; $i <= $totalInput; $i++) {

                $queryBarang = "INSERT INTO trx_barang (
                    id_spesifikasi, id_kondisi_barang, urutan_unit,
                    tgl_pengadaan_barang, keterangan_label, id_lokasi_penyimpanan, 
                    deskripsi_detail_lokasi, id_status, status_peminjaman
                ) VALUES (
                    :id_spek, :id_kondisi_barang, :urutan,
                    :tgl_pengadaan_barang, :keterangan_label, :id_lokasi_penyimpanan, 
                    :deskripsi_detail_lokasi, :id_status, :status_peminjaman
                )";

                $labelDefault = !empty($data['keterangan_label']) ? $data['keterangan_label'] : 'Belum';

                $this->db->query($queryBarang);
                $this->db->bind('id_spek', $idSpesifikasi);
                $this->db->bind('id_kondisi_barang', $data['kondisi_barang']);
                $this->db->bind('urutan', $i);
                $this->db->bind('tgl_pengadaan_barang', $data['tgl_pengadaan_barang']);
                $this->db->bind('keterangan_label', $labelDefault);
                $this->db->bind('id_lokasi_penyimpanan', $data['lokasi_penyimpanan']);
                $this->db->bind('deskripsi_detail_lokasi', $data['deskripsi_detail_lokasi']);
                $this->db->bind('id_status', $data['status']);
                $this->db->bind('status_peminjaman', $data['status_pinjam']);

                $this->db->execute();
                $idbarang = $this->db->lastInsertId();
                $berhasil += $this->db->rowCount();

                // Generate QR Unit (Virtual Code)
                $kodeLengkapVirtual = $kodeInisial . '/' . $i . '/' . $totalInput;

                $listDetailString .= $i . ". [" . $kodeLengkapVirtual . "] - " . $namaKondisi . " - " . $namaLokasi . "\n";

                $qrContentUnit = "Kode: " . $kodeLengkapVirtual . "\n" .
                    "Jenis: " . $namaJenis . "\n" .
                    "Lokasi: " . $namaLokasi . "\n" .
                    "Unit ke: " . $i . " dari " . $totalInput;

                $qrUnitName = uniqid("UNIT_") . ".png";
                QRcode::png($qrContentUnit, $pathQr . $qrUnitName, "M", 4, 4);

                $this->db->query("UPDATE trx_barang SET qr_code = :qr WHERE id_barang = :id");
                $this->db->bind('qr', $pathQr . $qrUnitName);
                $this->db->bind('id', $idbarang);
                $this->db->execute();
            }

            $qrContentMaster = "=== BATCH MASTER ===\n" .
                "Kode Batch: " . $kodeInisial . "\n" .
                "Jenis: " . $namaJenis . " | " . $namaMerek . "\n" .
                "Total: " . $totalInput . " Unit\n" .
                "------------------------\n" .
                "RINCIAN UNIT:\n" .
                $listDetailString;

            $qrMasterName = "MASTER_" . uniqid() . ".png";
            QRcode::png($qrContentMaster, $pathQr . $qrMasterName, "M", 4, 4);

            $this->db->query("UPDATE mst_spesifikasi SET qr_code_spesifikasi = :qr WHERE id_spesifikasi = :id");
            $this->db->bind('qr', $pathQr . $qrMasterName);
            $this->db->bind('id', $idSpesifikasi);
            $this->db->execute();

            return $berhasil;
        } else {
            return 0;
        }
    }

    public function getDataBarang()
    {
        $query = "SELECT 
                b.id_barang,
                b.urutan_unit,
                b.tgl_pengadaan_barang,
                b.keterangan_label,
                b.deskripsi_detail_lokasi,
                b.status_peminjaman,
                b.qr_code,
                
                -- Ambil data dari Master Spesifikasi
                spek.kode_barang, 
                spek.spesifikasi_barang,
                spek.jumlah_total as jumlah_barang, 
                spek.foto_barang,

                -- Data Master Lainnya
                j.sub_barang,
                m.nama_merek_barang,
                s.nama_satuan,
                k.kondisi_barang,
                l.nama_lokasi_penyimpanan,
                st.status

              FROM trx_barang b
              JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
              JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
              JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
              JOIN mst_satuan s ON spek.id_satuan = s.id_satuan
              LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
              LEFT JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
              LEFT JOIN mst_status st ON b.id_status = st.id_status
              
              ORDER BY b.id_barang DESC"; // Urutkan dari yang terbaru

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getDetailDataBarang($id_barang)
    {
        $query = "SELECT 
                    b.id_barang, b.id_spesifikasi, b.id_kondisi_barang, b.urutan_unit,
                    b.tgl_pengadaan_barang, b.keterangan_label, b.id_lokasi_penyimpanan,
                    b.deskripsi_detail_lokasi, b.id_status, b.status_peminjaman, b.qr_code,
                    spek.id_jenis_barang, 
                    spek.id_merek_barang, 
                    spek.id_satuan,
                    spek.spesifikasi_barang, 
                    spek.qr_code_spesifikasi,
                    spek.foto_barang,
                    spek.kode_barang,
                    spek.jumlah_total,
                    j.sub_barang, 
                    m.nama_merek_barang, 
                    s.nama_satuan, 
                    l.nama_lokasi_penyimpanan, 
                    k.kondisi_barang, 
                    st.status
                    
                  FROM trx_barang b
                  JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                  JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
                  JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
                  JOIN mst_satuan s ON spek.id_satuan = s.id_satuan
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
            $this->db->query("SELECT id_spesifikasi FROM trx_barang WHERE id_barang = :id");
            $this->db->bind("id", $id_barang);
            $row = $this->db->single();

            if (!$row) {
                return 0; // Data tidak ditemukan
            }

            $idSpesifikasi = $row['id_spesifikasi'];

            $this->db->query("SELECT foto_barang, qr_code_spesifikasi FROM mst_spesifikasi WHERE id_spesifikasi = :id");
            $this->db->bind("id", $idSpesifikasi);
            $dataSpek = $this->db->single();

            $this->db->query("SELECT qr_code FROM trx_barang WHERE id_spesifikasi = :id");
            $this->db->bind("id", $idSpesifikasi);
            $dataUnit = $this->db->resultSet();

            if (!empty($dataSpek['foto_barang']) && file_exists($dataSpek['foto_barang'])) {
                @unlink($dataSpek['foto_barang']);
            }
            // Hapus QR Master
            if (!empty($dataSpek['qr_code_spesifikasi']) && file_exists($dataSpek['qr_code_spesifikasi'])) {
                @unlink($dataSpek['qr_code_spesifikasi']);
            }
            // Hapus Semua QR Unit
            foreach ($dataUnit as $unit) {
                if (!empty($unit['qr_code']) && file_exists($unit['qr_code'])) {
                    @unlink($unit['qr_code']);
                }
            }

            $this->db->query("DELETE FROM trx_barang WHERE id_spesifikasi = :id");
            $this->db->bind("id", $idSpesifikasi);
            $this->db->execute();
            $this->db->query("DELETE FROM mst_spesifikasi WHERE id_spesifikasi = :id");
            $this->db->bind("id", $idSpesifikasi);
            $this->db->execute();

            return $this->db->rowCount(); // Berhasil

        } catch (Exception $e) {
            error_log("Error hapusBarang: " . $e->getMessage());
            return 0;
        }
    }

    public function getUbah($id_barang)
    {
        $query = "SELECT 
                    b.id_barang, b.id_spesifikasi, b.id_kondisi_barang, b.urutan_unit,
                    b.tgl_pengadaan_barang, b.keterangan_label, b.id_lokasi_penyimpanan,
                    b.deskripsi_detail_lokasi, b.id_status, b.status_peminjaman, b.qr_code, 
                    spek.id_jenis_barang, 
                    spek.id_merek_barang, 
                    spek.id_satuan,
                    spek.spesifikasi_barang, 
                    spek.foto_barang,
                    spek.kode_barang,
                    spek.jumlah_total
                  FROM trx_barang b
                  JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                  WHERE b.id_barang = :id";

        $this->db->query($query);
        $this->db->bind("id", $id_barang);
        return $this->db->single();
    }

    public function ubahBarang($data)
    {
        $this->db->query("SELECT b.id_spesifikasi, b.qr_code, spek.qr_code_spesifikasi,
                      spek.kode_barang, spek.jumlah_total
                      FROM trx_barang b
                      JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                      WHERE b.id_barang = :id");
        $this->db->bind("id", $data['id_barang']);
        $curr = $this->db->single();

        if (!$curr)
            return 0;
        $idSpek = $curr['id_spesifikasi'];
        $oldTotal = (int) $curr['jumlah_total'];
        $newTotal = (int) $data['jumlah_barang'];

        $fotoBarang = $data['foto_lama'];
        if ($_FILES['foto_barang']['error'] === 0) {
            $path = '../public/img/foto-barang/';
            $fotoBarang = $path . uniqid() . '_' . $_FILES['foto_barang']['name'];
            move_uploaded_file($_FILES['foto_barang']['tmp_name'], $fotoBarang);

            if (!empty($data['foto_lama']) && file_exists($data['foto_lama'])) {
                @unlink($data['foto_lama']);
            }
        }

        $this->db->query("SELECT kode_jenis_barang FROM mst_jenis_barang WHERE id_jenis_barang = :id");
        $this->db->bind('id', $data['sub_barang']);
        $kodeJenisString = $this->db->single()['kode_jenis_barang'] ?? 'XXX';

        $this->db->query("SELECT kode_merek_barang FROM mst_merek_barang WHERE id_merek_barang = :id");
        $this->db->bind('id', $data['nama_merek_barang']);
        $kodeMerekString = $this->db->single()['kode_merek_barang'] ?? '000';

        $bulanAngka = date('m', strtotime($data['tgl_pengadaan_barang']));
        $bulanRomawi = $this->getRomanMonth($bulanAngka);
        $tahun = date('Y', strtotime($data['tgl_pengadaan_barang']));
        $kodeBarangBaru = $tahun . '/' . $bulanRomawi . '/' . $kodeJenisString . '/' . $kodeMerekString;

        $querySpek = "UPDATE mst_spesifikasi SET
        foto_barang = :foto,
        id_jenis_barang = :jenis,
        id_merek_barang = :merek,
        id_satuan = :satuan,
        spesifikasi_barang = :spek,
        kode_barang = :kode_brg,
        jumlah_total = :jml_total
        WHERE id_spesifikasi = :idspek";
        $this->db->query($querySpek);
        $this->db->bind('foto', $fotoBarang);
        $this->db->bind('jenis', $data['sub_barang']);
        $this->db->bind('merek', $data['nama_merek_barang']);
        $this->db->bind('satuan', $data['satuan']);
        $this->db->bind('spek', $data['spesifikasi_barang']);
        $this->db->bind('kode_brg', $kodeBarangBaru);
        $this->db->bind('jml_total', $newTotal);
        $this->db->bind('idspek', $idSpek);
        $this->db->execute();

        $queryBarang = "UPDATE trx_barang SET
        id_kondisi_barang = :kondisi, tgl_pengadaan_barang = :tgl, keterangan_label = :ket,
        id_lokasi_penyimpanan = :lokasi, deskripsi_detail_lokasi = :det_lokasi,
        id_status = :stat, status_peminjaman = :stat_pinjam
        WHERE id_barang = :id";
        $this->db->query($queryBarang);
        $this->db->bind('kondisi', $data['kondisi_barang']);
        $this->db->bind('tgl', $data['tgl_pengadaan_barang']);
        $this->db->bind('ket', $data['keterangan_label']);
        $this->db->bind('lokasi', $data['lokasi_penyimpanan']);
        $this->db->bind('det_lokasi', $data['deskripsi_detail_lokasi']);
        $this->db->bind('stat', $data['status']);
        $this->db->bind('stat_pinjam', $data['status_pinjam']);
        $this->db->bind('id', $data['id_barang']);
        $this->db->execute();

        $pathQr = '../public/img/qr-code/';
        if (!file_exists($pathQr))
            mkdir($pathQr, 0777, true);

        $this->db->query("SELECT sub_barang FROM mst_jenis_barang WHERE id_jenis_barang = :id");
        $this->db->bind('id', $data['sub_barang']);
        $namaJenis = $this->db->single()['sub_barang'];

        $this->db->query("SELECT nama_lokasi_penyimpanan FROM mst_lokasi_penyimpanan WHERE id_lokasi_penyimpanan = :id");
        $this->db->bind('id', $data['lokasi_penyimpanan']);
        $namaLokasi = $this->db->single()['nama_lokasi_penyimpanan'];

        if ($newTotal > $oldTotal) {
            for ($i = $oldTotal + 1; $i <= $newTotal; $i++) {
                $queryInsert = "INSERT INTO trx_barang (
                id_spesifikasi, id_kondisi_barang, urutan_unit,
                tgl_pengadaan_barang, keterangan_label, id_lokasi_penyimpanan,
                deskripsi_detail_lokasi, id_status, status_peminjaman
            ) VALUES (
                :id_spek, :id_kondisi, :urutan,
                :tgl, :ket, :lokasi, :deskripsi, :status, :pinjam
            )";

                $labelDefault = !empty($data['keterangan_label']) ? $data['keterangan_label'] : 'Belum';
                $this->db->query($queryInsert);
                $this->db->bind('id_spek', $idSpek);
                $this->db->bind('id_kondisi', $data['kondisi_barang']);
                $this->db->bind('urutan', $i);
                $this->db->bind('tgl', $data['tgl_pengadaan_barang']);
                $this->db->bind('ket', $labelDefault);
                $this->db->bind('lokasi', $data['lokasi_penyimpanan']);
                $this->db->bind('deskripsi', $data['deskripsi_detail_lokasi']);
                $this->db->bind('status', $data['status']);
                $this->db->bind('pinjam', $data['status_pinjam']);
                $this->db->execute();

                $newId = $this->db->lastInsertId();

                $kodeLengkapVirtual = $kodeBarangBaru . '/' . $i . '/' . $newTotal;
                $qrContentUnit = "Kode: " . $kodeLengkapVirtual . "\n" .
                    "Jenis: " . $namaJenis . "\n" .
                    "Lokasi: " . $namaLokasi . "\n" .
                    "Unit ke: " . $i . " dari " . $newTotal;
                $qrUnitName = uniqid("UNIT_NEW_") . ".png";
                QRcode::png($qrContentUnit, $pathQr . $qrUnitName, "M", 4, 4);
                $this->db->query("UPDATE trx_barang SET qr_code = :qr WHERE id_barang = :id");
                $this->db->bind('qr', $pathQr . $qrUnitName);
                $this->db->bind('id', $newId);
                $this->db->execute();
            }
        } elseif ($newTotal < $oldTotal) {
            $this->db->query("SELECT qr_code FROM trx_barang WHERE id_spesifikasi = :id AND urutan_unit > :new_total");
            $this->db->bind('id', $idSpek);
            $this->db->bind('new_total', $newTotal);
            $toDelete = $this->db->resultSet();
            foreach ($toDelete as $row) {
                if (!empty($row['qr_code']) && file_exists($row['qr_code']))
                    @unlink($row['qr_code']);
            }

            $this->db->query("DELETE FROM trx_barang WHERE id_spesifikasi = :id AND urutan_unit > :new_total");
            $this->db->bind('id', $idSpek);
            $this->db->bind('new_total', $newTotal);
            $this->db->execute();
        }

        $det = $this->getDetailDataBarang($data['id_barang']);
        if (!empty($curr['qr_code']) && file_exists($curr['qr_code']))
            @unlink($curr['qr_code']);

        $kodeLengkapVirtual = $kodeBarangBaru . '/' . $det['urutan_unit'] . '/' . $newTotal;

        $qrContentUnit = "Kode: " . $kodeLengkapVirtual . "\n" .
            "Jenis: " . $det['sub_barang'] . "\n" .
            "Lokasi: " . $det['nama_lokasi_penyimpanan'] . "\n" .
            "Kondisi: " . $det['kondisi_barang'];
        $qrUnitName = uniqid("UNIT_UPD_") . ".png";
        QRcode::png($qrContentUnit, $pathQr . $qrUnitName, "M", 4, 4);
        $this->db->query("UPDATE trx_barang SET qr_code = :qr WHERE id_barang = :id");
        $this->db->bind('qr', $pathQr . $qrUnitName);
        $this->db->bind('id', $data['id_barang']);
        $this->db->execute();

        $queryAllUnits = "SELECT b.urutan_unit, k.kondisi_barang, l.nama_lokasi_penyimpanan
                      FROM trx_barang b
                      JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
                      JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
                      WHERE b.id_spesifikasi = :idspek
                      ORDER BY b.urutan_unit ASC";
        $this->db->query($queryAllUnits);
        $this->db->bind('idspek', $idSpek);
        $allUnits = $this->db->resultSet();
        $listDetailString = "";
        foreach ($allUnits as $unit) {
            $listDetailString .= $unit['urutan_unit'] . ". " . $unit['kondisi_barang'] . " @ " . $unit['nama_lokasi_penyimpanan'] . "\n";
        }

        if (!empty($curr['qr_code_spesifikasi']) && file_exists($curr['qr_code_spesifikasi'])) {
            @unlink($curr['qr_code_spesifikasi']);
        }

        $qrContentMaster = "=== BATCH MASTER ===\n" .
            "Kode: " . $kodeBarangBaru . "\n" .
            "Total: " . $newTotal . " Unit\n" .
            "-------------------\n" .
            $listDetailString;
        $qrMasterName = "MASTER_SPEK_UPD_" . uniqid() . ".png";
        QRcode::png($qrContentMaster, $pathQr . $qrMasterName, "M", 4, 4);
        $this->db->query("UPDATE mst_spesifikasi SET qr_code_spesifikasi = :qr WHERE id_spesifikasi = :idspek");
        $this->db->bind('qr', $pathQr . $qrMasterName);
        $this->db->bind('idspek', $idSpek);
        $this->db->execute();

        return 1;
    }

    public function cariDataBarang()
    {
        $keyword = $_POST['keyword'];
        $query = "SELECT 
                MAX(b.id_barang) as id_barang, 
                spek.kode_barang, 
                j.sub_barang, 
                m.nama_merek_barang, 
                spek.spesifikasi_barang,
                spek.jumlah_total,
                n.nama_satuan,
                MAX(k.kondisi_barang) as kondisi_barang
              FROM trx_barang b
              JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
              JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
              JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
              JOIN mst_satuan n ON spek.id_satuan = n.id_satuan
              LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
              WHERE 
                j.sub_barang LIKE :keyword
                OR m.nama_merek_barang LIKE :keyword
                OR spek.spesifikasi_barang LIKE :keyword
                OR spek.kode_barang LIKE :keyword
              GROUP BY spek.id_spesifikasi";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function cetak($data)
    {
        $ids = is_array($data) ? $data : [$data];
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';

        $query = "SELECT 
                b.id_barang, b.urutan_unit, b.tgl_pengadaan_barang, b.keterangan_label,
                b.deskripsi_detail_lokasi, b.status_peminjaman, b.qr_code,
                spek.kode_barang, spek.spesifikasi_barang, spek.jumlah_total as jumlah_barang, spek.foto_barang,
                j.sub_barang, m.nama_merek_barang, s.nama_satuan,
                k.kondisi_barang, l.nama_lokasi_penyimpanan, st.status
              FROM trx_barang b
              JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
              JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
              JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
              JOIN mst_satuan s ON spek.id_satuan = s.id_satuan
              LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
              LEFT JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
              LEFT JOIN mst_status st ON b.id_status = st.id_status
              
              WHERE b.id_barang IN ($placeholders)";

        $this->db->query($query);
        foreach ($ids as $k => $id) {
            $this->db->bind($k + 1, $id);
        }
        return $this->db->resultSet();
    }

    // --- HAPUS DATA MASTER (Updated by Andi's Assistant) ---
    public function hapusDataMaster($table, $id)
    {
        $pkColumn = "";
        $queriesToCheck = []; // Array untuk menampung query pengecekan

        switch ($table) {
            case 'mst_jenis_barang':
                $pkColumn = 'id_jenis_barang';
                // 1. Cek di tabel mst_spesifikasi (Header Barang)
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM mst_spesifikasi WHERE id_jenis_barang = :id";
                // 2. Cek di tabel trx_detail_peminjaman (Transaksi Peminjaman)
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM trx_detail_peminjaman WHERE id_jenis_barang = :id";
                break;

            case 'mst_merek_barang':
                $pkColumn = 'id_merek_barang';
                // Cek di tabel mst_spesifikasi
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM mst_spesifikasi WHERE id_merek_barang = :id";
                break;

            case 'mst_satuan':
                $pkColumn = 'id_satuan';
                // Cek di tabel mst_spesifikasi
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM mst_spesifikasi WHERE id_satuan = :id";
                break;

            case 'mst_lokasi_penyimpanan':
                $pkColumn = 'id_lokasi_penyimpanan';
                // Cek di tabel trx_barang (Unit Fisik)
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM trx_barang WHERE id_lokasi_penyimpanan = :id";
                break;

            case 'mst_status':
                $pkColumn = 'id_status';
                // Cek di tabel trx_barang (Unit Fisik)
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM trx_barang WHERE id_status = :id";
                break;

            case 'mst_kondisi_barang':
                $pkColumn = 'id_kondisi_barang';
                // Cek di tabel trx_barang (Unit Fisik)
                $queriesToCheck[] = "SELECT COUNT(*) as count FROM trx_barang WHERE id_kondisi_barang = :id";
                break;

            default:
                return 0; // Tabel tidak dikenali
        }

        // --- PROSES PENGECEKAN DATA GANDA (RELASI) ---
        foreach ($queriesToCheck as $query) {
            $this->db->query($query);
            $this->db->bind('id', $id);
            $result = $this->db->single();

            if ($result['count'] > 0) {
                return -1; // Kode Error: Data sedang dipakai (Constraint Fail)
            }
        }

        // --- PROSES HAPUS JIKA AMAN ---
        try {
            $deleteQuery = "DELETE FROM $table WHERE $pkColumn = :id";
            $this->db->query($deleteQuery);
            $this->db->bind('id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        } catch (PDOException $e) {
            // Tangkap error constraint database jika ada yang terlewat
            return -1;
        }
    }

    public function getUnitsBySpesifikasi($id_spesifikasi)
    {
        $query = "SELECT 
                    b.*, 
                    l.nama_lokasi_penyimpanan, 
                    k.kondisi_barang, 
                    st.status
                  FROM trx_barang b
                  LEFT JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
                  LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
                  LEFT JOIN mst_status st ON b.id_status = st.id_status
                  WHERE b.id_spesifikasi = :id
                  ORDER BY b.urutan_unit ASC";

        $this->db->query($query);
        $this->db->bind('id', $id_spesifikasi);
        return $this->db->resultSet();
    }

    // --- TAMBAHAN KHUSUS UNTUK EDIT UNIT (Copy paste di bagian paling bawah class) ---

    // 1. Ambil data lengkap satu unit berdasarkan ID Barang (trx_barang)
    public function getUnitById($id_barang)
    {
        $query = "SELECT 
                    b.*,
                    spek.kode_barang,
                    spek.jumlah_total,
                    spek.id_spesifikasi,
                    j.sub_barang,
                    m.nama_merek_barang
                  FROM trx_barang b
                  JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                  JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
                  JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
                  WHERE b.id_barang = :id";

        $this->db->query($query);
        $this->db->bind('id', $id_barang);
        return $this->db->single();
    }

    // 2. Fungsi Update Unit (Hanya update data unit, bukan master spek)
    public function updateUnit($data)
    {
        // Update data fisik unit
        $query = "UPDATE trx_barang SET 
                    id_kondisi_barang = :kondisi,
                    id_lokasi_penyimpanan = :lokasi,
                    deskripsi_detail_lokasi = :detail_lokasi,
                    id_status = :status,
                    keterangan_label = :label,
                    status_peminjaman = :pinjam
                  WHERE id_barang = :id";

        $this->db->query($query);
        $this->db->bind('kondisi', $data['id_kondisi_barang']);
        $this->db->bind('lokasi', $data['id_lokasi_penyimpanan']);
        $this->db->bind('detail_lokasi', $data['deskripsi_detail_lokasi']);
        $this->db->bind('status', $data['id_status']);
        $this->db->bind('label', $data['keterangan_label']);
        $this->db->bind('pinjam', $data['status_peminjaman']);
        $this->db->bind('id', $data['id_barang']);

        $this->db->execute();

        // UPDATE QR CODE UNIT (Karena lokasi/kondisi mungkin berubah)
        // Ambil data terbaru untuk generate QR
        $unit = $this->getUnitById($data['id_barang']);

        // Ambil nama lokasi & kondisi baru (untuk text QR)
        $this->db->query("SELECT nama_lokasi_penyimpanan FROM mst_lokasi_penyimpanan WHERE id_lokasi_penyimpanan = :id");
        $this->db->bind('id', $data['id_lokasi_penyimpanan']);
        $namaLokasi = $this->db->single()['nama_lokasi_penyimpanan'];

        $this->db->query("SELECT kondisi_barang FROM mst_kondisi_barang WHERE id_kondisi_barang = :id");
        $this->db->bind('id', $data['id_kondisi_barang']);
        $namaKondisi = $this->db->single()['kondisi_barang'];

        // Kode Unit (Contoh: 2026/01/C/LP1/401/30/5)
        $kodeFull = $unit['kode_barang'] . '/' . $unit['jumlah_total'] . '/' . $unit['urutan_unit'];

        $qrContent = "Kode: " . $kodeFull . "\n" .
            "Jenis: " . $unit['sub_barang'] . " (" . $unit['nama_merek_barang'] . ")\n" .
            "Lokasi: " . $namaLokasi . "\n" .
            "Kondisi: " . $namaKondisi;

        // Generate QR Baru (Overwrite file lama atau buat baru jika nama file random)
        $pathQr = '../public/img/qr-code/';
        $qrName = basename($unit['qr_code']);

        // Jika belum ada QR sebelumnya, buat nama baru
        if (empty($qrName) || !file_exists($pathQr . $qrName)) {
            $qrName = uniqid("UNIT_UPD_") . ".png";

            // Update path di DB jika file baru
            $this->db->query("UPDATE trx_barang SET qr_code = :qr WHERE id_barang = :id");
            $this->db->bind('qr', $pathQr . $qrName);
            $this->db->bind('id', $data['id_barang']);
            $this->db->execute();
        }

        // Pastikan library QR Code sudah di-include di controller atau bootstrap
        // Jika QRcode belum terload otomatis, uncomment baris di bawah:
        // require_once '../app/core/phpqrcode/qrlib.php'; 

        QRcode::png($qrContent, $pathQr . $qrName, "M", 4, 4);

        return $this->db->rowCount();
    }

    // --- KHUSUS CETAK PDF PER UNIT ---
    public function getDetailUnitForPrint($id_barang)
    {
        // Kita perlu JOIN banyak tabel agar data di PDF lengkap (bukan cuma ID)
        $query = "SELECT 
                    b.*,
                    -- Data dari Master Spesifikasi
                    spek.kode_barang AS kode_master,
                    spek.foto_barang,
                    spek.jumlah_total,
                    
                    -- Data Teks (Join)
                    j.sub_barang, 
                    m.nama_merek_barang, 
                    l.nama_lokasi_penyimpanan, 
                    k.kondisi_barang,
                    s.nama_satuan

                  FROM trx_barang b
                  JOIN mst_spesifikasi spek ON b.id_spesifikasi = spek.id_spesifikasi
                  JOIN mst_jenis_barang j ON spek.id_jenis_barang = j.id_jenis_barang
                  JOIN mst_merek_barang m ON spek.id_merek_barang = m.id_merek_barang
                  JOIN mst_satuan s ON spek.id_satuan = s.id_satuan
                  
                  -- Left Join untuk data unit yang mungkin kosong/berubah
                  LEFT JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
                  LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
                  
                  WHERE b.id_barang = :id";

        $this->db->query($query);
        $this->db->bind('id', $id_barang);
        return $this->db->single();
    }

    // Fungsi baru untuk mengambil unit dengan limit (pagination)
    public function getUnitsBySpesifikasiPaged($id_spesifikasi, $limit, $offset)
    {
        // Pastikan limit dan offset adalah integer murni
        $limit = (int) $limit;
        $offset = (int) $offset;

        // Masukkan langsung ke query untuk menghindari error binding LIMIT/OFFSET di beberapa versi PDO
        $query = "SELECT 
                b.*, 
                l.nama_lokasi_penyimpanan, 
                k.kondisi_barang, 
                st.status
              FROM trx_barang b
              LEFT JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
              LEFT JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
              LEFT JOIN mst_status st ON b.id_status = st.id_status
              WHERE b.id_spesifikasi = :id
              ORDER BY b.urutan_unit ASC
              LIMIT $limit OFFSET $offset";

        $this->db->query($query);
        $this->db->bind('id', $id_spesifikasi);
        return $this->db->resultSet();
    }

    // Fungsi untuk menghitung total unit agar kita tahu ada berapa halaman
    public function getTotalUnitsBySpesifikasi($id_spesifikasi)
    {
        $this->db->query("SELECT COUNT(*) as total FROM trx_barang WHERE id_spesifikasi = :id");
        $this->db->bind('id', $id_spesifikasi);
        return $this->db->single()['total'];
    }
}
