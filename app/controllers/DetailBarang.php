<?php 

class DetailBarang extends Controller {
    public function index() {
        $data['judul'] = 'Detail Barang';
    
        // Ambil model
        $DetailBarangModel = $this->model('Detail_barang_model');
    
        // Ambil semua data terkait barang
        $data += [
            'kondisiBarang' => $DetailBarangModel->getKondisiBarang(),
            'satuan' => $DetailBarangModel->getSatuan(),
            'status' => $DetailBarangModel->getStatus(),
            'sub_barang' => $DetailBarangModel->getSubBarang(),
            'nama_merek_barang' => $DetailBarangModel->getMerekBarang(),
            'lokasiPenyimpanan' => $DetailBarangModel->getLokasiPenyimpanan()
        ];
    
        // Ambil data user
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
    
        // Ambil filter dari POST
        $lokasi_id = $_POST['lokasi'] ?? '';
        $jenis_barang_id = $_POST['sub_barang'] ?? '';
        $merek_barang_id = $_POST['merek_barang'] ?? '';
    
        // Gunakan filter gabungan untuk mengambil data barang
        $data['dataTampilBarang'] = $DetailBarangModel->getDataBarangByFilters($merek_barang_id, $jenis_barang_id, $lokasi_id);
    
        // Load tampilan
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }
    
    
    
    public function detail($id_barang) {
        $data['judul'] = 'Detail Barang';
        
        // Mengambil data kondisi barang dari model
        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['dataTampilDetailBarang'] = $DetailBarangModel->getDetailDataBarang($id_barang);

        // Memanggil view transaksi barang
        $this->view('templates/header', $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    public function tambahBarang(){
        if($this->model('Detail_barang_model')->postDataBarang($_POST) > 0){
                Flasher::setFlash('Barang', 'berhasil', ' diTambahkan', 'success');
                header('Location: '. BASEURL . 'DetailBarang');
                exit;
            }
        
        else {
            Flasher::setFlash('Barang', 'gagal', ' diTambahkan </br>barang sudah ada', 'danger');
            header('Location: '. BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function hapus($id_barang){
        try{
            if($this->model('Detail_barang_model')->hapusBarang($id_barang) > 0){
                Flasher::setFlash('Barang', 'berhasil', ' dihapus', 'success');
                header('Location: '. BASEURL . 'DetailBarang');
                exit;
            }

        }catch(PDOException $e){
            Flasher::setFlash('Barang', 'gagal', ' dihapus', 'danger');
            header('Location: '. BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function getUbah(){
        echo json_encode( $this->model('Detail_barang_model')->getUbah($_POST['id_barang']));
    }

    public function ubahBarang(){
        if($this->model('Detail_barang_model')->ubahBarang($_POST) > 0){
            Flasher::setFlash('Barang', 'berhasil', ' diUbah', 'success');
            header('Location: '. BASEURL . 'DetailBarang');
            exit;
        }
        else{
            Flasher::setFlash('Barang', 'gagal', ' diUbah </br>barang sudah ada', 'danger');
            header('Location: '. BASEURL . 'DetailBarang');
            exit;
        }
    }

    public function cari(){
        $data['judul'] = 'Detail Barang';
        
        $DetailBarangModel = $this->model('Detail_barang_model');
        $data['dataTampilBarang'] = $DetailBarangModel->cariDataBarang();
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);
        
        // Memanggil view transaksi barang
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', data: $data);
        $this->view('DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    public function cetak() {
        if(isset($_POST) && !empty($_POST)){
            $temp = $_POST['id_barang'];
            $result = json_decode($temp,true) ;
            $data['judul'] = 'Detail Barang';
            $data['dataCetak'] = $this->model('Detail_barang_model')->cetak($result);
            $this->view('templates/header', $data);
            $this->view('DetailBarang/print', $data);
            $this->view('templates/footer');
        } else {
            Flasher::setFlash('Data barang', var_dump($_POST), ' diCetak </br> Pilih data barang', 'danger');
            header('Location: '. BASEURL . 'DetailBarang');
            exit;
        }
    }

}
