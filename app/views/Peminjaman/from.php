<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

$isEdit = isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true;
$headerData = $isEdit ? $_SESSION['edit_header'] : [];
$detailMap = $isEdit ? $_SESSION['edit_details_map'] : [];

$formAction = $isEdit ? BASEURL . 'Peminjaman/prosesUpdatePeminjaman' : BASEURL . 'Peminjaman/prosesTambahPeminjaman';

$val_judul = $isEdit ? $headerData['judul_kegiatan'] : '';
$val_tgl_aju = $isEdit ? $headerData['tanggal_pengajuan'] : date('Y-m-d');
$val_tgl_mulai = $isEdit ? $headerData['tanggal_peminjaman'] : '';
$val_tgl_akhir = $isEdit ? $headerData['tanggal_pengembalian'] : '';
?>

<div class="content">
    <div class="container-fluid">
        <div class="form-card">
            <form action="<?= $formAction; ?>" method="post">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="form-title"><?= $isEdit ? 'Edit Peminjaman' : 'Peminjaman'; ?></h2>
                        <div class="gap-row">
                            <label class="lbl">Judul kegiatan</label>
                            <input type="text" name="judul_kegiatan" class="inp-custom" value="<?= $val_judul; ?>"
                                required>
                        </div>
                        <div class="gap-row">
                            <label class="lbl">Tanggal pengajuan</label>
                            <div class="icon-wrap">
                                <input type="date" name="tanggal_pengajuan" class="inp-custom"
                                    value="<?= $val_tgl_aju; ?>" required>
                                <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                            </div>
                        </div>
                        <div class="row row-item-grid gap-row">
                            <div class="col-md-6">
                                <label class="lbl">Mulai dari tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_peminjaman" class="inp-custom"
                                        value="<?= $val_tgl_mulai; ?>" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="lbl">Sampai tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_pengembalian" class="inp-custom"
                                        value="<?= $val_tgl_akhir; ?>" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 right-section">
                        <div class="content-figure">
                            <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg"
                                alt="figure" />
                            <div class="hello-text">Hello! 👋</div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 2px dashed #e2e8f0; margin: 30px 0;">

                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($data['barang_selected'])): ?>

                            <?php foreach ($data['barang_selected'] as $item):
                                // LOGIKA PENGISIAN VALUE BARANG (EDIT)
                                $id = $item['id_jenis_barang'];
                                $curr_jml = 1;
                                $curr_ket = '';

                                if ($isEdit && isset($detailMap[$id])) {
                                    $curr_jml = $detailMap[$id]['jumlah'];
                                    $curr_ket = $detailMap[$id]['keterangan'];
                                }
                            ?>
                                <div class="item-row">
                                    <div class="row row-item-grid align-items-end">
                                        <div class="col-md-5">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="lbl mb-0">Jenis Barang</label>
                                                <button type="button" class="btn-cancel-item"
                                                    onclick="konfirmasiHapus('<?= BASEURL; ?>Peminjaman/hapusItem/<?= $item['id_jenis_barang']; ?>')">
                                                    <i class="fa-solid fa-circle-xmark"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="icon-wrap">
                                                <input type="hidden" name="id_jenis_barang[]"
                                                    value="<?= $item['id_jenis_barang']; ?>">
                                                <input type="text" class="inp-custom inp-readonly"
                                                    value="<?= $item['sub_barang']; ?>" readonly>
                                                <i class="fa-solid fa-check icon-inside"
                                                    style="color: #22c55e; font-size: 18px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="lbl">Jumlah</label>
                                            <input type="number" name="jumlah_peminjaman[]" class="inp-custom" min="1"
                                                value="<?= $curr_jml; ?>" required style="text-align: center;">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="lbl">Keterangan</label>
                                            <input type="text" name="keterangan_peminjaman[]" class="inp-custom"
                                                value="<?= $curr_ket; ?>" placeholder="-">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="add-more-container">
                                <a href="<?= BASEURL; ?>Peminjaman" class="btn-add-more" title="Tambah Barang Lain">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-warning">
                                Keranjang kosong. Silakan pilih barang di menu utama.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-footer">
                    <?php if ($isEdit): ?>
                        <a href="<?= BASEURL; ?>Riwayat" class="btn-back">Batal Edit</a>
                    <?php else: ?>
                        <a href="<?= BASEURL; ?>Peminjaman" class="btn-back">Kembali</a>
                    <?php endif; ?>

                    <?php if (!empty($data['barang_selected'])): ?>
                        <button type="submit" class="btn-send">
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