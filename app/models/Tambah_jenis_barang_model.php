<?php

class Tambah_jenis_barang_model{

    private $db;
    public function __construct(){
        $this->db = new Database;
    }

    public function postDataJenisBarang($data)
{

    // Insert data into mst_jenis_barang
    $queryJenisBarang = "INSERT INTO mst_jenis_barang (sub_barang, grup_sub, kode_sub, kode_jenis_barang) VALUES (:sub_barang, :grup_sub, :kode_sub, :kode_jenis_barang)";
    $this->db->query($queryJenisBarang);
    $this->db->bind('sub_barang', $data['sub_barang']);
    $this->db->bind('grup_sub', $data['grup_sub']);
    $this->db->bind('kode_sub', $data['kode_sub']);
    $kodeJenisBarang = $data['grup_sub'] . '/' . $data['kode_sub'];
    $this->db->bind('kode_jenis_barang', $kodeJenisBarang);
    $this->db->execute();
    

    return $this->db->rowCount();
}

public function getDataJenisBarang() {
    $tampilView = "SELECT * FROM mst_jenis_barang;";
    $this->db->query($tampilView);

    return $this->db->resultSet();

}

public function cekDataJenisBarang($data){
       // Insert data into mst_jenis_barang
        $queryCekJenisBarang = "SELECT COUNT(*) FROM mst_jenis_barang WHERE
        (sub_barang = :sub_barang OR
        kode_sub = :kode_sub) AND
        id_jenis_barang != :id_jenis_barang";
    
        $this->db->query($queryCekJenisBarang);
        $this->db->bind('sub_barang', $data['sub_barang']);
        $this->db->bind('kode_sub', $data['kode_sub']);
        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);
        $this->db->execute();
        
    
        return $this->db->single()['COUNT(*)'];
}

public function hapusJenisBarang($id_jenis_barang){
    $this->db->query("DELETE FROM mst_jenis_barang WHERE id_jenis_barang = :id_jenis_barang;");
    $this->db->bind("id_jenis_barang", $id_jenis_barang);

    $this->db->execute();


    return $this->db->resultSet();
    }

    public function getUbah($id_jenis_barang) {
        $tampilView = "SELECT * FROM mst_jenis_barang WHERE id_jenis_barang = :id_jenis_barang;";
        $this->db->query($tampilView);
        $this->db->bind("id_jenis_barang", $id_jenis_barang);

        return $this->db->single();
    
    }

    public function ubahJenisBarang($data)
    {
    
        // Insert data into mst_jenis_barang
        $queryJenisBarang = "UPDATE mst_jenis_barang SET sub_barang = :sub_barang, grup_sub= :grup_sub, kode_sub=:kode_sub, kode_jenis_barang = :kode_jenis_barang WHERE id_jenis_barang = :id_jenis_barang";
        $this->db->query($queryJenisBarang);
        $this->db->bind('sub_barang', $data['sub_barang']);
        $this->db->bind('grup_sub', $data['grup_sub']);
        $this->db->bind('kode_sub', $data['kode_sub']);
        $kodeJenisBarang = $data['grup_sub'] . '/' . $data['kode_sub'];
        $this->db->bind('kode_jenis_barang', $kodeJenisBarang);        $this->db->bind('id_jenis_barang', $data['id_jenis_barang']);

        $this->db->execute();
        
    
        return $this->db->rowCount();
    }


    public function cariDataJenisBarang(){
        $keyword = $_POST['keyword'];
        $query= "SELECT * FROM mst_jenis_barang WHERE sub_barang LIKE :keyword OR grup_sub LIKE :keyword OR kode_sub LIKE :keyword OR kode_jenis_barang LIKE :keyword";

        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();

    }
}