<?php
// if (!isset($_SESSION['login'])) {
//     header("Location:" . BASEURL . "Login");
//     exit;
// }

$isEdit = isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true;
$headerData = $isEdit ? $_SESSION['edit_header'] : [];
$detailMap  = $isEdit ? $_SESSION['edit_details_map'] : [];

$formAction = $isEdit ? BASEURL . 'Peminjaman/prosesUpdatePeminjaman' : BASEURL . 'Peminjaman/prosesTambahPeminjaman';

$val_judul      = $isEdit ? $headerData['judul_kegiatan'] : '';
$val_tgl_aju    = $isEdit ? $headerData['tanggal_pengajuan'] : date('Y-m-d');
$val_tgl_mulai  = $isEdit ? $headerData['tanggal_peminjaman'] : '';
$val_tgl_akhir  = $isEdit ? $headerData['tanggal_pengembalian'] : '';
?>

<meta name="base-url" content="<?= BASEURL; ?>">
<link rel="stylesheet" href="<?= BASEURL; ?>css/peminjamanForm.css">

<div class="content">
    <div class="container-fluid">
        <div class="form-card">
            <form action="<?= $formAction; ?>" method="post">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="form-title"><?= $isEdit ? 'Edit Peminjaman' : 'Peminjaman'; ?></h2>

                        <div class="gap-row">
                            <label class="lbl">Judul kegiatan</label>
                            <input type="text" name="judul_kegiatan" class="inp-custom" value="<?= $val_judul; ?>" required>
                        </div>

                        <div class="gap-row">
                            <label class="lbl">Tanggal pengajuan</label>
                            <div class="icon-wrap">
                                <input type="date" name="tanggal_pengajuan" class="inp-custom" value="<?= $val_tgl_aju; ?>" required>
                                <i class="fa-regular fa-calendar icon-inside icon-calendar"></i>
                            </div>
                        </div>

                        <div class="row row-item-grid gap-row">
                            <div class="col-md-6">
                                <label class="lbl">Mulai dari tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_peminjaman" class="inp-custom" value="<?= $val_tgl_mulai; ?>" required>
                                    <i class="fa-regular fa-calendar icon-inside icon-calendar"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="lbl">Sampai tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_pengembalian" class="inp-custom" value="<?= $val_tgl_akhir; ?>" required>
                                    <i class="fa-regular fa-calendar icon-inside icon-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="gap-row">
                            <label class="lbl">Komentar / Keterangan Peminjaman</label>
                            <textarea name="keterangan_peminjaman" class="inp-custom" rows="3" placeholder="Contoh: Untuk keperluan praktikum..."><?= $isEdit && isset($headerData['keterangan_peminjaman']) ? $headerData['keterangan_peminjaman'] : ''; ?></textarea>
                        </div>

                    </div>

                    <div class="col-md-5 right-section">
                        <div class="content-figure">
                            <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
                            <div class="hello-text">Hello! 👋</div>
                        </div>
                    </div>
                </div>

                <hr class="hr-dashed">

                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($data['barang_selected'])): ?>

                            <?php foreach ($data['barang_selected'] as $item):
                                $id = $item['id_jenis_barang'];
                                $curr_jml = 1;
                                $curr_unit = '';

                                if ($isEdit && isset($detailMap[$id]) && !empty($detailMap[$id])) {
                                    $saved_data = array_shift($detailMap[$id]);
                                    $curr_jml = $saved_data['jumlah'];
                                    $curr_unit = $saved_data['keterangan'];
                                }
                            ?>
                                <div class="item-row">
                                    <div class="row row-item-grid align-items-end">

                                        <div class="col-md-5">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <label class="lbl mb-0">Jenis Barang</label>
                                                            <button type="button" class="btn-cancel-item"
                                                                onclick="konfirmasiHapus('<?= BASEURL; ?>Peminjaman/hapusItem/<?= $item['hapus_id']; ?>')"> <i class="fa-solid fa-circle-xmark"></i> Hapus
            </button>
                                                        </div>
                                            <div class="icon-wrap">
                                                <input type="hidden" name="id_jenis_barang[]" value="<?= $item['id_jenis_barang']; ?>">
                                                <input type="text" class="inp-custom inp-readonly" value="<?= $item['sub_barang']; ?>" readonly>
                                                <i class="fa-solid fa-check icon-inside icon-check"></i>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="lbl">Jumlah</label>
                                            <input type="number" name="jumlah_peminjaman[]" class="inp-custom text-center-input" min="1" value="<?= $curr_jml; ?>" required>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="lbl">Pilih Spesifikasi Unit</label>
                                            <div class="icon-wrap">
                                                <select name="unit_selected[]" class="inp-custom" required>
                                                    <option value="">-- Pilih Unit --</option>

                                                    <?php if (!empty($item['list_unit'])) : ?>
                                                        <?php foreach ($item['list_unit'] as $unit) : ?>
                                                            <option value="<?= $unit['id_barang']; ?>"
                                                                <?= ($curr_unit == $unit['id_barang']) ? 'selected' : ''; ?>>
                                                                Unit <?= $unit['spesifikasi_barang']; ?> - (<?= $unit['kondisi_barang']; ?>)
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <option value="" disabled>Tidak ada unit tersedia</option>
                                                    <?php endif; ?>

                                                    <option value="Lainnya" <?= ($curr_unit == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                                </select>
                                                <i class="fa-solid fa-caret-down icon-inside icon-caret"></i>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>





                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i> Data barang kosong. Silakan tambah barang.
                            </div>
                        <?php endif; ?>

                        <div class="add-more-container" style="margin-top: 20px; text-align: center;">
                            <a href="<?= BASEURL; ?>Peminjaman" class="btn btn-primary" title="Tambah Barang Lain" style="border-radius: 50px; padding: 10px 20px;">
                                <i class="fa-solid fa-plus"></i> Tambah Barang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="action-footer">
                    <?php if ($isEdit): ?>
                        <a href="<?= BASEURL; ?>Riwayat" class="btn-back">Batal Edit</a>
                    <?php else: ?>
                        <a href="<?= BASEURL; ?>Peminjaman" class="btn-back">Kembali</a>
                    <?php endif; ?>

                    <?php if (!empty($data['barang_selected'])): ?>
                        <button type="submit" class="btn-send" id="btnSubmitPeminjaman">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Kirim'; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalHapus" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="modal-icon-circle"><i class="fa-regular fa-trash-can"></i></div>
        <h3 class="modal-title">Hapus Item?</h3>
        <p class="modal-desc">Apakah Anda yakin ingin menghapus barang ini<br>dari daftar peminjaman?</p>
        <div class="modal-btn-group">
            <button type="button" onclick="tutupModal()" class="btn-modal-cancel">Batal</button>
            <a id="btnLinkHapus" href="#" class="btn-modal-delete">Ya, Hapus</a>
        </div>
    </div>
</div>

<<<<<<< HEAD
<script src="<?= BASEURL; ?>js/peminjaman_form.js"></script>
=======
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        var btn = document.getElementById('btnSubmitPeminjaman');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
        }
    });

    // Cek apakah ada data Flash dari Controller
    <?php if (isset($_SESSION['flash'])) : ?>
        Swal.fire({
            title: "<?= $_SESSION['flash']['pesan']; ?>",
            html: "<?= $_SESSION['flash']['aksi']; ?>", // Pakai HTML agar bisa bold
            icon: "<?= $_SESSION['flash']['tipe']; ?>", // warning, error, success
            confirmButtonColor: '#1250ba',
            confirmButtonText: 'Oke, Saya Cek Lagi'
        });
        <?php unset($_SESSION['flash']); // Hapus session agar tidak muncul terus ?>
    <?php endif; ?>
</script>
>>>>>>> origin/main
