<?php

class Beranda_model{

    private $db;
    public function __construct(){
        $this->db = new Database;
    }

    public function getCount($table) {
        $this->db->query("SELECT COUNT(*) as total FROM " . $table);
        return $this->db->single()['total'];
    }

}