<?php

/**
 * Model Jenis_barang_model
 * * Menangani interaksi database untuk tabel 'mst_jenis_barang'.
 */
class Jenis_barang_model { 

    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    /**
     * Mengambil seluruh data jenis barang.
     * Diurutkan berdasarkan nama sub_barang (A-Z).
     */
    public function getDataJenisBarang() {
        $this->db->query("SELECT * FROM mst_jenis_barang ORDER BY sub_barang ASC"); 
        return $this->db->resultSet();
    }

    /**
     * Menambah data baru ke database.
     * Otomatis menggabungkan grup_sub dan kode_sub menjadi kode_jenis_barang.
     */
    public function postDataJenisBarang($data)
    {
        $query = "INSERT INTO mst_jenis_barang (sub_barang, grup_sub, kode_sub, kode_jenis_barang) 
                  VALUES (:sub_barang, :grup_sub, :kode_sub, :kode_jenis_barang)";
        
        $this->db->query($query);
        $this->db->bind('sub_barang', $data['sub_barang']);
        $this->db->bind('grup_sub', $data['grup_sub']);
        $this->db->bind('kode_sub', $data['kode_sub']);
        
        // Logic: kode_jenis_barang = Gabungan "Grup / Kode Sub" (Contoh: LPT/01)
        $this->db->bind('kode_jenis_barang', $data['grup_sub'] . '/' . $data['kode_sub']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Mengupdate data yang sudah ada.
     * Kode jenis barang juga ikut diperbarui jika komponennya berubah.
     */
    public function ubahJenisBarang($data)
    {
        $query = "UPDATE mst_jenis_barang SET 
                    sub_barang = :sub_barang, 
                    grup_sub= :grup_sub, 
                    kode_sub=:kode_sub, 
                    kode_jenis_barang = :kode_jenis_barang 
                  WHERE id_jenis_barang = :id_jenis_barang";
        
        $this->db->query($query);
        $this->db->bind('sub_barang', $data['sub_barang']);
        $this->db->bind('grup_sub', $data['grup_sub']);
        $this->db->bind('kode_sub', $data['kode_sub']);
        // Update kode gabungan
        $this->db->bind('kode_jenis_barang', $data['grup_sub'] . '/' . $data['kode_sub']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Menghapus data jenis barang dengan aman.
     * Menggunakan Try-Catch untuk menangani Foreign Key Constraint.
     * * @return int 
     * > 0 : Berhasil hapus
     * 0   : Gagal hapus (General)
     * -1  : Gagal hapus karena data sedang dipakai (Foreign Key Violation)
     */
    public function hapusJenisBarang($id_jenis_barang){
        try {
            $this->db->query("DELETE FROM mst_jenis_barang WHERE id_jenis_barang = :id_jenis_barang");
            $this->db->bind("id_jenis_barang", $id_jenis_barang);
            $this->db->execute();
            
            return $this->db->rowCount(); 

        } catch (PDOException $e) {
            // Menangkap error MySQL kode '23000' atau '1451' (Integrity constraint violation)
            // Ini terjadi jika kita menghapus Jenis Barang yang sudah dipakai di Tabel Barang
            if ($e->getCode() == '23000' || isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return -1; // Return kode khusus ke Controller
            }
            return 0; // Gagal umum
        }
    }

    /**
     * Cek apakah ada data kembar (Duplikasi).
     * Mengecek Nama Barang ATAU Kode Sub.
     * PENTING: Mengecualikan ID sendiri agar aman saat proses Edit.
     */
    public function cekDataJenisBarang($data){
        $query = "SELECT COUNT(*) FROM mst_jenis_barang WHERE 
                  (sub_barang = :sub_barang OR kode_sub = :kode_sub) 
                  AND id_jenis_barang != :id_jenis_barang";
    
        $this->db->query($query);
        $this->db->bind('sub_barang', $data['sub_barang']);
        $this->db->bind('kode_sub', $data['kode_sub']);
        
        // Ternary Operator:
        // Jika ID kosong (saat Tambah Data), set jadi string kosong agar query tetap valid.
        // Jika ID ada (saat Ubah Data), gunakan ID tersebut.
        $id = !empty($data['id_jenis_barang']) ? $data['id_jenis_barang'] : '';
        $this->db->bind('id_jenis_barang', $id);
        
        $this->db->execute();
        return $this->db->single()['COUNT(*)'];
    }

    public function getUbah($id_jenis_barang) {
        $this->db->query("SELECT * FROM mst_jenis_barang WHERE id_jenis_barang = :id_jenis_barang");
        $this->db->bind("id_jenis_barang", $id_jenis_barang);
        return $this->db->single();
    }

    public function cariDataJenisBarang(){
        $keyword = $_POST['keyword'];
        $query= "SELECT * FROM mst_jenis_barang WHERE 
                 sub_barang LIKE :keyword OR 
                 grup_sub LIKE :keyword OR 
                 kode_sub LIKE :keyword OR 
                 kode_jenis_barang LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }
}