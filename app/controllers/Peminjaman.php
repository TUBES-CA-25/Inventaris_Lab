<?php
class Peminjaman extends Controller
{
    public function index()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        $data['judul'] = 'Barang Laboratorium';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $PeminjamanModel = $this->model('Peminjaman_model');

        $data['barang'] = $PeminjamanModel->getAllBarang();
        $data['sub_barang'] = $PeminjamanModel->getSubBarang();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function cari()
    {
        if (!isset($_SESSION)) session_start();

        $data['judul'] = 'Pencarian Barang';
        $data['profile'] = $this->model("User_model")->profile(['id_user' => $_SESSION['id_user']]);
        $PeminjamanModel = $this->model('Peminjaman_model');

        if (isset($_POST['keyword'])) {
            $data['barang'] = $PeminjamanModel->cariBarang($_POST['keyword']);
        } else {
            $data['barang'] = $PeminjamanModel->getAllBarang();
        }

        $data['sub_barang'] = $PeminjamanModel->getSubBarang();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function tambahItem($id_barang)
    {
        $id_barang = IdObfuscator::decode($id_barang);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }
        if (!isset($_SESSION)) session_start();

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        if (!in_array($id_barang, $_SESSION['keranjang'])) {
            $_SESSION['keranjang'][] = $id_barang;
        }

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function formPeminjaman()
    {
        $data['judul'] = 'Form Pengajuan Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
            $barang_selected = $this->model('Peminjaman_model')->getBarangWhereIn($_SESSION['keranjang']);

            foreach ($barang_selected as $key => $item) {
                $units = $this->model('Peminjaman_model')->getUnitBarangTersedia($item['id_jenis_barang']);
                $barang_selected[$key]['list_unit'] = $units;
            }

            $data['barang_selected'] = $barang_selected;
        } else {
            $data['barang_selected'] = [];
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/from', $data);
        $this->view('templates/footer');
    }

    public function prosesTambahPeminjaman()
    {
        if (empty($_POST['id_jenis_barang'])) {
            Flasher::setFlash('Tidak ada barang yang dipilih.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }

        $dataUser['id_user'] = $_SESSION['id_user'];
        $userProfile = $this->model('User_model')->profile($dataUser);
        
        $dataPayload = $_POST;
        $dataPayload['nama_peminjam'] = $userProfile['nama_user']; 

        if ($this->model('Peminjaman_model')->postDataPeminjaman($dataPayload) > 0) {
            unset($_SESSION['keranjang']); 
            Flasher::setFlash('Pengajuan berhasil! Silakan lengkapi surat.', 'berhasil', '', 'success');
            header('Location: ' . BASEURL . 'Riwayat'); 
            exit;
        } else {
            Flasher::setFlash('Gagal mengajukan peminjaman.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
    }

    public function hapusItem($id_barang)
    {
        $id_barang = IdObfuscator::decode($id_barang);
        if (!$id_barang) {
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION['keranjang'])) {
            $key = array_search($id_barang, $_SESSION['keranjang']);
            
            if ($key !== false) {
                unset($_SESSION['keranjang'][$key]);
                $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
            }
        }

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }
    
    public function detail($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }
        $data['judul'] = 'Detail Peminjaman';
        $data['dataTampilPeminjaman'] = $this->model('Peminjaman_model')->getDetailDataPeminjaman($id_peminjaman);

        $this->view('templates/header', $data);
        $this->view('templates/DetailBarang/index', $data);
        $this->view('templates/footer');
    }

    public function tambahBarang($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }
        $header = $this->model('Peminjaman_model')->getPeminjamanById($id_peminjaman);
        $details = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman);

        if (!$header) {
            Flasher::setFlash('Data transaksi tidak ditemukan.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        $_SESSION['keranjang'] = [];
        $edit_details_map = []; 

        foreach ($details as $item) {
            $_SESSION['keranjang'][] = $item['id_jenis_barang'];

            $edit_details_map[$item['id_jenis_barang']] = [
                'jumlah'     => $item['jumlah'],
                'keterangan' => $item['id_barang'] 
            ];
        }

        $_SESSION['edit_mode'] = true;
        $_SESSION['edit_id_peminjaman'] = $id_peminjaman;
        $_SESSION['edit_header'] = $header;
        $_SESSION['edit_details_map'] = $edit_details_map;

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function prosesUpdatePeminjaman()
    {
        if (!isset($_SESSION['edit_id_peminjaman'])) {
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }

        $dataUser['id_user'] = $_SESSION['id_user'];
        $userProfile = $this->model('User_model')->profile($dataUser);

        $dataPayload = $_POST;
        $dataPayload['id_peminjaman'] = $_SESSION['edit_id_peminjaman']; 
        $dataPayload['nama_peminjam'] = $userProfile['nama_user'];
        $dataPayload['status'] = 'Melengkapi Surat'; 

        if ($this->model('Peminjaman_model')->ubahDataPeminjaman($dataPayload) >= 0) {
            unset($_SESSION['keranjang']);
            unset($_SESSION['edit_mode']);
            unset($_SESSION['edit_id_peminjaman']);
            unset($_SESSION['edit_header']);
            unset($_SESSION['edit_details_map']);

            Flasher::setFlash('Data peminjaman berhasil diperbarui.', 'berhasil', '', 'success');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        } else {
            Flasher::setFlash('Gagal memperbarui data.', 'gagal', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
    }

    public function tolakPengembalian()
    {
        if (!isset($_SESSION['login'])) {
            header("Location:" . BASEURL . "Login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_peminjaman = IdObfuscator::decode($_POST['id_peminjaman']);
            $alasan        = $_POST['alasan_penolakan'];

            if ($this->model('Peminjaman_model')->simpanTolakPengembalian($id_peminjaman, $alasan) > 0) {
                Flasher::setFlash('Berhasil', 'Pengembalian ditolak. Alasan tersimpan.', '', 'warning');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menyimpan penolakan.', '', 'danger');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }
    }
}