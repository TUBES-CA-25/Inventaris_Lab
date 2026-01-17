<?php

class Template_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllTemplate() {
        $this->db->query("SELECT * FROM mst_template_surat ORDER BY uploaded_at DESC");
        return $this->db->resultSet();
    }

    public function getTemplateById($id) {
        $this->db->query("SELECT * FROM mst_template_surat WHERE id_template = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function uploadTemplate($data) {
        $namaTemplate = $data['nama_template'];
        $jenisSurat = $data['jenis_surat'];
        $keterangan = $data['keterangan'];

        $file = $_FILES['file_template'];
        $namaFile = $file['name'];
        $error = $file['error'];
        $tmpName = $file['tmp_name'];

        if ($error === 4) {
            return 0; 
        }

        $ekstensiValid = ['docx', 'doc'];
        $ekstensiFile = explode('.', $namaFile);
        $ekstensiFile = strtolower(end($ekstensiFile));

        if (!in_array($ekstensiFile, $ekstensiValid)) {
            return -1; 
        }

        $namaFileBaru = uniqid() . '_' . $namaFile;
        
        $tujuan = '../public/files/template-surat/'; 
        if (!file_exists($tujuan)) {
            mkdir($tujuan, 0777, true);
        }

        move_uploaded_file($tmpName, $tujuan . $namaFileBaru);

        $query = "INSERT INTO mst_template_surat 
                  (nama_template, jenis_surat, file_template, keterangan) 
                  VALUES (:nama, :jenis, :file, :ket)";
        
        $this->db->query($query);
        $this->db->bind('nama', $namaTemplate);
        $this->db->bind('jenis', $jenisSurat);
        $this->db->bind('file', $namaFileBaru);
        $this->db->bind('ket', $keterangan);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusTemplate($id) {
        $template = $this->getTemplateById($id);
        
        if ($template) {
            $path = '../public/files/template-surat/' . $template['file_template'];
            if (file_exists($path)) {
                unlink($path); 
            }
        }

        $this->db->query("DELETE FROM mst_template_surat WHERE id_template = :id");
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }
}