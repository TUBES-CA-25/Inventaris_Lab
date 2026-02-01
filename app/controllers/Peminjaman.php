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

        $_SESSION['keranjang'][] = $id_barang;

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function formPeminjaman()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        $data['judul'] = 'Form Pengajuan Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $data['barang_selected'] = [];

        if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
            $unique_ids = array_values(array_unique($_SESSION['keranjang']));
            $db_items = $this->model('Peminjaman_model')->getBarangWhereIn($unique_ids);

            $item_map = [];
            foreach ($db_items as $item) {
                $item_map[$item['id_jenis_barang']] = $item;
            }

            foreach ($_SESSION['keranjang'] as $index => $sess_id_barang) {
                if (isset($item_map[$sess_id_barang])) {
                    $item_data = $item_map[$sess_id_barang];

                    $units = $this->model('Peminjaman_model')->getUnitBarangTersedia($sess_id_barang);
                    $item_data['list_unit'] = $units;

                    $item_data['hapus_id'] = IdObfuscator::encode($index);

                    $data['barang_selected'][] = $item_data;
                }
            }
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/from', $data);
        $this->view('templates/footer');
    }

    public function prosesTambahPeminjaman()
    {
        // 1. VALIDASI STOK SEBELUM SIMPAN
        if (isset($_POST['unit_selected']) && is_array($_POST['unit_selected'])) {

            foreach ($_POST['unit_selected'] as $index => $id_spesifikasi) {

                // Skip jika user memilih "Lainnya" atau kosong (biasanya tidak dicek stok via sistem ini)
                if ($id_spesifikasi == 'Lainnya' || empty($id_spesifikasi)) {
                    continue;
                }

                $jumlah_pinjam = (int) $_POST['jumlah_peminjaman'][$index];

                // Ambil Stok Tersedia (Baik & Bisa Dipinjam) dari Model
                $stok_tersedia = $this->model('Peminjaman_model')->getStokTersediaBySpesifikasi($id_spesifikasi);

                // LOGIKA PENGECEKAN
                if ($jumlah_pinjam > $stok_tersedia) {

                    // Ambil info barang untuk pesan error yang jelas
                    $infoBarang = $this->model('Peminjaman_model')->getNamaBarangBySpesifikasi($id_spesifikasi);
                    $namaLengkap = $infoBarang['sub_barang'] . ' (' . $infoBarang['spesifikasi_barang'] . ')';

                    // Set Flash Message Khusus (Format SweetAlert)
                    // Pastikan di footer/header Anda ada script untuk menangkap Flash ini
                    Flasher::setFlash('Stok Tidak Cukup!', "Barang <b>$namaLengkap</b> hanya tersisa <b>$stok_tersedia</b> unit yang kondisinya BAIK. Anda meminta <b>$jumlah_pinjam</b>.",'warning');

                    // Kembalikan user ke halaman form
                    header('Location: ' . BASEURL . 'Peminjaman/form');
                    exit;
                }
            }
        }

        // 2. JIKA LOLOS VALIDASI, LANJUTKAN PROSES SIMPAN SEPERTI BIASA
        if ($this->model('Peminjaman_model')->postDataPeminjaman($_POST) > 0) {
            Flasher::setFlash('Berhasil', 'Peminjaman berhasil diajukan', 'success');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'Peminjaman gagal diajukan', 'error');
            header('Location: ' . BASEURL . 'Peminjaman');
            exit;
        }
    }

    public function hapusItem($encoded_index)
    {
        $index = IdObfuscator::decode($encoded_index);

        if (!isset($_SESSION)) session_start();

        if ($index !== false && isset($_SESSION['keranjang'][$index])) {

            unset($_SESSION['keranjang'][$index]);

            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
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

            $edit_details_map[$item['id_jenis_barang']][] = [
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
