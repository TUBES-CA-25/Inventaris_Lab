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

        $data['isEdit'] = (isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true);
        $data['cart_count'] = isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0;

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/index', $data);
        $this->view('templates/footer');
    }

    public function cari()
    {
        if (!isset($_SESSION))
            session_start();

        $data['judul'] = 'Pencarian Barang';
        $data['profile'] = $this->model("User_model")->profile(['id_user' => $_SESSION['id_user']]);
        $PeminjamanModel = $this->model('Peminjaman_model');

        if (isset($_POST['keyword'])) {
            $data['barang'] = $PeminjamanModel->cariBarang($_POST['keyword']);
        } else {
            $data['barang'] = $PeminjamanModel->getAllBarang();
        }

        $data['sub_barang'] = $PeminjamanModel->getSubBarang();
        $data['cart_count'] = isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0;

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
        if (!isset($_SESSION))
            session_start();

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        $_SESSION['keranjang'][] = $id_barang;

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function formPeminjaman()
    {
        $data['judul'] = 'Form Pengajuan Peminjaman';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        // --- RESTORE DRAFT DATA ---
        $draft = $_SESSION['peminjaman_draft'] ?? [];
        $data['val_judul'] = $draft['judul_kegiatan'] ?? '';
        $data['val_tgl_aju'] = $draft['tanggal_pengajuan'] ?? date('Y-m-d');
        $data['val_tgl_mulai'] = $draft['tanggal_peminjaman'] ?? '';
        $data['val_tgl_akhir'] = $draft['tanggal_pengembalian'] ?? '';
        $data['val_ket'] = $draft['keterangan_peminjaman'] ?? '';
        $data['val_tujuan_lain'] = $draft['tujuan_lain'] ?? '';
        $data['val_tujuan_ta'] = $draft['tujuan_ta'] ?? '';
        $data['val_tujuan_riset'] = $draft['tujuan_riset'] ?? '';
        $data['val_dosen'] = $draft['dosen_pembimbing'] ?? '';
        // ---------------------------

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

                    // --- [PERBAIKAN 1: Ambil Spesifikasi, Bukan Unit] ---
                    // Pastikan model getSpesifikasiByJenis sudah ada di Peminjaman_model.php
                    $units = $this->model('Peminjaman_model')->getSpesifikasiByJenis($sess_id_barang);
                    $item_data['list_unit'] = $units;

                    $item_data['hapus_id'] = IdObfuscator::encode($index);

                    $data['barang_selected'][] = $item_data;
                }
            }
        }

        // Fetch Lecturers (Role ID 5)
        $data['list_dosen'] = $this->model('User_model')->getUsersByRole(5);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/from', $data);
        $this->view('templates/footer');
    }

    public function prosesTambahPeminjaman()
    {
        // 1. Wajib Start Session
        if (!isset($_SESSION))
            session_start();

        // 2. Cek Data Kosong
        if (empty($_POST['id_jenis_barang'])) {
            Flasher::setFlash('Gagal', 'Tidak ada barang yang dipilih.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }

        // 3. Validasi Stok & Tanggal
        $this->cekValidasiTanggal($_POST);
        $this->cekValidasiStok($_POST);

        // 4. Pastikan User Login
        if (!isset($_SESSION['id_user'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }

        // 5. Siapkan Data
        $dataUser['id_user'] = $_SESSION['id_user'];
        $userProfile = $this->model('User_model')->profile($dataUser);

        $dataPayload = $_POST;
        $dataPayload['nama_peminjam'] = $userProfile['nama_user'];
        $dataPayload['tanggal_pengajuan'] = date('Y-m-d'); // Otomatis hari ini
        $dataPayload['id_user'] = $_SESSION['id_user'];
        // -------------------------------------

        // 6. Eksekusi Database
        if ($this->model('Peminjaman_model')->postDataPeminjaman($dataPayload) > 0) {

            // Log Activity for Assistant/Korlab
            if (in_array($_SESSION['id_role'], [3, 4])) {
                $this->model('Beranda_model')->logActivity(
                    $_SESSION['id_user'],
                    'MEMINJAM',
                    'Melakukan peminjaman barang untuk kegiatan: ' . ($dataPayload['judul_kegiatan'] ?? '-')
                );
            }

            // BERHASIL: Bersihkan keranjang dan draft
            unset($_SESSION['keranjang']);
            unset($_SESSION['peminjaman_draft']);

            Flasher::setFlash('Berhasil!', 'Pengajuan peminjaman berhasil dibuat. Silakan cek riwayat.', '', 'success');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        } else {
            // GAGAL
            Flasher::setFlash('Gagal', 'Terjadi kesalahan saat menyimpan ke database.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
    }

    public function hapusItem($encoded_index)
    {
        $index = IdObfuscator::decode($encoded_index);

        if (!isset($_SESSION))
            session_start();

        if ($index !== false && isset($_SESSION['keranjang'][$index])) {

            unset($_SESSION['keranjang'][$index]);

            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
        }

        header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
        exit;
    }

    public function simpanDraft()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['peminjaman_draft'] = [
                'judul_kegiatan' => $_POST['judul_kegiatan'] ?? '',
                'tujuan_lain' => $_POST['tujuan_lain'] ?? '',
                'tujuan_ta' => $_POST['tujuan_ta'] ?? '',
                'tujuan_riset' => $_POST['tujuan_riset'] ?? '',
                'dosen_pembimbing' => $_POST['dosen_pembimbing'] ?? '',
                'tanggal_pengajuan' => $_POST['tanggal_pengajuan'] ?? '',
                'tanggal_peminjaman' => $_POST['tanggal_peminjaman'] ?? '',
                'tanggal_pengembalian' => $_POST['tanggal_pengembalian'] ?? '',
                'keterangan_peminjaman' => $_POST['keterangan_peminjaman'] ?? ''
            ];
        }
        header('Location: ' . BASEURL . 'Peminjaman');
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
                'jumlah' => $item['jumlah'],
                'keterangan' => $item['id_spesifikasi'] ?? ''
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

        // --- VALIDASI DATA ---
        $this->cekValidasiTanggal($_POST);
        $this->cekValidasiStok($_POST);
        // ---------------------

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
            unset($_SESSION['peminjaman_draft']);

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
            $alasan = $_POST['alasan_penolakan'];

            if ($this->model('Peminjaman_model')->simpanTolakPengembalian($id_peminjaman, $alasan) > 0) {
                Flasher::setFlash('Berhasil', 'Pengembalian ditolak. Alasan tersimpan.', '', 'warning');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menyimpan penolakan.', '', 'danger');
            }

            header('Location: ' . BASEURL . 'ValidasiPeminjaman/detail/' . $id_peminjaman);
            exit;
        }
    }

    private function cekValidasiTanggal($postData)
    {
        $today = date('Y-m-d');
        if (isset($postData['tanggal_peminjaman']) && $postData['tanggal_peminjaman'] < $today) {
            Flasher::setFlash('Tanggal Tidak Valid', 'Tanggal mulai peminjaman tidak boleh tanggal kemarin.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
        if (isset($postData['tanggal_pengembalian']) && $postData['tanggal_pengembalian'] < $today) {
            Flasher::setFlash('Tanggal Tidak Valid', 'Tanggal pengembalian tidak boleh tanggal kemarin.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }
        if (isset($postData['tanggal_pengembalian']) && $postData['tanggal_pengembalian'] < $postData['tanggal_peminjaman']) {
            Flasher::setFlash('Tanggal Tidak Valid', 'Tanggal pengembalian tidak boleh sebelum tanggal mulai.', '', 'danger');
            header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
            exit;
        }

        // Validasi Maksimal 2 Bulan
        if (isset($postData['tanggal_peminjaman']) && isset($postData['tanggal_pengembalian'])) {
            $tglStart = new DateTime($postData['tanggal_peminjaman']);
            $tglEnd = new DateTime($postData['tanggal_pengembalian']);
            $interval = $tglStart->diff($tglEnd);

            // Cek jika lebih dari 2 bulan atau (1 bulan + sisa hari yang membuat total > ~60 hari)
            // PHP DateTime diff->m (months) and diff->y (years) helps
            if ($interval->y > 0 || $interval->m > 2 || ($interval->m == 2 && $interval->d > 0)) {
                Flasher::setFlash('Batas Waktu Terlampaui', 'Durasi peminjaman maksimal adalah 2 bulan.', '', 'danger');
                header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
                exit;
            }
        }
    }

    private function cekValidasiStok($postData)
    {
        if (isset($postData['unit_selected']) && is_array($postData['unit_selected'])) {
            foreach ($postData['unit_selected'] as $index => $id_spesifikasi) {

                // Skip jika "Lainnya" atau kosong
                if ($id_spesifikasi == 'Lainnya' || empty($id_spesifikasi)) {
                    continue;
                }

                $jumlah_pinjam = (int) $postData['jumlah_peminjaman'][$index];

                // Ambil Stok Tersedia (Baik & Bisa Dipinjam)
                $stok_tersedia = $this->model('Peminjaman_model')->getStokTersediaBySpesifikasi($id_spesifikasi);

                if ($jumlah_pinjam > $stok_tersedia) {

                    $infoBarang = $this->model('Peminjaman_model')->getNamaBarangBySpesifikasi($id_spesifikasi);
                    $namaLengkap = ($infoBarang) ? $infoBarang['sub_barang'] . ' (' . $infoBarang['spesifikasi_barang'] . ')' : 'Barang Terpilih';

                    Flasher::setFlash(
                        'Stok Tidak Cukup!',
                        "Barang <b>$namaLengkap</b> hanya tersisa <b>$stok_tersedia</b> unit yang kondisinya BAIK. Anda meminta <b>$jumlah_pinjam</b>.",
                        '', // <--- Tambahkan string kosong ini agar jumlah argumen jadi 4
                        'warning'
                    );

                    header('Location: ' . BASEURL . 'Peminjaman/formPeminjaman');
                    exit; // Stop proses dan kembali ke form
                }
            }
        }
        return false; // Lolos validasi (tidak ada error)
    }

    public function batalEdit()
    {
        if (!isset($_SESSION))
            session_start();

        // Hapus semua session terkait edit dan keranjang sementara
        unset($_SESSION['keranjang']);
        unset($_SESSION['edit_mode']);
        unset($_SESSION['edit_id_peminjaman']);
        unset($_SESSION['edit_header']);
        unset($_SESSION['edit_details_map']);

        // Redirect ke halaman Riwayat atau Peminjaman
        header('Location: ' . BASEURL . 'Riwayat');
        exit;
    }

    public function batal()
    {
        if (!isset($_SESSION))
            session_start();
        unset($_SESSION['keranjang']);
        header('Location: ' . BASEURL . 'Peminjaman');
        exit;
    }
}
