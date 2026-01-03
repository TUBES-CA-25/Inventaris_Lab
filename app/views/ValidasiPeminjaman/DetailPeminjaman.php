<?php
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid p-4">
        <h1 class="page-title">Detail peminjaman</h1>

        <?php $p = $data['peminjaman']; ?>

        <div class="row">
            <div class="col-12">
                <div class="detail-label">Judul kegiatan</div>
                <div class="detail-value">
                    <?= $p['judul_kegiatan'] ? $p['judul_kegiatan'] : '-'; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="detail-label">Tanggal pengajuan</div>
                <div class="detail-value">
                    <?= date('d F Y', strtotime($p['tanggal_pengajuan'])); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="detail-label">Mulai dari tanggal</div>
                <div class="detail-value">
                    <?= date('d F Y', strtotime($p['tanggal_peminjaman'])); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Sampai tanggal</div>
                <div class="detail-value">
                    <?= date('d F Y', strtotime($p['tanggal_pengembalian'])); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="detail-label">Jenis barang</div>
                <div class="detail-value">
                    <ul class="items-list">
                        <li><?= isset($p['sub_barang']) ? $p['sub_barang'] : '-'; ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-label">Jumlah</div>
                <div class="detail-value">
                    <ul class="items-list">
                        <li><?= $p['jumlah_peminjaman']; ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="detail-label">Keterangan</div>
                <div class="detail-value">
                    <?= $p['keterangan_peminjaman'] ? $p['keterangan_peminjaman'] : '-'; ?>
                </div>
            </div>
        </div>
        
        <?php if($p['status'] == 'diproses') : ?>
            <div class="action-buttons-container">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post">
                    <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="btn-action btn-terima" onclick="return confirm('Yakin ingin menerima?')">Terima</button>
                </form>

                <button type="button" class="btn-action btn-tolak" onclick="toggleFormTolak()">Tolak</button>

                <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-action btn-dikembalikan">Dikembalikan</a>
            </div>
        <?php else: ?>
            <div class="action-buttons-container">
                <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-action btn-dikembalikan">Kembali</a>
            </div>
        <?php endif; ?>

        <div id="formTolakContainer" style="display: none;">
            <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                <input type="hidden" name="status" value="ditolak">

                <div class="catatan-section">
                    <div class="detail-label">Catatan</div>
                    <textarea class="catatan-box" name="pesan_penolakan" required></textarea>
                </div>

                <div class="btn-kirim-container">
                    <button type="submit" class="btn-kirim">Kirim</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    
</script>