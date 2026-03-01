<?php

class Beranda extends Controller
{

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Beranda';
        $berandaModel = $this->model('Beranda_model');

        $data['id_user'] = $_SESSION['id_user'];
        $data['id_role'] = $_SESSION['id_role'];
        $data['profile'] = $this->model("User_model")->profile($data);

        if ($data['id_role'] == 1) {
            // Data khusus Kepala Lab
            $stats = $berandaModel->getAllCounts();
            $data['total_items'] = $stats['jml_barang'];
            $data['pending_loans_count'] = $stats['jml_peminjaman_proses'];
            $data['weekly_loans_count'] = $stats['jml_peminjaman_minggu'];
            $data['pending_loans'] = $berandaModel->getPeminjamanPending(3); // Fetch top 3 pending

            $viewContent = 'Beranda/KepalaLab';
        } elseif ($data['id_role'] == 6) {
            // Data khusus Mahasiswa
            $studentStats = $berandaModel->getStudentStats($data['id_user']);
            $data['peminjaman_berlangsung'] = $studentStats['ongoing'] ?? 0;
            $data['melewati_batas'] = $studentStats['overdue'] ?? 0;
            $data['recent_loans'] = $berandaModel->getRecentLoans($data['id_user']);

            $viewContent = 'Beranda/mahasiswa';
        } elseif ($data['id_role'] == 3 || $data['id_role'] == 4) {
            // Data khusus Asisten / Korlab
            $assistantStats = $berandaModel->getAssistantStats();
            $data['peminjaman_berlangsung'] = $assistantStats['ongoing'] ?? 0;
            $data['melewati_batas'] = $assistantStats['overdue'] ?? 0;
            $data['damaged_goods'] = $berandaModel->getDamagedGoodsPaged(5, 0);
            $data['total_damaged'] = $berandaModel->getTotalDamagedGoodsCount();

            // Perbedaan Role 3 & 4
            if ($data['id_role'] == 3) {
                // Korlab: Lihat semua activity log asisten & data inventaris
                $data['activity_logs'] = $berandaModel->getAllAssistantActivityLog(10);

                $inventoryStats = $berandaModel->getAllCounts();
                $data['total_items'] = $inventoryStats['jml_barang'];
                $data['total_brands'] = $inventoryStats['jml_merek'];
            } else {
                // Asisten: Lihat activity log sendiri
                $data['activity_logs'] = $berandaModel->getAssistantActivityLog($data['id_user'], 5);
            }

            $viewContent = 'Beranda/Asisten';
        } elseif ($data['id_role'] == 2) {
            // Data khusus Laboran
            $stats = $berandaModel->getAllCounts();
            $data['total_barang'] = $stats['jml_barang'];
            $data['total_peminjaman'] = $stats['jml_peminjaman'];
            $data['total_pengembalian'] = $stats['jml_pengembalian'];
            $data['total_jenis'] = $stats['jml_jenis'];
            $data['total_brands'] = $stats['jml_merek'];
            $data['total_damaged'] = $berandaModel->getTotalDamagedGoodsCount();

            // Tables for Laboran
            $data['pending_loans'] = $berandaModel->getPeminjamanPending(5);
            $data['verified_returns'] = $berandaModel->getPengembalianVerified(5);

            $viewContent = 'Beranda/Laboran';
        } else {
            // Data untuk Admin/Petugas
            $stats = $berandaModel->getAllCounts();
            $data['jumlah_jenis_barang'] = $stats['jml_jenis'];
            $data['jumlah_peminjaman'] = $stats['jml_peminjaman'];
            $data['jumlah_merek_barang'] = $stats['jml_merek'];
            $data['jumlah_detail_barang'] = $stats['jml_barang'];
            $data['jumlah_pengembalian'] = $stats['jml_pengembalian'];
            $data['total_damaged'] = $berandaModel->getTotalDamagedGoodsCount();

            $viewContent = 'Beranda/index';
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view($viewContent, $data);
        $this->view('templates/footer');
    }

    public function getAjaxStats()
    {
        $json = file_get_contents('php://input');
        $input = json_decode($json, true);

        $mode = $input['mode'] ?? 'bulanan';
        $tahun = $input['tahun'] ?? date('Y');
        $bulan = $input['bulan'] ?? date('m');
        $id_user = $_SESSION['id_user'];
        $id_role = $_SESSION['id_role'];

        if ($id_role == 6) {
            $data = $this->model('Beranda_model')->getStudentChartData($id_user, $mode, $tahun, $bulan);
        } else {
            $data = $this->model('Beranda_model')->getChartDataFiltered($mode, $tahun, $bulan);
        }

        echo json_encode($data);
    }

    public function getDamagedGoodsAjax()
    {
        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $berandaModel = $this->model('Beranda_model');
        $data['damaged_goods'] = $berandaModel->getDamagedGoodsPaged($limit, $offset);
        $data['total_damaged'] = $berandaModel->getTotalDamagedGoodsCount();
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($data['total_damaged'] / $limit);

        echo json_encode($data);
    }
}