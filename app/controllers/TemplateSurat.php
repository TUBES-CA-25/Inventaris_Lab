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

    public function tandaTangan($id_peminjaman)
    {
        $id_peminjaman_dec = IdObfuscator::decode($id_peminjaman);
        if (!$id_peminjaman_dec) {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman_dec);
        $user = $this->peminjamanModel->getUserProfile($_SESSION['id_user']);

        if (!$peminjaman || $peminjaman['id_user'] != $_SESSION['id_user']) {
            Flasher::setFlash('Akses Ditolak', 'Data tidak ditemukan atau bukan milik Anda', '', 'danger');
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        if (empty($user['file_ttd'])) {
            Flasher::setFlash('Tanda Tangan Belum Ada', 'Silakan upload tanda tangan di profil Anda terlebih dahulu.', '', 'warning');
            header('Location: ' . BASEURL . 'Profil');
            exit;
        }

        if (empty($peminjaman['file_surat']) || !file_exists(__DIR__ . '/../../public/files/surat-peminjaman/' . $peminjaman['file_surat'])) {
            $this->generateUnsignedPDF($id_peminjaman_dec);
            $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman_dec);
        }

        $data['id_peminjaman'] = $id_peminjaman_dec;
        $data['file_surat'] = $peminjaman['file_surat'];
        $data['user'] = $user;

        $this->view('Peminjaman/TandaTanganMHS', $data);
    }

    private function generateUnsignedPDF($id)
    {
        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id);
        $details = $this->peminjamanModel->getDetailBarangByPeminjamanId($id);
        $id_user = $peminjaman['id_user'];
        $user = $this->peminjamanModel->getUserProfile($id_user);

        $supervisor = null;
        if (!empty($peminjaman['dosen_pembimbing'])) {
            $supervisor = $this->model('User_model')->getUserByName($peminjaman['dosen_pembimbing']);
        }

        $pathKop = __DIR__ . '/../../public/img/kop_surat.png';
        $gambar_kop = '';
        if (file_exists($pathKop)) {
            $type = pathinfo($pathKop, PATHINFO_EXTENSION);
            $dataImg = file_get_contents($pathKop);
            $gambar_kop = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
        }

        ob_start();
        if ($peminjaman['id_jenis_peminjaman'] == 1) {
            require_once '../app/views/Peminjaman/suratPdfMHS.php';
        } else {
            require_once '../app/views/Peminjaman/surat_pdf.php';
        }
        $htmlContent = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('legal', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        $namaFile = 'UNSIGNED_' . uniqid() . '.pdf';
        $tujuan = __DIR__ . '/../../public/files/surat-peminjaman/';
        if (!file_exists($tujuan))
            mkdir($tujuan, 0777, true);

        file_put_contents($tujuan . $namaFile, $pdfOutput);
        $this->peminjamanModel->updateSuratPeminjaman($id, $namaFile);
    }

    public function prosesSignature()
    {
        $id_peminjaman = IdObfuscator::decode($_POST['id_peminjaman']);
        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman);
        $user = $this->peminjamanModel->getUserProfile($_SESSION['id_user']);

        $mhs_page = $_POST['mhs_page'];
        $mhs_x = $_POST['mhs_x'];
        $mhs_y = $_POST['mhs_y'];
        $mhs_w = $_POST['mhs_w'];
        $mhs_h = $_POST['mhs_h'];

        $sourceFile = __DIR__ . '/../../public/files/surat-peminjaman/' . $peminjaman['file_surat'];
        $ttdPath = __DIR__ . '/../../public/img/ttd/' . $user['file_ttd'];

        // Use a PREVIEW prefix for temporary viewing
        $outputName = 'PREVIEW_MHS_' . uniqid() . '.pdf';
        $outputPath = __DIR__ . '/../../public/files/surat-peminjaman/' . $outputName;

        $pathAutoload = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($pathAutoload)) {
            require_once $pathAutoload;
        } else {
            require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';
            require_once __DIR__ . '/../vendor/setasign/fpdi/src/autoload.php';
        }

        $pdf = new \setasign\Fpdi\Fpdi();
        $pageCount = $pdf->setSourceFile($sourceFile);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplatesize($tplIdx);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            if ($i == $mhs_page) {
                $paperW = $size['width'];
                $paperH = $size['height'];
                $fx = $mhs_x * $paperW;
                $fy = $mhs_y * $paperH;
                $fw = $mhs_w * $paperW;
                $fh = $mhs_h * $paperH;

                if (file_exists($ttdPath)) {
                    $pdf->Image($ttdPath, $fx, $fy, $fw, $fh);
                }
            }
        }

        $pdf->Output('F', $outputPath);

        // Redirect to preview step instead of finalizing
        header('Location: ' . BASEURL . 'TemplateSurat/preview/' . IdObfuscator::encode($id_peminjaman) . '/' . $outputName);
        exit;
    }

    public function preview($id_peminjaman, $file_preview)
    {
        $id_dec = IdObfuscator::decode($id_peminjaman);
        if (!$id_dec) {
            header('Location: ' . BASEURL . 'Riwayat');
            exit;
        }

        $data['id_peminjaman'] = $id_dec;
        $data['file_preview'] = $file_preview;

        $this->view('Peminjaman/PreviewTandaTangan', $data);
    }

    public function kumpulkan($id_peminjaman)
    {
        $id_dec = IdObfuscator::decode($id_peminjaman);
        $file_final = $_POST['file_final'];

        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_dec);
        $oldFile = $peminjaman['file_surat'];

        // Finalize filename: Rename PREVIEW to SIGNED
        $newName = str_replace('PREVIEW_', 'SIGNED_', $file_final);

        $oldPath = __DIR__ . '/../../public/files/surat-peminjaman/' . $file_final;
        $newPath = __DIR__ . '/../../public/files/surat-peminjaman/' . $newName;

        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);
        }

        // Delete the original unsigned file
        $sourcePath = __DIR__ . '/../../public/files/surat-peminjaman/' . $oldFile;
        if (file_exists($sourcePath)) {
            unlink($sourcePath);
        }

        if ($this->peminjamanModel->updateSuratPeminjaman($id_dec, $newName) > 0) {
            Flasher::setFlash('Berhasil', 'Peminjaman berhasil ditandatangani dan dikumpulkan.', '', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem.', '', 'danger');
        }

        header('Location: ' . BASEURL . 'Riwayat');
        exit;
    }

    public function batal($id_peminjaman)
    {
        // Discard result and return to positioning
        header('Location: ' . BASEURL . 'TemplateSurat/tandaTangan/' . $id_peminjaman);
        exit;
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
        // Route PDF based on Category
        if ($peminjaman['id_jenis_peminjaman'] == 1) {
            require_once '../app/views/Peminjaman/suratPdfMHS.php';
        } else {
            require_once '../app/views/Peminjaman/surat_pdf.php';
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