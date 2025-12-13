<?php

class Detail_barang_model
{

    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    public function getSubBarang()
    {
        $query = "SELECT * FROM mst_jenis_barang ORDER BY sub_barang";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getMerekBarang()
    {
        $query = "SELECT * FROM mst_merek_barang ORDER BY nama_merek_barang";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getKondisiBarang()
    {
        $query = "SELECT * FROM mst_kondisi_barang ORDER BY kondisi_barang";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getSatuan()
    {
        $query = "SELECT * FROM mst_satuan ORDER BY nama_satuan";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getStatus()
    {
        $query = "SELECT * FROM mst_status ORDER BY status";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getLokasiPenyimpanan()
    {
        $query = "SELECT * FROM mst_lokasi_penyimpanan ORDER BY nama_lokasi_penyimpanan";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getDataBarangByFilters($id_merek_barang, $id_jenis_barang, $id_lokasi) {
        $query = "SELECT 
            b.id_barang,
            b.foto_barang,
            b.deskripsi_barang,
            b.tgl_pengadaan_barang,
            b.keterangan_label,
            b.deskripsi_detail_lokasi,
            b.status_peminjaman,
            b.kode_barang,
            b.qr_code,
            j.sub_barang, 
            m.nama_merek_barang, 
            k.kondisi_barang, 
            s.status, 
            l.nama_lokasi_penyimpanan,
            b.jumlah_barang,
            n.nama_satuan
        FROM trx_barang b
        JOIN mst_jenis_barang j ON b.id_jenis_barang = j.id_jenis_barang
        JOIN mst_merek_barang m ON b.id_merek_barang = m.id_merek_barang
        JOIN mst_kondisi_barang k ON b.id_kondisi_barang = k.id_kondisi_barang
        JOIN mst_status s ON b.id_status = s.id_status
        JOIN mst_lokasi_penyimpanan l ON b.id_lokasi_penyimpanan = l.id_lokasi_penyimpanan
        JOIN mst_satuan n ON b.id_satuan = n.id_satuan
        WHERE 1=1"; 
    
        // Menambahkan filter berdasarkan input dari form
        if (!empty($id_merek_barang)) {
            $query .= " AND b.id_merek_barang = :id_merek_barang";
        }
        if (!empty($id_jenis_barang)) {
            $query .= " AND b.id_jenis_barang = :id_jenis_barang";
        }
        if (!empty($id_lokasi)) {
            $query .= " AND b.id_lokasi_penyimpanan = :id_lokasi";
        }
    
        // Menyiapkan query
        $this->db->query($query);
        
        // Bind parameter filter jika ada
        if (!empty($id_merek_barang)) {
            $this->db->bind(':id_merek_barang', $id_merek_barang);
        }
        if (!empty($id_jenis_barang)) {
            $this->db->bind(':id_jenis_barang', $id_jenis_barang);
        }
        if (!empty($id_lokasi)) {
            $this->db->bind(':id_lokasi', $id_lokasi);
        }
        
        // Menjalankan query dan mengembalikan hasil
        return $this->db->resultSet();
    }
    

    public function postDataBarang($data)
    {
        $ukuranFile = $_FILES['foto_barang']['size'];
        $limit = 2 * 1024 * 1024;

        if ($ukuranFile <= $limit) {
            $joinKodeJenisBarang = "SELECT (mst_jenis_barang.kode_jenis_barang) FROM trx_barang JOIN mst_jenis_barang ON trx_barang.id_jenis_barang = mst_jenis_barang.id_jenis_barang WHERE mst_jenis_barang.id_jenis_barang = trx_barang.id_jenis_barang";
            $this->db->query($joinKodeJenisBarang);
            $kodeJenisBarang = $this->db->single();
            $kodeJenisBarangString = $kodeJenisBarang['kode_jenis_barang'];


            $joinKodeMerekBarang = "SELECT mst_merek_barang.kode_merek_barang FROM trx_barang JOIN mst_merek_barang ON trx_barang.id_merek_barang = mst_merek_barang.id_merek_barang WHERE mst_merek_barang.id_merek_barang = trx_barang.id_merek_barang";
            $this->db->query($joinKodeMerekBarang);
            $kodeMerekBarang = $this->db->single();
            $kodeMerekBarangString = $kodeMerekBarang['kode_merek_barang'];

            $uploadDirectory = '../public/img/foto-barang/';
            $uploadedFile = $_FILES['foto_barang']['tmp_name'];
            $fotoBarang = $uploadDirectory . $_FILES['foto_barang']['name'];

            move_uploaded_file($uploadedFile, $fotoBarang);

            $queryBarang = "INSERT INTO trx_barang (foto_barang, id_jenis_barang, id_merek_barang, id_kondisi_barang, jumlah_barang, id_satuan, deskripsi_barang, tgl_pengadaan_barang, keterangan_label, id_lokasi_penyimpanan, deskripsi_detail_lokasi, id_status, status_peminjaman, kode_barang) VALUES (:foto_barang, :id_jenis_barang, :id_merek_barang, :id_kondisi_barang, :jumlah_barang, :id_satuan, :deskripsi_barang, :tgl_pengadaan_barang, :keterangan_label, :id_lokasi_penyimpanan, :deskripsi_detail_lokasi, :id_status, :status_peminjaman, :kode_barang)";

            $this->db->query($queryBarang);
            $this->db->bind('foto_barang', $fotoBarang);
            $this->db->bind('id_jenis_barang', $data['sub_barang']);
            $this->db->bind('id_merek_barang', $data['nama_merek_barang']);
            $this->db->bind('id_kondisi_barang', $data['kondisi_barang']);
            // $this->db->bind('jumlah_barang', $data['jumlah_barang']);
            $this->db->bind('jumlah_barang', !empty($data['jumlah_barang']) ? $data['jumlah_barang'] : 1);
            $this->db->bind('id_satuan', $data['satuan']);
            $this->db->bind('deskripsi_barang', $data['deskripsi_barang']);
            $this->db->bind('tgl_pengadaan_barang', $data['tgl_pengadaan_barang']);
            $this->db->bind('keterangan_label', $data['keterangan_label']);
            $this->db->bind('id_lokasi_penyimpanan', $data['lokasi_penyimpanan']);
            $this->db->bind('deskripsi_detail_lokasi', $data['deskripsi_detail_lokasi']);
            $this->db->bind('id_status', $data['status']);
            $this->db->bind('status_peminjaman', $data['status_pinjam']);

            function angkaRomawi($number)
            {
                $romans = [
                    'M' => 1000,
                    'CM' => 900,
                    'D' => 500,
                    'CD' => 400,
                    'C' => 100,
                    'XC' => 90,
                    'L' => 50,
                    'XL' => 40,
                    'X' => 10,
                    'IX' => 9,
                    'V' => 5,
                    'IV' => 4,
                    'I' => 1
                ];

                $result = '';

                foreach ($romans as $roman => $value) {
                    $matches = intval($number / $value);
                    $result .= str_repeat($roman, $matches);
                    $number %= $value;
                }


                return $result;
            }
            $month = date('m', strtotime($data['tgl_pengadaan_barang']));
            $romanMonth = angkaRomawi($month);

            $kodeBarang = date('Y', strtotime($data['tgl_pengadaan_barang'])) . '/' . $romanMonth . '/' . $kodeJenisBarangString . '/' . $kodeMerekBarangString . '/' . $data['barang_ke'] . '/' . $data['total_barang'];

            $this->db->bind('kode_barang', $kodeBarang);
            $this->db->execute();
            $idbarang = $this->db->lastInsertId();

            $this->db->query("SELECT * FROM detail_barang WHERE id_barang = :id_barang");
            $this->db->bind("id_barang", $idbarang);
            $detailBarang = $this->db->single();
            $subBarang = $detailBarang['sub_barang'];
            $merekBarang = $detailBarang['nama_merek_barang'];
            $kondisiBarang = $detailBarang['kondisi_barang'];
            $satuan = $detailBarang['nama_satuan'];
            $lokasiPenyimpanan = $detailBarang['nama_lokasi_penyimpanan'];
            $status = $detailBarang['status'];

            $qrCode = "Kode barang \n" . $kodeBarang . "\n\nSub barang \n" . $subBarang . "\n\nMerek barang\n" . $merekBarang . "\n\nKondisi barang\n" . $kondisiBarang . "\n\nJumlah barang\n" . $data['jumlah_barang'] . "\n\nSatuan\n" . $satuan . "\n\nDeskripsi barang\n" . $data['deskripsi_barang'] . "\n\nTanggal pengadaan barang\n" . $data['tgl_pengadaan_barang'] . "\n\nKeterangan label\n" . $data['keterangan_label'] . "\n\nLokasi penyimpanan\n" . $lokasiPenyimpanan . "\n\nDeskripsi detail lokasi\n" . $data['deskripsi_detail_lokasi'] . "\n\nStatus\n" . $status . "\n\nStatus peminjaman\n" . $data['status_pinjam'];

            $uniqueFileName = uniqid("code_") . ".png";
            QRcode::png("$qrCode", "../public/img/qr-code/" . "$uniqueFileName", "M", 4, 4);

            $uploadDirectory = '../public/img/qr-code/';
            $fotoQr = $uploadDirectory . $uniqueFileName;
            move_uploaded_file($_FILES[$uniqueFileName]['tmp_name'], $fotoQr);

            // Update database with the unique filename
            $queryQr = "UPDATE trx_barang SET qr_code = :qr_code WHERE id_barang = :id_barang";
            $this->db->query($queryQr);
            $this->db->bind('qr_code', $fotoQr);
            $this->db->bind('id_barang', $idbarang);
            $this->db->execute();


            return $this->db->rowCount();
        } else {
            Flasher::setFlash('Foto barang', 'gagal', ' diUpload <br> ukuran gambar terlalu besar', 'danger');
            header('Location: ' . BASEURL . 'SemuaBarang');
            exit;
        }
    }

    public function getDataBarang()
    {

        $tampilView = "SELECT * FROM detail_barang;";

        $this->db->query($tampilView);

        return $this->db->resultSet();

    }


    public function getDetailDataBarang($id_barang)
    {
        $this->db->query("SELECT * FROM detail_barang WHERE id_barang = :id_barang");
        $this->db->bind("id_barang", $id_barang);

        return $this->db->single();
    }

    public function hapusBarang($id_barang)
    {
        $this->db->query("SELECT (foto_barang) FROM trx_barang WHERE id_barang = :id_barang;");
        $this->db->bind("id_barang", $id_barang);
        $pathFotoBarang = $this->db->single();
        $pathFotoBarangString = $pathFotoBarang['foto_barang'];

        $lokasi_foto = "C:/xampp/htdocs/inventori/public/img/foto-barang/" . basename($pathFotoBarangString);
        unlink($lokasi_foto);

        $this->db->query("SELECT (qr_code) FROM trx_barang WHERE id_barang = :id_barang;");
        $this->db->bind("id_barang", $id_barang);
        $pathQrCode = $this->db->single();
        $pathQrCodeString = $pathQrCode['qr_code'];

        $lokasi_qr = "C:/xampp/htdocs/inventori/public/img/qr-code/" . basename($pathQrCodeString);
        unlink($lokasi_qr);

        $this->db->query("DELETE FROM trx_barang WHERE id_barang = :id_barang;");
        $this->db->bind("id_barang", $id_barang);

        return $this->db->resultSet();
    }

    public function getUbah($id_barang)
    {
        $tampilView = "SELECT * FROM trx_barang WHERE id_barang = :id_barang;";
        $this->db->query($tampilView);
        $this->db->bind("id_barang", $id_barang);

        return $this->db->single();

    }

    public function ubahBarang($data)
    {

        $this->db->query("SELECT (qr_code) FROM trx_barang WHERE id_barang = :id_barang;");
        $this->db->bind("id_barang", $data['id_barang']);
        $pathQrCode = $this->db->single();
        $pathQrCodeString = $pathQrCode['qr_code'];

        $lokasi_qr = "C:/xampp/htdocs/inventori/public/img/qr-code/" . basename($pathQrCodeString);
        unlink($lokasi_qr);


        $joinKodeJenisBarang = "SELECT (mst_jenis_barang.kode_jenis_barang) FROM trx_barang JOIN mst_jenis_barang ON trx_barang.id_jenis_barang = mst_jenis_barang.id_jenis_barang WHERE  trx_barang.id_jenis_barang = mst_jenis_barang.id_jenis_barang ;";

        $this->db->query($joinKodeJenisBarang);

        $kodeJenisBarang = $this->db->single();
        $kodeJenisBarangString = $kodeJenisBarang['kode_jenis_barang'];


        $joinKodeMerekBarang = "SELECT mst_merek_barang.kode_merek_barang FROM trx_barang JOIN mst_merek_barang ON trx_barang.id_merek_barang = mst_merek_barang.id_merek_barang WHERE trx_barang.id_merek_barang = mst_merek_barang.id_merek_barang;";

        $this->db->query($joinKodeMerekBarang);

        $kodeMerekBarang = $this->db->single();
        $kodeMerekBarangString = $kodeMerekBarang['kode_merek_barang'];


        $uploadDirectory = '../public/img/foto-barang/';
        $uploadedFile = $_FILES['foto_barang']['tmp_name'];
        $fotoBarang = $uploadDirectory . $_FILES['foto_barang']['name'];

        move_uploaded_file($uploadedFile, $fotoBarang);



        $queryBarang = "UPDATE trx_barang SET
        foto_barang = :foto_barang,
        id_jenis_barang = :id_jenis_barang, id_merek_barang = :id_merek_barang, id_kondisi_barang = :id_kondisi_barang, jumlah_barang = :jumlah_barang, id_satuan = :id_satuan, deskripsi_barang = :deskripsi_barang, tgl_pengadaan_barang = :tgl_pengadaan_barang, keterangan_label = :keterangan_label, id_lokasi_penyimpanan = :id_lokasi_penyimpanan, deskripsi_detail_lokasi = :deskripsi_detail_lokasi, id_status = :id_status, status_peminjaman = :status_peminjaman, 
        kode_barang = :kode_barang WHERE id_barang = :id_barang";


        $this->db->query($queryBarang);
        $this->db->bind('foto_barang', $fotoBarang);
        $this->db->bind('id_jenis_barang', $data['sub_barang']);
        $this->db->bind('id_merek_barang', $data['nama_merek_barang']);
        $this->db->bind('id_kondisi_barang', $data['kondisi_barang']);
        $this->db->bind('jumlah_barang', $data['jumlah_barang']);
        $this->db->bind('id_satuan', $data['satuan']);
        $this->db->bind('deskripsi_barang', $data['deskripsi_barang']);
        $this->db->bind('tgl_pengadaan_barang', $data['tgl_pengadaan_barang']);
        $this->db->bind('keterangan_label', $data['keterangan_label']);
        $this->db->bind('id_lokasi_penyimpanan', $data['lokasi_penyimpanan']);
        $this->db->bind('deskripsi_detail_lokasi', $data['deskripsi_detail_lokasi']);
        $this->db->bind('id_status', $data['status']);
        $this->db->bind('status_peminjaman', $data['status_pinjam']);

        function Romawi($number)
        {
            $romans = [
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1
            ];

            $result = '';

            foreach ($romans as $roman => $value) {
                $matches = intval($number / $value);
                $result .= str_repeat($roman, $matches);
                $number %= $value;
            }



            return $result;
        }
        $month = date('m', strtotime($data['tgl_pengadaan_barang']));
        $romanMonth = Romawi($month);

        $kodeBarang = date('Y', strtotime($data['tgl_pengadaan_barang'])) . '/' . $romanMonth . '/' . $kodeJenisBarangString . '/' . $kodeMerekBarangString . '/' . $data['barang_ke'] . '/' . $data['total_barang'];

        $this->db->bind('kode_barang', $kodeBarang);
        $this->db->bind('id_barang', $data['id_barang']);
        $this->db->execute();

        $this->db->query("SELECT * FROM detail_barang WHERE id_barang = :id_barang");
        $this->db->bind("id_barang", $data['id_barang']);
        $detailBarang = $this->db->single();
        $subBarang = $detailBarang['sub_barang'];
        $merekBarang = $detailBarang['nama_merek_barang'];
        $kondisiBarang = $detailBarang['kondisi_barang'];
        $satuan = $detailBarang['nama_satuan'];
        $lokasiPenyimpanan = $detailBarang['nama_lokasi_penyimpanan'];
        $status = $detailBarang['status'];

        $qrCode = "Kode barang \n" . $kodeBarang . "\n\nSub barang \n" . $subBarang . "\n\nMerek barang\n" . $merekBarang . "\n\nKondisi barang\n" . $kondisiBarang . "\n\nJumlah barang\n" . $data['jumlah_barang'] . "\n\nSatuan\n" . $satuan . "\n\nDeskripsi barang\n" . $data['deskripsi_barang'] . "\n\nTanggal pengadaan barang\n" . $data['tgl_pengadaan_barang'] . "\n\nKeterangan label\n" . $data['keterangan_label'] . "\n\nLokasi penyimpanan\n" . $lokasiPenyimpanan . "\n\nDeskripsi detail lokasi\n" . $data['deskripsi_detail_lokasi'] . "\n\nStatus\n" . $status . "\n\nStatus peminjaman\n" . $data['status_pinjam'];

        $uniqueFileName = uniqid("code_") . ".png";
        QRcode::png("$qrCode", "../public/img/qr-code/" . "$uniqueFileName", "M", 4, 4);

        $uploadDirectory = '../public/img/qr-code/';
        $fotoQr = $uploadDirectory . $uniqueFileName;
        move_uploaded_file($_FILES[$uniqueFileName]['tmp_name'], $fotoQr);

        $queryQr = "UPDATE trx_barang SET qr_code = :qr_code WHERE id_barang = :id_barang";
        $this->db->query($queryQr);
        $this->db->bind('qr_code', $fotoQr);
        $this->db->bind('id_barang', $data['id_barang']);
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

    // public function profile($id_user){
    //     $this->db->query("SELECT * FROM trx_data_user WHERE id_user = :id_user");
    //     $this->db->bind("id_user", $id_user);
    //     return $this->db->single(); // Pastikan ini mengembalikan array dengan kunci "detail_barang"

    // }

    public function cetak($data)
    {   
        if(!is_array($data)) {
            $query = "SELECT mst_jenis_barang.sub_barang,
            mst_merek_barang.nama_merek_barang,      
            mst_kondisi_barang.kondisi_barang,
            trx_barang.jumlah_barang,
            mst_satuan.nama_satuan,
            trx_barang.deskripsi_barang,
            trx_barang.tgl_pengadaan_barang,
            trx_barang.kode_barang,
            trx_barang.keterangan_label,
            mst_lokasi_penyimpanan.nama_lokasi_penyimpanan,
            trx_barang.deskripsi_detail_lokasi,
            trx_barang.status_peminjaman,
            trx_barang.foto_barang,
            trx_barang.qr_code,
            mst_status.status
        FROM trx_barang
        JOIN mst_jenis_barang ON trx_barang.id_jenis_barang = mst_jenis_barang.id_jenis_barang
        JOIN mst_merek_barang ON trx_barang.id_merek_barang = mst_merek_barang.id_merek_barang
        JOIN mst_satuan ON trx_barang.id_satuan = mst_satuan.id_satuan
        JOIN mst_kondisi_barang ON trx_barang.id_kondisi_barang = mst_kondisi_barang.id_kondisi_barang
        JOIN mst_lokasi_penyimpanan ON trx_barang.id_lokasi_penyimpanan = mst_lokasi_penyimpanan.id_lokasi_penyimpanan
        JOIN mst_status ON trx_barang.id_status = mst_status.id_status
        WHERE id_barang = :id_barang";

        $this->db->query($query);
        $this->db->bind('id_barang', $data);
        $result[] = $this->db->resultSet();
        }else {

        foreach ($data as $idbarang) {
            $query = "SELECT mst_jenis_barang.sub_barang,
                mst_merek_barang.nama_merek_barang,      
                mst_kondisi_barang.kondisi_barang,
                trx_barang.jumlah_barang,
                mst_satuan.nama_satuan,
                trx_barang.deskripsi_barang,
                trx_barang.tgl_pengadaan_barang,
                trx_barang.kode_barang,
                trx_barang.keterangan_label,
                mst_lokasi_penyimpanan.nama_lokasi_penyimpanan,
                trx_barang.deskripsi_detail_lokasi,
                trx_barang.status_peminjaman,
                trx_barang.foto_barang,
                trx_barang.qr_code,
                mst_status.status
            FROM trx_barang
            JOIN mst_jenis_barang ON trx_barang.id_jenis_barang = mst_jenis_barang.id_jenis_barang
            JOIN mst_merek_barang ON trx_barang.id_merek_barang = mst_merek_barang.id_merek_barang
            JOIN mst_satuan ON trx_barang.id_satuan = mst_satuan.id_satuan
            JOIN mst_kondisi_barang ON trx_barang.id_kondisi_barang = mst_kondisi_barang.id_kondisi_barang
            JOIN mst_lokasi_penyimpanan ON trx_barang.id_lokasi_penyimpanan = mst_lokasi_penyimpanan.id_lokasi_penyimpanan
            JOIN mst_status ON trx_barang.id_status = mst_status.id_status
            WHERE id_barang = :id_barang";

            $this->db->query($query);
            $this->db->bind('id_barang', $idbarang);
            $result[] = $this->db->resultSet();
        }
    }

        return $result;
    }

    

}