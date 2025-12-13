<div class="body-beranda" >
    <div class="side-bar">
        <div class="profil">
            <div class="logo">
                <img src="<?=BASEURL;?>img/logo bg hitam.svg" alt="logo" />
            </div>
            <div class="data-profil">
                <?php
                    
                        $foto_profil = $data['profile']['foto'];
                        if($foto_profil == "../public/img/foto-profile/"){
                            echo '<img src="'. BASEURL . $foto_profil . '/user.svg'. '" alt="profile" style="border-radius: 50%; height: 100px; width: 100px; object-fit:cover;">';
                        }else{
                        echo '<img src="'. BASEURL . $foto_profil . '" alt="profile" style="border-radius: 50%; height: 100px; width: 100px; object-fit:cover;">';
                        }
                        
                echo '<div class="detail-data-profil">';
                if (isset($data['profile']['nama_user']) && isset($data['profile']['role'])) {
                $nama = $data['profile']['nama_user'];
                $role = $data['profile']['role'];
                echo '<p style="color: white; font-weight: 600; margin-bottom:0;">' . $nama . '</p>
                    <p style="color: white; font-size: 13px;">' . $role . '</p></div>';
            }
            
                    ?>

            </div>
        </div>
        <hr />
        <div class="menu">
        <ul>
    <li class="beranda">
        <button onclick="location.href='<?=BASEURL;?>Beranda'">
            <i class="fa-solid fa-house" style="color: #ffffff;"></i>
            Beranda
        </button>
    </li>
    <?php
        // Semua role login dapat mengakses semua barang
        if (isset($_SESSION['login'])) {
            echo '<li class="semua-barang">
                <button onclick="location.href=\''.BASEURL.'DetailBarang\'">
                    <i class="fa-solid fa-boxes-stacked" style="color: #ffffff"></i>
                    Detail Barang
                </button>
            </li>';
        }
    ?>
    <?php
        // Semua role login dapat mengakses jenis barang
        if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
            echo '<li class="tambah-jenis-barang">
                <button onclick="location.href=\''.BASEURL.'JenisBarang\'">
                    <i class="fa-solid fa-box" style="color: #ffffff"></i>
                    Jenis Barang
                </button>
            </li>';
        }
    ?>
    <?php
        // Semua role login dapat mengakses merek barang
        if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
            echo '<li class="tambah-merek-barang">
                <button onclick="location.href=\''.BASEURL.'merekBarang\'">
                    <i class="fa-solid fa-barcode" style="color: #ffffff;"></i>
                    Merek Barang
                </button>
            </li>';
        }
    ?>
    <?php
        // Semua role login dapat mengakses peminjaman
        if (isset($_SESSION['login'])) {
            echo '<li class="tambah-peminjaman-barang">
                <button onclick="location.href=\''.BASEURL.'peminjaman\'">
                    <i class="fa-solid fa-receipt" style="color: #ffffff;"></i>
                    Peminjaman
                </button>
            </li>';
        }
    ?>
    <?php
        // Semua role login dapat mengakses Pengembalian
        if (isset($_SESSION['login'])) {
            echo '<li class="tambah-pengembalian-barang">
                <button onclick="location.href=\''.BASEURL.'pengembalian\'">
                    <i class="fa-solid fa-rotate-left" style="color: #ffffff;"></i>
                    Pengembalian
                </button>
            </li>';
        }
    ?>

    <?php
        // Role 1 hingga 4 dapat mengakses Kelola Akun
        if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
            echo '<li class="kelola-akun">
                <button onclick="location.href=\''.BASEURL.'KelolaAkun\'">
                    <i class="fa-solid fa-users-gear" style="color: #ffffff;"></i>
                    Kelola Akun
                </button>
            </li>';
        }
    ?>
    <?php
        // Semua role dapat mengakses login dan register
        if (!isset($_SESSION['login'])) {
            echo '<li class="login">
                <button onclick="location.href=\''.BASEURL.'Login\'">
                    <i class="fa-solid fa-right-to-bracket" style="color: #ffffff;"></i>
                    Login
                </button>
            </li>';
            echo '<li class="register">
                <button onclick="location.href=\''.BASEURL.'Register\'">
                    <i class="fa-solid fa-user-plus" style="color: #ffffff;"></i>
                    Register
                </button>
            </li>';
        }
    ?>
    <li class="keluar" style="margin-top: 40px;">
        <div class="btn-group dropright">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" onmouseover="this.style.backgroundColor='#CAD6FF'"
                onmouseout="this.style.backgroundColor='transparent'">
                <i class="fa-solid fa-gear" style="color: #ffffff;"></i>Pengaturan </button>
            <div class="dropdown-menu dropdown-menu-right">
                <button class="dropdown-item" onclick="location.href='<?=BASEURL?>Profil'" type="button"
                    style="margin-top: 10px; color:black;">
                    <i class="fa-regular fa-user"></i>Profil</button>
                <button class="dropdown-item" data-toggle="modal" data-target="#konfirmasiKeluar"
                    style="color: black; margin-top: 0;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Keluar
                </button>
            </div>
        </div>
    </li>
</ul>

        </div>
    </div>