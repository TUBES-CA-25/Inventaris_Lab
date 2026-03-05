<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class TemplateSurat extends Controller
{
    private $templateModel;
    private $peminjamanModel;

    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        $this->templateModel = $this->model('Template_model');
        $this->peminjamanModel = $this->model('Peminjaman_model');
    }

    public function index()
    {
        $data['judul'] = 'Daftar Template Surat';
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model('User_model')->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/lengkapi', $data);
        $this->view('templates/footer');
    }

    public function lengkapi($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            header('Location: ' . BASEURL . 'TemplateSurat');
            exit;
        }
        $data['judul'] = 'Pelengkapan Berkas';
        $data['id_peminjaman'] = $id_peminjaman;

        $data['detail_barang'] = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman);
        $data['peminjaman'] = $this->model('ValidasiPeminjaman_model')->getDetailValidasiDataPeminjaman($id_peminjaman);
        $data['details'] = $this->peminjamanModel->getDetailBarangByPeminjamanId($id_peminjaman);

        if (!$data['peminjaman']) {
            Flasher::setFlash('Data', 'tidak ditemukan', '', 'danger');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model('User_model')->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Peminjaman/lengkapi', $data);
        $this->view('templates/footer');
    }

    public function generatePDF($id_peminjaman)
    {
        $id_peminjaman = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman) {
            echo "ID tidak valid.";
            exit;
        }

        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman);
        $details = $this->peminjamanModel->getDetailBarangByPeminjamanId($id_peminjaman);
        // $data['barang'] = $this->model('Peminjaman_model')->getDetailBarangByPeminjamanId($id_peminjaman);

        if (!$peminjaman) {
            echo "Data tidak ditemukan.";
            exit;
        }

        // --- SECURITY CHECK: Restricted user can only access their own ---
        $id_user_login = $_SESSION['id_user'];
        $id_role_login = $_SESSION['id_role']; // Assuming this is set in session

        if (($id_role_login == 4 || $id_role_login == 6 || $id_role_login == 7) && $peminjaman['id_user'] != $id_user_login) {
            echo "Akses Ditolak: Anda tidak memiliki hak akses untuk dokumen ini.";
            exit;
        }

        $id_user = $peminjaman['id_user'];
        $user = $this->peminjamanModel->getUserProfile($id_user);

        // Fetch Supervisor Profile (for NIDN/NIP)
        $supervisor = null;
        if (!empty($peminjaman['dosen_pembimbing'])) {
            $supervisor = $this->model('User_model')->getUserByName($peminjaman['dosen_pembimbing']);
        }

        $pathKop = '../public/img/kop_surat.png';
        $gambar_kop = '';

        if (file_exists($pathKop)) {
            $type = pathinfo($pathKop, PATHINFO_EXTENSION);
            $dataImg = file_get_contents($pathKop);
            $gambar_kop = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
        }

        ob_start();
        // Cek Role untuk memilih Template
        if ($user['id_role'] == '6') {
            require_once '../app/views/Peminjaman/suratPdfMHS.php';
        } else {
            require_once '../app/views/peminjaman/surat_pdf.php';
        }
        $htmlContent = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times-Roman');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('legal', 'portrait');

        $dompdf->render();

        if (ob_get_length()) {
            ob_end_clean();
        }

        $filename = 'Surat_Peminjaman_' . '.pdf';
        $dompdf->stream($filename, ["Attachment" => 1]);
        exit;
    }

    public function prosesUpload()
    {
        if (isset($_POST['submit_upload'])) {
            $id_peminjaman = IdObfuscator::decode($_POST['id_peminjaman']);

            $file = $_FILES['file_surat'];
            $ekstensiValid = ['pdf'];
            $namaFile = $file['name'];
            $tmpName = $file['tmp_name'];
            $error = $file['error'];

            $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

            if ($error === 4) {
                Flasher::setFlash('Gagal', 'Pilih file terlebih dahulu', '', 'danger');
                header('Location: ' . BASEURL . 'TemplateSurat/lengkapi/' . $id_peminjaman);
                exit;
            }

            if (!in_array($ext, $ekstensiValid)) {
                Flasher::setFlash('Gagal', 'Format file harus PDF, Word, atau Gambar', '', 'danger');
                header('Location: ' . BASEURL . 'TemplateSurat/lengkapi/' . $id_peminjaman);
                exit;
            }

            $namaBaru = 'SIGNED_' . uniqid() . '.' . $ext;
            $tujuan = '../public/files/surat-peminjaman/';

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0777, true);
            }

            if (move_uploaded_file($tmpName, $tujuan . $namaBaru)) {
                if ($this->peminjamanModel->updateSuratPeminjaman($id_peminjaman, $namaBaru) > 0) {
                    Flasher::setFlash('Berhasil', 'Surat berhasil diupload. Menunggu verifikasi.', '', 'success');
                    header('Location: ' . BASEURL . 'Riwayat');
                } else {
                    Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem saat menyimpan data', '', 'danger');
                    header('Location: ' . BASEURL . 'TemplateSurat/lengkapi/' . $id_peminjaman);
                }
            } else {
                Flasher::setFlash('Gagal', 'Gagal memindahkan file upload', '', 'danger');
                header('Location: ' . BASEURL . 'TemplateSurat/lengkapi/' . $id_peminjaman);
            }
            exit;
        }
    }
}