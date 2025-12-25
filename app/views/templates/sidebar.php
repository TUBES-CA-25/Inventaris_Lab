<div class="body-beranda">
    <div class="side-bar">
        <div class="profil">
            <div class="logo">
                <img src="<?= BASEURL; ?>img/logo bg hitam.svg" alt="logo" />
            </div>
            <div class="data-profil">
                <?php
                $foto_profil = $data['profile']['foto'];
                if ($foto_profil == "../public/img/foto-profile/") {
                    echo '<img src="' . BASEURL . $foto_profil . '/user.svg' . '" alt="profile" style="border-radius: 50%; height: 100px; width: 100px; object-fit:cover;">';
                } else {
                    echo '<img src="' . BASEURL . $foto_profil . '" alt="profile" style="border-radius: 50%; height: 100px; width: 100px; object-fit:cover;">';
                }

                echo '<div class="detail-data-profil">';
                if (isset($data['profile']['nama_user']) && isset($data['profile']['role'])) {
                    $nama = $data['profile']['nama_user'];
                    $role = $data['profile']['role'];
                    // Class CSS baru diterapkan di sini
                    echo '<p class="profile-name" style = "color: white;">' . $nama . '</p>
                          <p class="profile-role" style = "color: white;">' . $role . '</p></div>';
                }
                ?>
            </div>
        </div>
        <hr />
        <div class="menu">
            <ul>
                <li class="beranda">
                    <button onclick="location.href='<?= BASEURL; ?>Beranda'">
                        <i class="fa-solid fa-house"></i>
                        Beranda
                    </button>
                </li>
                <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="semua-barang">
                        <button onclick="location.href=\'' . BASEURL . 'DetailBarang\'">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            Detail Barang
                        </button>
                    </li>';
                }
                ?>
                <!-- <?php
                if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
                    echo '<li class="tambah-jenis-barang">
                        <button onclick="location.href=\'' . BASEURL . 'JenisBarang\'">
                            <i class="fa-solid fa-box"></i>
                            Jenis Barang
                        </button>
                    </li>';
                }
                ?>
                <?php
                if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
                    echo '<li class="tambah-merek-barang">
                        <button onclick="location.href=\'' . BASEURL . 'merekBarang\'">
                            <i class="fa-solid fa-barcode"></i>
                            Merek Barang
                        </button>
                    </li>';
                }
                ?> -->
                <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="tambah-peminjaman-barang">
                        <button onclick="location.href=\'' . BASEURL . 'peminjaman\'">
                            <i class="fa-solid fa-receipt"></i>
                            Peminjaman
                        </button>
                    </li>';
                }
                ?>
                <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="tambah-pengembalian-barang">
                        <button onclick="location.href=\'' . BASEURL . 'pengembalian\'">
                            <i class="fa-solid fa-rotate-left"></i>
                            Pengembalian
                        </button>
                    </li>';
                }
                ?>
                <?php
                if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
                    echo '<li class="tambah-merek-barang">
                        <button onclick="location.href=\'' . BASEURL . 'merekBarang\'">
                            <i class="fa-solid fa-barcode"></i>
                            Validasi Peminjaman
                        </button>
                    </li>';
                }
                ?>
                <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="riwayat menu-item-riwayat">
                        <div class="btn-group dropright">
                            <button type="button" class="btn dropdown-toggle btn-riwayat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item riwayat-dropdown-item first" onclick="location.href=\'' . BASEURL . 'peminjaman\'" type="button">
                                    <i class="fa-solid fa-receipt"></i> Peminjaman
                                </button>
                                <button class="dropdown-item riwayat-dropdown-item last" onclick="location.href=\'' . BASEURL . 'pengembalian\'" type="button">
                                    <i class="fa-solid fa-rotate-left"></i> Pengembalian
                                </button>
                            </div>
                        </div>
                    </li>';
                }
                ?>
                <?php
                if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
                    echo '<li class="kelola-akun">
                        <button onclick="location.href=\'' . BASEURL . 'KelolaAkun\'">
                            <i class="fa-solid fa-users-gear"></i>
                            Kelola Akun
                        </button>
                    </li>';
                }
                ?>
                <?php
                if (!isset($_SESSION['login'])) {
                    echo '<li class="login">
                        <button onclick="location.href=\'' . BASEURL . 'Login\'">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Login
                        </button>
                    </li>';
                    echo '<li class="register">
                        <button onclick="location.href=\'' . BASEURL . 'Register\'">
                            <i class="fa-solid fa-user-plus"></i>
                            Register
                        </button>
                    </li>';
                }
                ?>
                
                <li class="keluar menu-item-logout">
                    <div class="btn-group dropright">
                        <button type="button" class="btn dropdown-toggle btn-settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-gear"></i> Pengaturan
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item settings-dropdown-item first" onclick="location.href='<?= BASEURL ?>Profil'" type="button">
                                <i class="fa-regular fa-user"></i> Profil
                            </button>
                            <button class="dropdown-item settings-dropdown-item last" data-toggle="modal" data-target="#konfirmasiKeluar">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                Keluar
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>