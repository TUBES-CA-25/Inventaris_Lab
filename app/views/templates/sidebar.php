<div class="body-beranda">
    <div class="side-bar d-flex flex-column justify-content-between">

        <div>
            <div class="logo-header p-10 d-flex align-items-center" style="margin: 15px; margin-bottom: 15px;">
                <img src="<?= BASEURL; ?>img/logo bg hitam.svg" alt="logo" />
            </div>

            <div class="profil text-center mb-4">
                <div class="img-container mb-2">
                    <?php
                    $foto_profil = $data['profile']['foto'];
                    // Cek apakah foto default atau custom
                    $src = (strpos($foto_profil, 'user.svg') !== false || empty($foto_profil))
                        ? BASEURL . 'img/foto-profile/user.svg'
                        : BASEURL . $foto_profil;
                    ?>
                    <img src="<?= $src; ?>" alt="profile" class="profile-img">
                </div>
                <div class="profile-info">
                    <?php if (isset($data['profile']['nama_user'])) : ?>
                        <h6 class="text-white font-weight-bold mb-0"><?= $data['profile']['nama_user']; ?></h6>
                        <small class="text-muted-light"><?= $data['profile']['role']; ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="menu-list">
                <a href="<?= BASEURL; ?>Beranda" class="menu-item <?= ($data['judul'] == 'Beranda') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Beranda</span>
                </a>

                <?php if (isset($_SESSION['login'])) : ?>
                    <a href="<?= BASEURL; ?>DetailBarang" class="menu-item <?= ($data['judul'] == 'Detail Barang') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Detail Barang</span>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['login'])) : ?>
                    <a href="<?= BASEURL; ?>Peminjaman" class="menu-item <?= ($data['judul'] == 'Peminjaman') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Peminjaman</span>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['login'])) : ?>
                    <a href="<?= BASEURL; ?>Riwayat" class="menu-item <?= ($data['judul'] == 'Riwayat Peminjaman') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Riwayat Peminjaman</span>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) : ?>
                    <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="menu-item <?= ($data['judul'] == 'Validasi Peminjaman') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-file-circle-check"></i>
                        <span>Validasi Peminjaman</span>
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) : ?>
                    <a href="<?= BASEURL; ?>KelolaAkun" class="menu-item <?= ($data['judul'] == 'Kelola Akun') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Kelola Akun</span>
                    </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['login'])) : ?>
                    <a href="<?= BASEURL; ?>Profil" class="menu-item <?= ($data['judul'] == 'Profil') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Profile</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="menu-footer p-4">
            <a href="#" data-toggle="modal" data-target="#konfirmasiKeluar" class="menu-item logout-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Keluar</span>
            </a>
        </div>

    </div>

    <div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-body" style="text-align: center;">
                    <lottie-player src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json" background="transparent" speed="1" style="width: 250px; height: 250px; margin: 0 auto;" loop autoplay></lottie-player>
                    <p style="color:#385161; opacity: 0.6; font-weight: 500;">Apakah anda yakin ingin keluar?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-light" style="width: 100px;" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" style="width: 100px;" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
                </div>
            </div>
        </div>
    </div>