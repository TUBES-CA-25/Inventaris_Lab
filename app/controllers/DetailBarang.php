<?php

class DetailBarang extends Controller
{
    public function index()
    {
        $data['judul'] = 'Detail Barang';

        $DetailBarangModel = $this->model('Detail_barang_model');

        $data += [
            'kondisiBarang' => $DetailBarangModel->getKondisiBarang(),
            'satuan' => $DetailBarangModel->getSatuan(),
            'status' => $DetailBarangModel->getStatus(),
            'sub_barang' => $DetailBarangModel->getSubBarang(),
            'nama_merek_barang' => $DetailBarangModel->getMerekBarang(),
            'lokasiPenyimpanan' => $DetailBarangModel->getLokasiPenyimpanan()
        ];

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $lokasi_id = $_POST['lokasi'] ?? '';
        $jenis_barang_id = $_POST['sub_barang'] ?? '';
        $merek_barang_id = $_POST['merek_barang'] ?? '';

        $data['dataTampilBarang'] = $DetailBarangModel->getDataBarangByFilters($merek_barang_id, $jenis_barang_id, $lokasi_id);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id_barang_encoded)
    {
        // Simpan ID yang ter-encode untuk digunakan di link pagination
        $data['id_encoded'] = $id_barang_encoded;

        $id_barang = IdObfuscator::decode($id_barang_encoded);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['dataTampilDetailBarang'] = $DetailBarangModel->getDetailDataBarang($id_barang);
        $id_spesifikasi = $data['dataTampilDetailBarang']['id_spesifikasi'];

        // --- LOGIKA PAGINATION ---
        $data['limit'] = 5;
        // Pastikan halaman minimal adalah 1
        $data['halamanAktif'] = (isset($_GET['p']) && is_numeric($_GET['p'])) ? (int)$_GET['p'] : 1;

        $offset = ($data['halamanAktif'] - 1) * $data['limit'];

        $data['totalData'] = $DetailBarangModel->getTotalUnitsBySpesifikasi($id_spesifikasi);
        $data['totalHalaman'] = ceil($data['totalData'] / $data['limit']);

        // Ambil data yang sudah dipotong LIMIT & OFFSET
        $data['listUnits'] = $DetailBarangModel->getUnitsBySpesifikasiPaged($id_spesifikasi, $data['limit'], $offset);

        // Kirim data ke View
        $data['judul'] = 'Detail Barang';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/detail', $data);
        $this->view('templates/footer');
    }


    public function tambah()
    {
        $data['judul'] = 'Tambah Barang';

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        $DetailBarangModel = $this->model('Detail_barang_model');

        $data['sub_barang'] = $DetailBarangModel->getSubBarang();
        $data['nama_merek_barang'] = $DetailBarangModel->getMerekBarang();
        $data['kondisiBarang'] = $DetailBarangModel->getKondisiBarang();
        $data['satuan'] = $DetailBarangModel->getSatuan();
        $data['status'] = $DetailBarangModel->getStatus();
        $data['lokasiPenyimpanan'] = $DetailBarangModel->getLokasiPenyimpanan();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/form', $data);
        $this->view('templates/footer');
    }

    public function tambahBarang()
    {
        if ($this->model('Detail_barang_model')->postDataBarang($_POST) > 0) {
            Flasher::setFlash('Barang', 'berhasil', ' diTambahkan', 'success');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        } else {
            Flasher::setFlash('Barang', 'gagal', ' diTambahkan </br>barang sudah ada', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function hapus($id_barang)
    {
        $id_barang = IdObfuscator::decode($id_barang);
        if (!$id_barang) {
            Flasher::setFlash('ID Barang', 'tidak valid', '', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        try {
            $result = $this->model('Detail_barang_model')->hapusBarang($id_barang);

            if ($result > 0) {
                Flasher::setFlash('Barang', 'berhasil', ' dihapus', 'success');
            } else {
                Flasher::setFlash('Barang', 'tidak ditemukan atau sudah dihapus', '', 'warning');
            }
        } catch (PDOException $e) {
            error_log("PDO Error hapus barang: " . $e->getMessage());
            Flasher::setFlash('Barang', 'gagal', ' dihapus (Database Error)', 'danger');
        } catch (Exception $e) {
            error_log("Error hapus barang: " . $e->getMessage());
            Flasher::setFlash('Barang', 'gagal', ' dihapus', 'danger');
        }

        header('Location: ' . BASEURL . 'DetailBarang');
        exit;
    }

    public function getUbah()
    {
        $data = $this->model('Detail_barang_model')->getUbah(IdObfuscator::decode($_POST['id_barang']));
        if ($data) {
            $data['id_barang'] = IdObfuscator::encode($data['id_barang']);
        }
        echo json_encode($data);
    }

    public function ubahBarang()
    {
        $_POST['id_barang'] = IdObfuscator::decode($_POST['id_barang']);

        if ($this->model('Detail_barang_model')->ubahBarang($_POST) > 0) {
            Flasher::setFlash('Barang', 'berhasil', ' diUbah', 'success');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        } else {
            Flasher::setFlash('Barang', 'gagal', ' diUbah </br>barang sudah ada', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function cari()
    {
        $data['judul'] = 'Detail Barang';

        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['dataTampilBarang'] = $DetailBarangModel->cariDataBarang();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    public function cetak()
    {
        if (isset($_POST['id_barang']) && !empty($_POST['id_barang'])) {
            $data['judul'] = 'Laporan Detail Barang';

            $ids_barang = $_POST['id_barang'];
            if (is_array($ids_barang)) {
                $ids_barang = array_map(['IdObfuscator', 'decode'], $ids_barang);
            }
            if (empty($ids_barang)) {
                header('Location: ' . BASEURL . 'DetailBarang');
                exit;
            }

            $data['dataCetak'] = $this->model('Detail_barang_model')->cetak($ids_barang);

            $this->view('templates/header', $data);
            $this->view('DetailBarang/print', $data);
        } else {
            Flasher::setFlash('Gagal', 'Pilih minimal satu data barang untuk dicetak.', '', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function ubah($id_barang)
    {
        $id_barang = IdObfuscator::decode($id_barang);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        $data['judul'] = 'Ubah Data Barang';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        $data['barang'] = $this->model('Detail_barang_model')->getDetailDataBarang($id_barang);
        $data['barang']['id_barang'] = IdObfuscator::encode($data['barang']['id_barang']);

        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['sub_barang'] = $DetailBarangModel->getSubBarang();
        $data['nama_merek_barang'] = $DetailBarangModel->getMerekBarang();
        $data['kondisiBarang'] = $DetailBarangModel->getKondisiBarang();
        $data['satuan'] = $DetailBarangModel->getSatuan();
        $data['status'] = $DetailBarangModel->getStatus();
        $data['lokasiPenyimpanan'] = $DetailBarangModel->getLokasiPenyimpanan();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/form', $data);
        $this->view('templates/footer');
    }

    public function cetakSatuan($id_barang)
    {
        $id_barang = IdObfuscator::decode($id_barang);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }
        $data['judul'] = 'Laporan Detail Barang';
        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['item'] = $DetailBarangModel->getDetailDataBarang($id_barang);
        $this->view('DetailBarang/PrintSatu', $data);
    }

    public function hapusMaster($type, $id)
    {
        // Mapping URL parameter ke Nama Tabel Database
        $tableMap = [
            'jenis'  => 'mst_jenis_barang',
            'merek'  => 'mst_merek_barang',
            'lokasi' => 'mst_lokasi_penyimpanan',
            'status' => 'mst_status',
            'satuan' => 'mst_satuan'
        ];

        if (!array_key_exists($type, $tableMap)) {
            Flasher::setFlash('Data', 'tidak valid', '', 'danger');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $table = $tableMap[$type];

        // Panggil method hapusDataMaster di Model
        $result = $this->model('Detail_barang_model')->hapusDataMaster($table, $id);

        if ($result > 0) {
            Flasher::setFlash('Data Master', 'berhasil', ' dihapus', 'success');
        } elseif ($result == -1) {
            Flasher::setFlash('Gagal menghapus!', 'Data sedang digunakan oleh barang lain', '', 'warning');
        } else {
            Flasher::setFlash('Data Master', 'gagal', ' dihapus', 'danger');
        }

        // Kembali ke halaman form sebelumnya
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function ubahUnit($encoded_id)
    {
        $id_barang = IdObfuscator::decode($encoded_id);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        $data['judul'] = 'Ubah Unit Barang';
        $DetailModel = $this->model('Detail_barang_model');

        // Ambil data unit
        $data['unit'] = $DetailModel->getUnitById($id_barang);

        // Pastikan ID asli tersedia untuk dikirim ke view
        $data['unit']['id_barang_asli'] = $id_barang;

        // Data user & profile
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // Ambil data master untuk dropdown di form
        $data['kondisi'] = $DetailModel->getKondisiBarang();
        $data['lokasi'] = $DetailModel->getLokasiPenyimpanan();
        $data['status'] = $DetailModel->getStatus();

        // Urutan view yang benar (header -> sidebar -> content -> footer)
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/ubah_unit', $data);
        $this->view('templates/footer');
    }

    public function prosesUbahUnit()
    {
        if ($this->model('Detail_barang_model')->updateUnit($_POST) > 0) {
            Flasher::setFlash('Unit Barang', 'berhasil', 'diubah', 'success');
        } else {
            Flasher::setFlash('Unit Barang', 'gagal', 'diubah', 'danger');
        }
        // Redirect kembali ke halaman detail Master (induknya)
        header('Location: ' . BASEURL . 'DetailBarang/detail/' . IdObfuscator::encode($_POST['id_spesifikasi']));
        exit;
    }

    public function cetakUnit($encoded_id)
    {
        // 1. Decode ID Barang (Unit)
        $id_unit = IdObfuscator::decode($encoded_id);

        if (!$id_unit) {
            // Jika ID tidak valid/hasil hack URL
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        // 2. Ambil Data Lengkap dari Model yang baru kita buat
        $data['unit'] = $this->model('Detail_barang_model')->getDetailUnitForPrint($id_unit);

        // Cek jika data ditemukan
        if (!$data['unit']) {
            Flasher::setFlash('Data Unit', 'tidak ditemukan', '', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }

        // 3. Panggil View PrintUnit
        // Pastikan nama file View nanti sesuai (PrintUnit.php)
        $this->view('DetailBarang/PrintUnit', $data);
    }
}
