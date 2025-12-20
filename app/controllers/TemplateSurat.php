<?php

class TemplateSurat extends Controller {

    private $templateModel;

    public function __construct() {
        // Cek Login
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASEURL . 'Login');
            exit;
        }
        $this->templateModel = $this->model('Template_model');
    }

    public function index() {
        $data['judul'] = 'Template Surat';
        $data['templates'] = $this->templateModel->getAllTemplate();
        
        $data['id_user'] = $_SESSION['id_user'];
        $data['profile'] = $this->model("User_model")->profile($data);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('Template_surat/index', $data);
        $this->view('templates/footer');
    }

    public function upload() {
        // Proses Upload
        $status = $this->templateModel->uploadTemplate($_POST);

        if ($status > 0) {
            Flasher::setFlash('Template', 'berhasil', 'diupload', 'success');
        } elseif ($status == -1) {
            Flasher::setFlash('Gagal', 'Format file harus .docx atau .doc', '', 'danger');
        } else {
            Flasher::setFlash('Template', 'gagal', 'diupload', 'danger');
        }

        header('Location: ' . BASEURL . 'TemplateSurat');
        exit;
    }

    public function hapus($id) {
        if ($this->templateModel->hapusTemplate($id) > 0) {
            Flasher::setFlash('Template', 'berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Template', 'gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . 'TemplateSurat');
        exit;
    }

    /**
     * Fitur Download File
     * User akan mendownload file asli yang diupload admin.
     */
    public function download($id) {
        $template = $this->templateModel->getTemplateById($id);

        if (!$template) {
            Flasher::setFlash('Error', 'Data template tidak ditemukan.', '', 'danger');
            header('Location: ' . BASEURL . 'TemplateSurat');
            exit;
        }

        $filePath = '../public/files/template-surat/' . $template['file_template'];

        if (file_exists($filePath)) {
            // Header untuk memaksa browser mendownload file (Bukan membukanya)
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($template['nama_template'] . '.docx') . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            
            // Bersihkan output buffer agar file tidak corrupt
            ob_clean();
            flush();
            readfile($filePath);
            exit;
        } else {
            Flasher::setFlash('Error', 'File fisik hilang dari server.', '', 'danger');
            header('Location: ' . BASEURL . 'TemplateSurat');
            exit;
        }
    }
}