<?php

class Flasher{
    public static function setFlash($objek, $pesan, $aksi, $type){
        $_SESSION['flash'] = [
            'objek' => $objek,
            'pesan' => $pesan,
            'aksi' => $aksi,
            'type' => $type
        ];

    }
    public static function flash(){
        if(isset($_SESSION['flash'])){
            echo'<div id="flasher" class="alert alert-'.$_SESSION['flash']['type'] . ' alert-dismissible fade show" role="alert">' . $_SESSION['flash']['objek'].'  <strong>'.$_SESSION['flash']['pesan'].'</strong>' .$_SESSION['flash']['aksi'] . '
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
         </button>
     </div>';

     unset($_SESSION['flash']);
        }
    }
}