<div class="body p-5" style="height: 95vh;">
    <div class="header mb-3">
        <img src="<?= BASEURL; ?>img/logo bg putih.svg" alt="logo" />
    </div>
    <div style="display: flex; gap:50px; width:100%;">

        <div class="justify-content-center d-flex" style="width: 100%;">
            <div class="card">
                <div class="card-header">
                    Informasi pribadi
                </div>
                <div class="card-body p-5">
                    <div class="foto" style="">
                        <?php
                        $profile_data = $data['profile'];
                        $foto_profil = $profile_data['foto'];
                        if ($foto_profil == "../public/img/foto-profile/") {
                            echo '<img src="' . BASEURL . $foto_profil . '/user.svg" alt="profile" style="border-radius: 50%; height: 180px; width: 180px; object-fit:cover;">';
                        } else {
                            echo '<img src="' . BASEURL . $foto_profil . '" alt="profile" style="border-radius: 50%; height: 180px; width: 180px; object-fit:cover;">';
                        }

                        echo '<div class="detail-data-profil">';
                        if (isset($profile_data['nama_user']) && isset($profile_data['role'])) {
                            $nama = $profile_data['nama_user'];
                            $role = $profile_data['role'];
                            echo '<h3 class="nama" style="color: black">' . $nama . '</h3>
                            <h3 class="role" style="color: black">' . $role . '</h3></div>';
                        }
                        ?>
                    </div>
                    <div style="width: 100%;">
                        <div class="flash" style="width: 80%; margin-left:15px;">
                            <?php Flasher::flash(); ?>
                        </div>
                        <span>
                            <h6>Nama Lengkap</h6>
                            <p><?= $profile_data['nama_user']; ?></p>
                        </span>
                        <span>
                            <h6>Email</h6>
                            <p><?= $profile_data['email']; ?></p>
                        </span>
                        <span>
                            <h6>No Hp</h6>
                            <p><?= $profile_data['no_hp_user']; ?></p>
                        </span>
                        <span>
                            <h6>Jenis Kelamin</h6>
                            <p><?= $profile_data['jenis_kelamin'] ?></p>
                        </span>
                        <span>
                            <h6>Alamat</h6>
                            <p><?= $profile_data['alamat'] ?></p>
                        </span>
                    </div>
                </div>
                <div class="btn-footer d-flex">
                    <a href="<?= BASEURL; ?>Beranda" class="btn">
                        <i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>
                        Kembali
                    </a>
                    <a href="<?= BASEURL; ?>/Profil/ubah/<?= $profile_data['id_user']; ?>"
                        data-id="<?= $profile_data['id_user']; ?>"
                        class="btn btn-Ubah-profile"
                        data-target="#modalUbah"
                        data-toggle="modal">
                        <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        Ubah
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:15px">
            <div class="modal-header">
                <h5 class="modal-title title-ubahUser">Ubah data user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body body-ubahUser">
                <form action="<?= BASEURL ?>Profil/ubah" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_user" id="id_user">
                    <div style="width: 70%; padding-left: 15px;">
                        <div class="foto">
                            <input type="file" name="foto" id="foto" accept="image/*"
                                placeholder="Pilih foto" />
                            <label for="foto">Upload Foto (Maks 2 MB) </label>
                        </div>
                        <br>
                        <div class="nama">
                            <label for="nama_user">Nama Lengkap</label>
                            <input type="text" id="nama_user" placeholder="Masukkan nama lengkap anda" maxlength="100"
                                name="nama_user" style="width: 100%;" required />
                        </div>
                        <br>
                        <div class="no_hp">
                            <label for="no_hp_user">No. Hp</label>
                            <input type="text" name="no_hp_user" id="no_hp_user" placeholder="Masukkan no. Hp anda" required
                                maxlength="13" oninput="validasiInput(this)" />
                        </div>
                        <br>
                        <div class="alamat">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" id="alamat" placeholder="Masukkan alamat anda" required maxlength="100" />
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="modal-footer" style="margin-right: 30%;">
                    </div>
                    <br>
                    <div style="display: flex; width:100%; justify-content: end; align-items: end;">
                        <button type="submit" id="kirim">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>