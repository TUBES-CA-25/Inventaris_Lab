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
    
    public function detail($id_barang)
    {
        $data['judul'] = 'Detail Barang';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['dataTampilDetailBarang'] = $DetailBarangModel->getDetailDataBarang($id_barang);

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
        try {
            if ($this->model('Detail_barang_model')->hapusBarang($id_barang) > 0) {
                Flasher::setFlash('Barang', 'berhasil', ' dihapus', 'success');
                header('Location: ' . BASEURL . 'DetailBarang');
                exit;
            }
        } catch (PDOException $e) {
            Flasher::setFlash('Barang', 'gagal', ' dihapus', 'danger');
            header('Location: ' . BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function getUbah()
    {
        echo json_encode($this->model('Detail_barang_model')->getUbah($_POST['id_barang']));
    }

    public function ubahBarang()
    {
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
        $data['judul'] = 'Ubah Barang';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $data['barang'] = $this->model('Detail_barang_model')->getUbah($id_barang);

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
}