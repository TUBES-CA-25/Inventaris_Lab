<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .profile-container {
        background: linear-gradient(135deg, #0C1740 0%, #0C1740 100%);
        min-height: 100vh;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        padding: 40px 50px;
        max-width: 1100px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 35px;
    }

    .profile-header img {
        width: 50px;
        height: 50px;
    }

    .profile-header-text h1 {
        font-size: 20px;
        font-weight: 700;
        color: #0C1740;
        margin: 0;
        line-height: 1.2;
    }

    .profile-header-text h2 {
        font-size: 16px;
        font-weight: 500;
        color: #0C1740;
        margin: 0;
        line-height: 1.2;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 50px;
        align-items: start;
    }

    .profile-photo-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .profile-photo {
        width: 220px;
        height: 220px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f0f0f0;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .upload-text {
        font-size: 13px;
        color: #666;
        text-align: center;
        margin-top: 5px;
    }

    .profile-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 30px;
    }

    .info-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-group.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 2px;
    }

    .info-value {
        padding: 12px 18px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 14px;
        color: #1a1a2e;
        background: #f8f9fa;
        min-height: 44px;
        display: flex;
        align-items: center;
    }

    .info-value.textarea {
        min-height: 80px;
        align-items: flex-start;
    }

    .profile-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 35px;
        padding-top: 25px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 12px 35px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back {
        background: #e8e8e8;
        color: #1a1a2e;
    }

    .btn-back:hover {
        background: #d0d0d0;
    }

    .btn-edit {
        background: #0C1740;
        color: white;
    }

    .btn-edit:hover {
        background: #2d2d44;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(26, 26, 46, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        background: #0C1740;
        color: white;
        padding: 20px 30px;
        border-radius: 20px 20px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    .modal-close:hover {
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .modal-form-group {
        margin-bottom: 20px;
    }

    .modal-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #0C1740;
        margin-bottom: 8px;
    }

    .modal-form-group input,
    .modal-form-group select,
    .modal-form-group textarea {
        width: 100%;
        padding: 12px 18px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 14px;
        color: #0C1740;
        background: white;
        transition: all 0.3s ease;
    }

    .modal-form-group input:focus,
    .modal-form-group select:focus,
    .modal-form-group textarea:focus {
        outline: none;
        border-color: #FFC837;
    }

    .modal-form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .modal-form-group input[type="file"] {
        padding: 10px;
        font-size: 13px;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .flash-message {
        padding: 12px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .flash-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .flash-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .flash-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    @media (max-width: 968px) {
        .profile-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .profile-info {
            grid-template-columns: 1fr;
        }

        .profile-photo {
            width: 180px;
            height: 180px;
        }

        .profile-card {
            padding: 30px 25px;
        }
    }

    @media (max-width: 576px) {
        .profile-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message flash-<?= $_SESSION['flash']['type']; ?>">
                <?= $_SESSION['flash']['message']; ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

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
                           placeholder="Masukkan email" required>
                </div>

                <div class="modal-form-group">
                    <label>NIM/NIPS</label>
                    <input type="text" name="nim_nips" 
                           value="<?= htmlspecialchars($profile_data['nim_nips'] ?? ''); ?>" 
                           placeholder="Masukkan NIM/NIPS" 
                           maxlength="50">
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