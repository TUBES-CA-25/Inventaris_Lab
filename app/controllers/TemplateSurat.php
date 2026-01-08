<?php

// Pastikan autoload composer sudah di-load di index.php utama, 
// atau load di sini jika belum.
// require_once '../vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

class TemplateSurat extends Controller
{
    private $templateModel;
    private $peminjamanModel;

    public function __construct()
    {
        // Cek Session
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

    public function lengkapi($id_peminjaman) {
        $data['judul'] = 'Pelengkapan Berkas'; 
        $data['id_peminjaman'] = $id_peminjaman;
        
        // Ambil Data Header
        $data['peminjaman'] = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman);

        // Ambil Detail Barang
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

    // --- FUNGSI UTAMA GENERATE PDF ---
    public function generatePDF($id_peminjaman) {
        
        // 1. Ambil Data Header Transaksi
        $peminjaman = $this->peminjamanModel->getDetailPeminjaman($id_peminjaman);
        
        // 2. Ambil Data Detail Barang (Array untuk diloop)
        $details = $this->peminjamanModel->getDetailBarangByPeminjamanId($id_peminjaman);
        
        // Validasi Kepemilikan (Opsional tapi disarankan)
        if (!$peminjaman) {
             echo "Data tidak ditemukan."; exit;
        }

        // 3. Ambil Data User (Profile)
        $id_user = $_SESSION['id_user']; 
        $user = $this->peminjamanModel->getUserProfile($id_user);

        // 4. Siapkan Gambar KOP SURAT (Base64 agar terbaca oleh DomPDF)
        $pathKop = '../public/img/kop_surat.png'; 
        $gambar_kop = '';

        if (file_exists($pathKop)) {
            $type = pathinfo($pathKop, PATHINFO_EXTENSION);
            $dataImg = file_get_contents($pathKop);
            $gambar_kop = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
        }

        // 5. Load View HTML ke dalam Buffer
        ob_start();
        // Variabel $peminjaman, $user, $details, $gambar_kop akan dikirim ke file ini
        require_once '../app/views/peminjaman/surat_pdf.php'; 
        $htmlContent = ob_get_clean();

        // 6. Konfigurasi DomPDF
        // Pastikan path autoload benar
        // require_once '../vendor/autoload.php'; 
        
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Penting untuk gambar
        $options->set('defaultFont', 'Times-Roman'); 
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        
        // Render PDF
        $dompdf->render();

        // Bersihkan output buffer sebelumnya agar file tidak korup
        if (ob_get_length()) { ob_end_clean(); }

        // 7. Download File
        $filename = 'Surat_Peminjaman_' . $id_peminjaman . '.pdf';
        $dompdf->stream($filename, ["Attachment" => 0]); // 0 = Preview di browser, 1 = Download
        exit;
    }

    public function prosesUpload()
    {
        if (isset($_POST['submit_upload'])) {
            $id_peminjaman = $_POST['id_peminjaman'];

            // Cek File
            $file = $_FILES['file_surat'];
            $ekstensiValid = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
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

            // Upload File
            $namaBaru = 'SIGNED_' . uniqid() . '.' . $ext;
            $tujuan = '../public/files/surat-peminjaman/';

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0777, true);
            }

            if(move_uploaded_file($tmpName, $tujuan . $namaBaru)) {
                // Update Database via Model
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