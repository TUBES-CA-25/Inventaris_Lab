<button class="hamburger-menu" id="hamburgerBtn">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<nav class="side-bar d-flex flex-column justify-content-between" id="sidebarMenu">
    <div>
        <div class="logo-header p-10 d-flex align-items-center" style="margin: 15px; margin-bottom: 15px;">
            <img src="<?= BASEURL; ?>img/logo bg hitam.svg" alt="logo" />
        </div>

        <div class="profil text-center mb-4">
            <div class="img-container mb-2"
                style="display: flex; justify-content: center; align-items: center; height: 80px;">
                <?php
                $foto_profil = $data['profile']['foto'] ?? '';
                // Cek apakah user punya foto custom (tidak kosong & bukan file default lama)
                $has_photo = !empty($foto_profil) && strpos($foto_profil, 'PersonCircle.png') === false;
                ?>

                <?php if ($has_photo): ?>
                    <img src="<?= BASEURL . $foto_profil; ?>" alt="profile" class="profile-img"
                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">

                    <i class="fa-solid fa-circle-user text-white" style="font-size: 60px; display: none;"></i>

                <?php else: ?>
                    <i class="fa-solid fa-circle-user text-white" style="font-size: 60px;"></i>
                <?php endif; ?>
            </div>
            <div class="profile-info1">
                <?php if (isset($data['profile']['nama_user'])): ?>
                    <h6 class="text-white font-weight-bold mb-0"><?= $data['profile']['nama_user']; ?></h6>
                    <small class="text-muted-light"><?= $data['profile']['role']; ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="menu-list">
            <?php
            $role = $_SESSION['id_role'] ?? '';
            $is_admin_access = in_array($role, [ROLE_KALAB, ROLE_LABORAN]);
            $is_academic = in_array($role, [ROLE_DOSEN, ROLE_MAHASISWA]);
            ?>

            <a href="<?= BASEURL; ?>Beranda" class="menu-item <?= ($data['judul'] == 'Beranda') ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i>
                <span>Beranda</span>
            </a>

            <?php if (isset($_SESSION['login']) && ($is_admin_access || $is_academic)): ?>
                <a href="<?= BASEURL; ?>DetailBarang"
                    class="menu-item <?= ($data['judul'] == 'Detail Barang') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Detail Barang</span>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['login']) && (in_array($role, [ROLE_DOSEN, ROLE_MAHASISWA, '5', '6', '7']))): ?>
                <a href="<?= BASEURL; ?>Peminjaman"
                    class="menu-item <?= ($data['judul'] == 'Peminjaman') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Peminjaman</span>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['login']) && ($is_academic)): ?>
                <a href="<?= BASEURL; ?>Pengembalian"
                    class="menu-item <?= ($data['judul'] == 'Pengembalian') ? 'active' : ''; ?>">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Pengembalian</span>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['login'])): ?>
                <a href="<?= BASEURL; ?>Riwayat"
                    class="menu-item <?= ($data['judul'] == 'Riwayat Peminjaman') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Peminjaman</span>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['login']) && $is_admin_access): ?>
                <a href="<?= BASEURL; ?>ValidasiPeminjaman"
                    class="menu-item <?= ($data['judul'] == 'Validasi Peminjaman') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Validasi Peminjaman</span>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['login']) && ($is_admin_access || $role == ROLE_DOSEN)): ?>
                <a href="<?= BASEURL; ?>KelolaAkun"
                    class="menu-item <?= ($data['judul'] == 'Kelola Akun') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Kelola Akun</span>
                </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['login'])): ?>
                <a href="<?= BASEURL; ?>Profil" class="menu-item <?= ($data['judul'] == 'Profil') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user"></i>
                    <span>Profile</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="menu-footer p-4">
        <a href="#" data-toggle="modal" data-target="#konfirmasiKeluar" data-bs-toggle="modal"
            data-bs-target="#konfirmasiKeluar" class="menu-item logout-link">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Keluar</span>
        </a>
    </div>

</nav>
    <div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-hidden="true"
        style="z-index: 99999;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-body" style="text-align: center;">
                    <lottie-player src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                        background="transparent" speed="1" style="width: 250px; height: 250px; margin: 0 auto;" loop
                        autoplay></lottie-player>
                    <p style="color:#385161; opacity: 0.6; font-weight: 500;">Apakah anda yakin ingin keluar?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" style="width: 100px;" data-dismiss="modal"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger" style="width: 100px;"
                        onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
                </div>
            </div>
        </div>
    </div>