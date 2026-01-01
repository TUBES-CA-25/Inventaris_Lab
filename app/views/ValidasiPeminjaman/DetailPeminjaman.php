<div class="content">
    <div class="container-fluid">
        <h1 class="page-title">Detail peminjaman</h1>

        <?php $p = $data['peminjaman']; ?>

        <div class="info-item">
            <div class="label">Judul kegiatan</div>
            <div class="value"><?= $p['judul_kegiatan'] ? $p['judul_kegiatan'] : '-'; ?></div>
        </div>

        <div class="info-item">
            <div class="label">Tanggal pengajuan</div>
            <div class="value"><?= date('d F Y', strtotime($p['tanggal_pengajuan'])); ?></div>
        </div>

        <div class="row-custom">
            <div class="col-custom">
                <div class="label">Mulai dari tanggal</div>
                <div class="value"><?= date('d F Y', strtotime($p['tanggal_peminjaman'])); ?></div>
            </div>
            <div class="col-custom">
                <div class="label">Sampai tanggal</div>
                <div class="value"><?= date('d F Y', strtotime($p['tanggal_pengembalian'])); ?></div>
            </div>
        </div>

        <div class="row-custom">
            <div class="col-custom">
                <div class="label">Jenis barang</div>
                <ul class="items-list">
                    <li><?= isset($p['sub_barang']) ? $p['sub_barang'] : '-'; ?></li>
                </ul>
            </div>
            <div class="col-custom">
                <div class="label">Jumlah</div>
                <ul class="items-list">
                    <li><?= $p['jumlah_peminjaman']; ?></li>
                </ul>
            </div>
        </div>

        <div class="info-item">
            <div class="label">Keterangan</div>
            <div class="value"><?= $p['keterangan_peminjaman'] ? $p['keterangan_peminjaman'] : '-'; ?></div>
        </div>
        
        <div class="info-item">
            <div class="label">Status Saat Ini</div>
            <div class="value" style="text-transform: capitalize; font-weight:600;">
                <?= $p['status']; ?>
            </div>
        </div>

        <?php if($p['status'] == 'diproses') : ?>
        <div class="action-buttons">
            <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" style="display:inline;">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                <input type="hidden" name="status" value="disetujui">
                <button type="submit" class="btn-custom btn-terima" onclick="return confirm('Yakin ingin menerima?')">Terima</button>
            </form>

            <button type="button" class="btn-custom btn-tolak" onclick="toggleFormTolak()">Tolak</button>

            <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-custom btn-dikembalikan">Kembali</a>
        </div>
        <?php else: ?>
            <div class="action-buttons">
                <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-custom btn-dikembalikan">Kembali</a>
            </div>
        <?php endif; ?>

        <div id="formTolakContainer" style="display: none;">
            <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" class="clearfix">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                <input type="hidden" name="status" value="ditolak">

                <div class="catatan-section">
                    <div class="label">Catatan</div>
                    <textarea class="catatan-box" name="pesan_penolakan" required placeholder=""></textarea>
                </div>

                <button type="submit" class="btn-kirim">Kirim</button>
            </form>
        </div>

    </div>
</div>

<script>
    // Script untuk memunculkan/menyembunyikan form catatan saat tombol Tolak diklik
    function toggleFormTolak() {
        var formContainer = document.getElementById("formTolakContainer");
        if (formContainer.style.display === "none") {
            formContainer.style.display = "block";
            // Scroll otomatis ke bagian catatan
            formContainer.scrollIntoView({ behavior: 'smooth' });
        } else {
            formContainer.style.display = "none";
        }
    }
</script>