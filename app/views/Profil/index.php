<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= BASEURL; ?>img/logo bg putih.svg" alt="logo" />
            <!-- <div class="profile-header-text">
                <h1>Sistem Inventori</h1>
                <h2>Barang ICLabs</h2>
            </div> -->
        </div>

        <!-- Flash Message -->
        <div class="row">
            <div class="col-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-photo-section">
                <?php
                $profile_data = $data['profile'];
                $foto_profil = $profile_data['foto'];

                if (empty($foto_profil) || $foto_profil == "../public/img/foto-profile/" || !file_exists(str_replace('../public/', '', $foto_profil))) {
                    echo '<i class="fa-solid fa-circle-user profile-photo" style="font-size: 220px; color: #1e293b; display: flex; justify-content: center; align-items: center; background: white; border: none;"></i>';
                } else {
                    echo '<img class="profile-photo" src="' . BASEURL . $foto_profil . '" alt="profile">';
                }
                ?>
                <p class="upload-text">Foto Profil</p>
            </div>

            <div class="profile-info">
                <div class="info-group">
                    <label class="info-label">Nama Lengkap</label>
                    <div class="info-value"><?= htmlspecialchars($profile_data['nama_user']); ?></div>
                </div>
                <div class="info-group">
                    <label class="info-label">Email</label>
                    <div class="info-value"><?= htmlspecialchars($profile_data['email']); ?></div>
                </div>
                <div class="info-group">
                    <label class="info-label">NIM/NIPS</label>
                    <div class="info-value"><?= htmlspecialchars($profile_data['nim_nip'] ?? '-'); ?></div>
                </div>
                <div class="info-group">
                    <label class="info-label">No HP</label>
                    <div class="info-value"><?= htmlspecialchars($profile_data['no_hp_user']); ?></div>
                </div>
                <div class="info-group">
                    <label class="info-label">Jenis Kelamin</label>
                    <div class="info-value"><?= htmlspecialchars($profile_data['jenis_kelamin']); ?></div>
                </div>
                <div class="info-group full-width">
                    <label class="info-label">Alamat</label>
                    <div class="info-value textarea"><?= htmlspecialchars($profile_data['alamat']); ?></div>
                </div>
            </div>
        </div>

        <div class="profile-actions" style="display: flex; justify-content: center; gap: 15px; padding-bottom: 20px;">

            <a href="<?= BASEURL; ?>Beranda" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <?php if (in_array($profile_data['id_role'], [1, 2, 4, 5, 6])): ?>
                <button type="button" class="btn btn-navy rounded-pill" onclick="openTTDModal()">
                    <i class="fa-solid fa-file-signature"></i> Kelola Tanda Tangan
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-navy rounded-pill btn-edit" onclick="openEditModal()">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
            </button>

            <button type="button" class="btn btn-navy rounded-pill" onclick="openPassModal()">
                <i class="fa-solid fa-key"></i> Ganti Password
            </button>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['login']) && in_array($profile_data['id_role'], [1, 2, 4, 5, 6])): ?>
    <div class="modal-overlay" id="ttdModal">
        <div class="modal-content"
            style="max-width: <?= in_array($profile_data['id_role'], [1, 2]) ? '700px' : '500px' ?>;">
            <div class="modal-header">
                <h3><?= in_array($profile_data['id_role'], [1, 2]) ? 'Manajemen Tanda Tangan Digital' : 'Tanda Tangan Saya' ?>
                </h3>
                <button class="modal-close" onclick="closeTTDModal()">&times;</button>
            </div>

            <div class="modal-body">
                <?php if (in_array($profile_data['id_role'], [1, 2])): ?>
                    <form action="<?= BASEURL ?>Profil/updateTTD" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3" style="border-right: 1px solid #eee;">
                                <h5 class="text-primary font-weight-bold text-center mb-3">Kepala Laboratorium</h5>
                                <div class="text-center p-3 mb-3"
                                    style="background: #f8f9fc; border: 2px dashed #4e73df; border-radius: 8px;">
                                    <img src="<?= BASEURL; ?>img/ttd/ttd_huzain.png?t=<?= time(); ?>" alt="TTD Huzain"
                                        class="img-fluid" style="max-height: 100px; object-fit: contain;"
                                        onerror="this.outerHTML='<span style=\'color: #858796; font-style: italic; font-weight: bold;\'>Tidak ada TTD</span>';">
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold">Ganti TTD Kepala Lab (.png)</label>
                                    <input type="file" name="ttd_kalab" accept="image/png"
                                        class="form-control-file border p-1 rounded">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h5 class="text-success font-weight-bold text-center mb-3">Laboran</h5>
                                <div class="text-center p-3 mb-3"
                                    style="background: #f0fdf4; border: 2px dashed #1cc88a; border-radius: 8px;">
                                    <img src="<?= BASEURL; ?>img/ttd/ttd_fatimah.png?t=<?= time(); ?>" alt="TTD Fatimah"
                                        class="img-fluid" style="max-height: 100px; object-fit: contain;"
                                        onerror="this.outerHTML='<span style=\'color: #858796; font-style: italic; font-weight: bold;\'>Tidak ada TTD</span>';">
                                </div>
                                <div class="form-group">
                                    <label class="small font-weight-bold">Ganti TTD Laboran (.png)</label>
                                    <input type="file" name="ttd_laboran" accept="image/png"
                                        class="form-control-file border p-1 rounded">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info small mt-2">
                            <i class="fa-solid fa-info-circle"></i>
                            Upload file baru hanya jika ingin mengubah tanda tangan. Wajib format <b>.PNG Transparan</b>.
                        </div>
                        <div class="modal-actions mt-3">
                            <button type="button" class="btn btn-back" onclick="closeTTDModal()">Batal</button>
                            <button type="submit" class="btn btn-edit" style="background-color: #4e73df;">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan & Update TTD
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <form action="<?= BASEURL ?>Profil/ubah" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_user" value="<?= $profile_data['id_user']; ?>">
                        <input type="hidden" name="nama_user" value="<?= $profile_data['nama_user']; ?>">
                        <input type="hidden" name="nim_nip" value="<?= $profile_data['nim_nip']; ?>">
                        <input type="hidden" name="no_hp_user" value="<?= $profile_data['no_hp_user']; ?>">
                        <input type="hidden" name="jenis_kelamin" value="<?= $profile_data['jenis_kelamin']; ?>">
                        <input type="hidden" name="alamat" value="<?= $profile_data['alamat']; ?>">
                        <input type="hidden" name="fotoLama" value="<?= $profile_data['foto']; ?>">
                        <input type="hidden" name="file_ttdLama" value="<?= $profile_data['file_ttd']; ?>">

                        <div class="text-center mb-4">
                            <p class="small text-muted mb-2">Tanda Tangan Saat Ini:</p>
                            <div class="p-3 mb-3 mx-auto"
                                style="max-width: 300px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($profile_data['file_ttd']) && file_exists('../public/img/ttd/' . $profile_data['file_ttd'])): ?>
                                    <img src="<?= BASEURL; ?>img/ttd/<?= $profile_data['file_ttd']; ?>?t=<?= time(); ?>"
                                        alt="TTD Saya" class="img-fluid" style="max-height: 100px; object-fit: contain;">
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fa-solid fa-file-signature text-muted mb-2" style="font-size: 2rem;"></i>
                                        <div class="small font-italic text-muted">Belum ada tanda tangan</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-2">Upload Tanda Tangan Baru (.png)</label>
                            <input type="file" name="file_ttd" accept="image/png" class="form-control border p-2 rounded"
                                required>
                            <small class="text-muted d-block mt-2">
                                <i class="fa-solid fa-circle-info"></i> Pastikan gambar memiliki latar belakang
                                <b>transparan</b>.
                            </small>
                        </div>

                        <div class="modal-actions mt-4">
                            <button type="button" class="btn btn-back" onclick="closeTTDModal()">Batal</button>
                            <button type="submit" class="btn btn-navy" style="padding: 10px 25px;">
                                <i class="fa-solid fa-upload"></i> Unggah Tanda Tangan
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Profil</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form action="<?= BASEURL ?>Profil/ubah" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?= $profile_data['id_user']; ?>">
                <input type="hidden" name="fotoLama" value="<?= $profile_data['foto']; ?>">
                <input type="hidden" name="file_ttdLama" value="<?= $profile_data['file_ttd']; ?>">

                <div class="modal-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_user" value="<?= htmlspecialchars($profile_data['nama_user']); ?>"
                        maxlength="100" required>
                </div>

                <div class="modal-form-group">
                    <label>Email</label>`q
                    <input type="email" name="email" value="<?= htmlspecialchars($profile_data['email']); ?>"
                        placeholder="Masukkan email" readonly style="background-color: #f0f0f0;">
                    <small style="color: #888;">Email tidak dapat diubah di sini.</small>
                </div>

                <div class="modal-form-group">
                    <label>NIM/NIP</label>
                    <input type="text" name="nim_nip" value="<?= htmlspecialchars($profile_data['nim_nip'] ?? ''); ?>"
                        placeholder="Masukkan NIM/NIP" maxlength="30">
                </div>

                <div class="modal-form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp_user" value="<?= htmlspecialchars($profile_data['no_hp_user']); ?>"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="modal-form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= ($profile_data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?= ($profile_data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <div class="modal-form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" required><?= htmlspecialchars($profile_data['alamat']); ?></textarea>
                </div>

                <div class="modal-form-group">
                    <label>Ganti Foto Profil</label>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-back" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-navy btn-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-overlay" id="passModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ganti Password</h3>
            <button class="modal-close" onclick="closePassModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form action="<?= BASEURL ?>Profil/gantiPassword" method="post">
                <div class="modal-form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" name="currentPassword" required>
                </div>
                <div class="modal-form-group">
                    <label>Password Baru</label>
                    <input type="password" name="newPassword" required>
                </div>
                <div class="modal-form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="confirmPassword" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-back" onclick="closePassModal()">Batal</button>
                    <button type="submit" class="btn btn-navy">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal() {
        document.getElementById('editModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openTTDModal() {
        const modal = document.getElementById('ttdModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeTTDModal() {
        const modal = document.getElementById('ttdModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    document.getElementById('editModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Event listener untuk modal TTD jika ada
    const ttdModal = document.getElementById('ttdModal');
    if (ttdModal) {
        ttdModal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeTTDModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeTTDModal();
        }
    });

    setTimeout(function () {
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            flashMessage.style.opacity = '0';
            flashMessage.style.transition = 'opacity 0.5s ease';
            setTimeout(() => flashMessage.remove(), 500);
        }
    }, 3000);

    function openPassModal() {
        document.getElementById('passModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closePassModal() {
        document.getElementById('passModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Tambahkan event listener untuk modal password
    const passModal = document.getElementById('passModal');
    if (passModal) {
        passModal.addEventListener('click', function (e) {
            if (e.target === this) closePassModal();
        });
    }

    // Perbaiki event listener ESC agar mencakup semua modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeTTDModal();
            if (typeof closePassModal === "function") closePassModal();
        }
    });
</script>