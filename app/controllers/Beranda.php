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
        $stats = $berandaModel->getAllCounts();

        $data['jumlah_jenis_barang'] = $stats['jml_jenis'];
        $data['jumlah_peminjaman'] = $stats['jml_peminjaman'];
        $data['jumlah_merek_barang'] = $stats['jml_merek'];
        $data['jumlah_detail_barang'] = $stats['jml_barang'];
        $data['jumlah_pengembalian'] = $stats['jml_pengembalian'];

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Beranda/index', $data);
        $this->view('templates/footer');
    }

    public function getAjaxStats()
    {
        $json = file_get_contents('php://input');
        $input = json_decode($json, true);

        $mode = $input['mode'] ?? 'bulanan';
        $tahun = $input['tahun'] ?? date('Y');
        $bulan = $input['bulan'] ?? date('m');

        $data = $this->model('Beranda_model')->getChartDataFiltered($mode, $tahun, $bulan);

        echo json_encode($data);
    }
}