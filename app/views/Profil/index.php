<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= BASEURL; ?>img/logo bg putih.svg" alt="logo" />
            <div class="profile-header-text">
                <h1>Sistem Inventori</h1>
                <h2>Barang ICLabs</h2>
            </div>
        </div>

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
                
                if ($foto_profil == "../public/img/foto-profile/" || empty($foto_profil) || !file_exists(str_replace('../public/', '', $foto_profil))) {
                    echo '<img class="profile-photo" src="' . BASEURL . 'img/foto-profile/user.svg" alt="profile">';
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
                    <label class="info-label">NIM/NIP</label>
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
            
            <a href="<?= BASEURL; ?>Beranda" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <?php if ($profile_data['id_role'] == 1 || $profile_data['id_role'] == 2) : ?>
                <button type="button" class="btn" style="background-color: #4e73df; color: white;" onclick="openTTDModal()">
                    <i class="fa-solid fa-file-signature"></i> Kelola Tanda Tangan
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-edit" onclick="openEditModal()">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
            </button>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['2'])) : ?>
<div class="modal-overlay" id="ttdModal">
    <div class="modal-content" style="max-width: 700px;"> <div class="modal-header">
            <h3>Manajemen Tanda Tangan Digital</h3>
            <button class="modal-close" onclick="closeTTDModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <form action="<?= BASEURL ?>Profil/updateTTD" method="post" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6 mb-3" style="border-right: 1px solid #eee;">
                        <h5 class="text-primary font-weight-bold text-center mb-3">Kepala Laboratorium</h5>
                        
                        <div class="text-center p-3 mb-3" style="background: #f8f9fc; border: 2px dashed #4e73df; border-radius: 8px;">
                            <img src="<?= BASEURL; ?>img/ttd/ttd_huzain.png?t=<?= time(); ?>" 
                                 alt="TTD Huzain" 
                                 class="img-fluid" 
                                 style="max-height: 100px; object-fit: contain;"
                                 onerror="this.src='https://via.placeholder.com/150x80?text=Belum+Ada';">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Ganti TTD Kepala Lab (.png)</label>
                            <input type="file" name="ttd_kalab" accept="image/png" class="form-control-file border p-1 rounded">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <h5 class="text-success font-weight-bold text-center mb-3">Laboran</h5>
                        
                        <div class="text-center p-3 mb-3" style="background: #f0fdf4; border: 2px dashed #1cc88a; border-radius: 8px;">
                            <img src="<?= BASEURL; ?>img/ttd/ttd_fatimah.png?t=<?= time(); ?>" 
                                 alt="TTD Fatimah" 
                                 class="img-fluid" 
                                 style="max-height: 100px; object-fit: contain;"
                                 onerror="this.src='https://via.placeholder.com/150x80?text=Belum+Ada';">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Ganti TTD Laboran (.png)</label>
                            <input type="file" name="ttd_laboran" accept="image/png" class="form-control-file border p-1 rounded">
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

                <div class="modal-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_user" value="<?= htmlspecialchars($profile_data['nama_user']); ?>" maxlength="100" required>
                </div>

                <div class="modal-form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($profile_data['email']); ?>" readonly style="background-color: #f0f0f0;">
                </div>

                <div class="modal-form-group">
                    <label>NIM/NIP</label>
                    <input type="text" name="nim_nip" value="<?= htmlspecialchars($profile_data['nim_nip'] ?? ''); ?>" maxlength="30">
                </div>

                <div class="modal-form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp_user" value="<?= htmlspecialchars($profile_data['no_hp_user']); ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
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
                    <button type="button" class="btn btn-back" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-edit">Simpan Biodata</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // FUNGSI MODAL
    function openEditModal() { document.getElementById('editModal').classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeEditModal() { document.getElementById('editModal').classList.remove('active'); document.body.style.overflow = 'auto'; }
    function openTTDModal() { document.getElementById('ttdModal').classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeTTDModal() { document.getElementById('ttdModal').classList.remove('active'); document.body.style.overflow = 'auto'; }

    // KLIK DI LUAR MODAL
    window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) closeEditModal();
        if (event.target == document.getElementById('ttdModal')) closeTTDModal();
    }
    
    // AUTO HIDE FLASH
    setTimeout(function() {
        const flashMessage = document.querySelector('.flash-message');
        if (flashMessage) {
            flashMessage.style.opacity = '0';
            flashMessage.style.transition = 'opacity 0.5s ease';
            setTimeout(() => flashMessage.remove(), 500);
        }
    }, 3000);
</script>