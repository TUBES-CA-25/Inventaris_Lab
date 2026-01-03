<style>
    /* ================= CSS KHUSUS SIDEBAR MODERN ================= */

/* 1. Sidebar Container */
.side-bar {
  width: 20;  /* Lebar kita perbesar sedikit agar lega */
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
  background: linear-gradient(180deg, #0c1740 0%, #08102e 100%); /* Gradasi halus */
  overflow-y: auto;
  box-shadow: 5px 0 25px rgba(0,0,0,0.15); /* Bayangan kedalaman */
  color: white;
  transition: all 0.3s ease;
}

/* Scrollbar Custom untuk Sidebar */
.side-bar::-webkit-scrollbar { width: 6px; }
.side-bar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
.side-bar::-webkit-scrollbar-track { background: transparent; }


/* 2. Profil Section */
.profil {
  padding: 30px 20px 20px 20px;
  text-align: center;
  background: rgba(255, 255, 255, 0.03); /* Sedikit highlight */
}

.profil .logo img {
  height: 50px;
  width: auto;
  margin-bottom: 20px;
  filter: drop-shadow(0 4px 4px rgba(0,0,0,0.2));
}

.data-profil {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 15px;
}

.data-profil img {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255,255,255,0.1);
  padding: 3px;
  transition: transform 0.3s ease;
}

.data-profil img:hover {
  transform: scale(1.05);
  border-color: #60a5fa;
}

.detail-data-profil {
  margin-top: 15px;
  margin-bottom: 10px;
}

.detail-data-profil .profile-name {
  font-size: 16px !important;
  font-weight: 700;
  margin-bottom: 5px;
  letter-spacing: 0.5px;
}

.detail-data-profil .profile-role {
  font-size: 12px !important;
  background: rgba(255,255,255,0.15);
  padding: 4px 12px;
  border-radius: 20px;
  display: inline-block;
  color: #cbd5e1 !important;
}

/* Garis Pemisah */
.side-bar hr {
  border: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
  margin: 10px 0 20px 0;
  width: 100%;
}


/* 3. Menu Navigation */
.menu { padding: 0 15px 50px 15px; }
.menu ul { list-style: none; padding: 0; margin: 0; width: 100%; }
.menu li { margin-bottom: 8px; width: 100%; }

/* Styling SEMUA BUTTON di dalam menu */
/* Selector ini menarget semua button di dalam li, termasuk .btn-settings, .btn-riwayat */
.menu ul li button,
.btn-riwayat,
.btn-settings {
  display: flex;
  align-items: center; /* Vertikal center */
  justify-content: flex-start; /* Teks rata kiri */
  width: 100%;
  padding: 12px 20px;
  background: transparent; /* Reset background */
  border: 1px solid transparent; /* Reset border */
  border-radius: 12px; /* Sudut membulat */
  color: #a0aec0; /* Warna teks abu-abu terang */
  font-family: "Poppins", sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  gap: 15px; /* Jarak icon ke teks */
}

/* Style Icon di dalam button */
.menu ul li button i,
.btn-riwayat i,
.btn-settings i {
  font-size: 18px;
  width: 25px; /* Lebar tetap agar teks sejajar vertikal */
  text-align: center;
  color: #a0aec0;
  transition: 0.3s;
}

/* --- HOVER EFFECT --- */
/* Saat kursor diarahkan ke button */
.menu ul li button:hover,
.btn-riwayat:hover,
.btn-settings:hover,
.dropdown-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: #ffffff; /* Teks jadi putih terang */
  transform: translateX(5px); /* Geser sedikit ke kanan */
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.menu ul li button:hover i,
.btn-riwayat:hover i,
.btn-settings:hover i {
  color: #60a5fa; /* Icon jadi biru muda */
  transform: scale(1.1);
}

/* Fokus state (saat diklik) */
.menu ul li button:focus {
  background-color: rgba(255, 255, 255, 0.15);
  color: white;
  outline: none;
}


/* 4. Dropdown Menu Styling */
/* Meng-override style bawaan bootstrap agar gelap & modern */
.dropdown-menu {
  background-color: #0f172a !important; /* Biru sangat gelap */
  border: 1px solid rgba(255,255,255,0.1) !important;
  border-radius: 12px !important;
  box-shadow: 0 10px 25px rgba(0,0,0,0.4) !important;
  padding: 8px !important;
  margin-top: 5px !important;
}

.dropdown-item {
  color: #cbd5e1 !important;
  padding: 10px 15px !important;
  border-radius: 8px;
  font-size: 13px !important;
  display: flex !important;
  align-items: center;
  gap: 10px;
  transition: all 0.2s;
}

.dropdown-item i {
  width: 20px;
  text-align: center;
}

.dropdown-item:hover {
  background-color: #1e293b !important;
  color: white !important;
  padding-left: 20px !important; /* Efek geser teks */
}
</style>

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
                <!-- <?php
                if (isset($_SESSION['login'])) {
                    echo '<li class="tambah-pengembalian-barang">
                        <button onclick="location.href=\'' . BASEURL . 'pengembalian\'">
                            <i class="fa-solid fa-rotate-left"></i>
                            Pengembalian
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
                if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
                    echo '<li class="tambah-merek-barang">
                        <button onclick="location.href=\'' . BASEURL . 'ValidasiPeminjaman\'">
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