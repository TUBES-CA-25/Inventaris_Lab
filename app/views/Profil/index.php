<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= BASEURL; ?>img/logo bg putih.svg" alt="logo" />
            <div class="profile-header-text">
                <h1>Sistem Inventori</h1>
                <h2>Barang ICLabs</h2>
            </div>
        </div>

        <!-- Flash Message -->
        <div class="row">
            <div class="col-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>

        <div class="profile-content">
            <!-- Photo Section -->
            <div class="profile-photo-section">
                <?php
                $profile_data = $data['profile'];
                $foto_profil = $profile_data['foto'];
                if ($foto_profil == "../public/img/foto-profile/" || empty($foto_profil)) {
                    echo '<img class="profile-photo" src="' . BASEURL . 'img/foto-profile/user.svg" alt="profile">';
                } else {
                    echo '<img class="profile-photo" src="' . BASEURL . $foto_profil . '" alt="profile">';
                }
                ?>
                <p class="upload-text">Upload foto</p>
            </div>

            <!-- Info Section -->
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
                    <div class="info-value"><?= htmlspecialchars($profile_data['nim_nips'] ?? '-'); ?></div>
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

        <div class="profile-actions">
            <a href="<?= BASEURL; ?>Beranda" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
            <button type="button" class="btn btn-edit" onclick="openEditModal()">
                <i class="fa-solid fa-pen-to-square"></i>
                Edit
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Profil</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form action="<?= BASEURL ?>Profil/ubah" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?= $profile_data['id_user']; ?>">
                
                <input type="hidden" name="id_user" value="<?= $profile_data['id_user']; ?>">
                <input type="hidden" name="fotoLama" value="<?= $profile_data['foto']; ?>">

                <div class="modal-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_user" 
                           value="<?= htmlspecialchars($profile_data['nama_user']); ?>" 
                           placeholder="Masukkan nama lengkap" 
                           maxlength="100" required>
                </div>

                <div class="modal-form-group">
                    <label>Email</label>
                    <input type="email" name="email" 
                           value="<?= htmlspecialchars($profile_data['email']); ?>" 
                           placeholder="Masukkan email" readonly style="background-color: #f0f0f0;">
                    <small style="color: #888;">Email tidak dapat diubah di sini.</small>
                </div>

                <div class="modal-form-group">
                    <label>NIM/NIP</label>
                    <input type="text" name="nim_nip" 
                           value="<?= htmlspecialchars($profile_data['nim_nip'] ?? ''); ?>" 
                           placeholder="Masukkan NIM/NIP" 
                           maxlength="30">
                </div>

                <div class="modal-form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp_user" 
                           value="<?= htmlspecialchars($profile_data['no_hp_user']); ?>" 
                           placeholder="Masukkan no. HP" 
                           maxlength="13" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                           required>
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
                    <textarea name="alamat" 
                              placeholder="Masukkan alamat" 
                              maxlength="200" required><?= htmlspecialchars($profile_data['alamat']); ?></textarea>
                </div>

                <div class="modal-form-group">
                    <label>Upload Foto (Maks 2 MB)</label>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-back" onclick="closeEditModal()">Kembali</button>
                    <button type="submit" class="btn btn-edit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan
                    </button>
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

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});

// Auto hide flash message
setTimeout(function() {
    const flashMessage = document.querySelector('.flash-message');
    if (flashMessage) {
        flashMessage.style.opacity = '0';
        flashMessage.style.transition = 'opacity 0.5s ease';
        setTimeout(() => flashMessage.remove(), 500);
    }
}, 3000);
</script>