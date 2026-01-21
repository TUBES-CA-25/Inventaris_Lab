<?php
// Cek sesi login & role
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// Ambil data & standarkan status jadi huruf kecil
$p = $data['peminjaman'];
$status_sekarang = strtolower($p['status']);
?>

<link rel="stylesheet" href="<?= BASEURL; ?>css/validasi-peminjaman.css">
<style>
    /* Styling Tambahan untuk Form Tolak */
    .form-container-custom { display: none; margin-top: 20px; padding: 20px; border-radius: 8px; animation: fadeIn 0.3s ease-in-out; }
    .form-tolak-normal { background-color: #f8f9fa; border: 1px solid #dee2e6; }
    .form-tolak-danger { background-color: #fff5f5; border: 1px solid #ef4444; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="content">
    <div class="container-fluid p-4">
        <h1 class="page-title mb-4">Detail Peminjaman</h1>

        <div class="d-flex justify-content-between align-items-start">
            <div class="w-100 me-3">
                <div class="row mb-3">
                    <div class="col-12"><div class="detail-label">Judul kegiatan</div><div class="detail-value"><?= $p['judul_kegiatan'] ? $p['judul_kegiatan'] : '-'; ?></div></div>
                </div>
                <div class="row mb-3">
                    <div class="col-12"><div class="detail-label">Tanggal pengajuan</div><div class="detail-value"><?= date('d F Y', strtotime($p['tanggal_pengajuan'])); ?></div></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><div class="detail-label">Mulai tanggal</div><div class="detail-value"><?= date('d F Y', strtotime($p['tanggal_peminjaman'])); ?></div></div>
                    <div class="col-md-6"><div class="detail-label">Sampai tanggal</div><div class="detail-value"><?= date('d F Y', strtotime($p['tanggal_pengembalian'])); ?></div></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="detail-label">Barang</div>
                        <ul class="items-list" style="padding-left:15px; margin:0;">
                            <?php if (!empty($data['detail_barang'])) : foreach ($data['detail_barang'] as $item) : ?>
                                <li><?= htmlspecialchars($item['nama_barang']); ?></li>
                            <?php endforeach; else : ?><li>-</li><?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Jumlah</div>
                        <ul class="items-list" style="list-style:none; padding:0; margin:0;">
                            <?php if (!empty($data['detail_barang'])) : foreach ($data['detail_barang'] as $item) : ?>
                                <li><?= $item['jumlah']; ?> Unit</li>
                            <?php endforeach; ?>
                            <li style="margin-top:5px; border-top:1px dashed #ccc;"><strong>Total: <?= $p['jumlah_peminjaman']; ?> Unit</strong></li>
                        <?php else : ?><li>0 Unit</li><?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12"><div class="detail-label">Keterangan / Alasan</div><div class="detail-value"><?= $p['keterangan_peminjaman'] ? $p['keterangan_peminjaman'] : '-'; ?></div></div>
                </div>
            </div>

            <div class="flex-shrink-0">
                <?php if (!empty($p['file_surat'])) : ?>
                    <a href="<?= BASEURL; ?>files/surat-peminjaman/<?= $p['file_surat']; ?>" target="_blank" class="btn-pdf">
                        <i class="fas fa-file-pdf" style="font-size:30px; display:block; margin-bottom:5px;"></i><span>Lihat PDF</span>
                    </a>
                <?php else : ?>
                    <div class="btn-pdf-disabled"><i class="fas fa-file-pdf" style="font-size:30px; display:block; margin-bottom:5px;"></i><span>No File</span></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="action-buttons-container mt-5 pt-3 border-top">

            <?php if ($status_sekarang == 'diproses') : ?>
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" style="display:inline;">
                    <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="btn-action btn-terima" onclick="return confirm('Yakin ingin menyetujui?')">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </form>
                <button type="button" class="btn-action btn-tolak" onclick="toggleFormTolak()">
                    <i class="fas fa-times"></i> Tolak
                </button>

            <?php elseif ($status_sekarang == 'disetujui') : ?>
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" style="display:inline;">
                    <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                    <input type="hidden" name="status" value="dikembalikan">
                    <button type="submit" class="btn-action btn-kembali-barang" onclick="return confirm('Barang sudah dikembalikan lengkap?')">
                        <i class="fas fa-box-open"></i> Terima Barang
                    </button>
                </form>
                <button type="button" class="btn-action btn-tolak" onclick="toggleFormTolakPengembalian()">
                    <i class="fas fa-exclamation-triangle"></i> Tolak Pengembalian
                </button>

            <?php elseif ($status_sekarang == 'ditolak') : ?>
                <div class="alert alert-danger w-100 d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Pengembalian Ditolak.</strong><br>
                        Alasan: "<?= !empty($p['alasan_penolakan']) ? $p['alasan_penolakan'] : '-'; ?>"
                    </div>
                </div>

                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" style="display:inline;">
                    <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                    <input type="hidden" name="status" value="dikembalikan">
                    <button type="submit" class="btn-action btn-kembali-barang" onclick="return confirm('Ubah status menjadi Diterima/Dikembalikan?')">
                        <i class="fas fa-undo"></i> Ubah jadi Diterima
                    </button>
                </form>

                <button type="button" class="btn-action btn-tolak" onclick="toggleFormTolakPengembalian()">
                    <i class="fas fa-edit"></i> Edit Penolakan
                </button>

            <?php else: ?>
                <div class="alert alert-secondary w-100">
                    Status saat ini: <strong><?= ucfirst($status_sekarang); ?></strong>
                </div>
            <?php endif; ?>

        </div>

        <div id="formTolakContainer" class="form-container-custom form-tolak-normal">
            <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                <input type="hidden" name="status" value="ditolak">
                <div class="catatan-section">
                    <div class="detail-label" style="color:var(--btn-red)">Alasan Penolakan:</div>
                    <textarea class="catatan-box form-control mt-2" name="pesan_penolakan" required placeholder="Tulis alasan..." rows="3"></textarea>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn-action btn-tolak">Kirim</button>
                    <button type="button" class="btn btn-secondary ms-2" onclick="toggleFormTolak()">Batal</button>
                </div>
            </form>
        </div>

        <div id="formTolakPengembalianContainer" class="form-container-custom form-tolak-danger">
            <h5 style="color:#ef4444; font-weight:bold;">
                <?= ($status_sekarang == 'ditolak') ? 'Edit Alasan Penolakan' : 'Tolak Pengembalian Barang'; ?>
            </h5>
            <p style="font-size:13px; color:#666;">Data ini akan disimpan di tabel Penolakan Pengembalian.</p>
            
            <form action="<?= BASEURL; ?>ValidasiPeminjaman/tolakPengembalian" method="post">
                <input type="hidden" name="id_peminjaman" value="<?= $p['id_peminjaman']; ?>">
                <div class="catatan-section">
                    <div class="detail-label" style="color:#ef4444">Alasan / Masalah:</div>
                    <textarea class="catatan-box form-control mt-2" name="alasan_penolakan" required placeholder="Contoh: Barang lecet, kabel hilang..." rows="3"><?= !empty($p['alasan_penolakan']) ? $p['alasan_penolakan'] : ''; ?></textarea>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn-action btn-tolak">
                        <?= ($status_sekarang == 'ditolak') ? 'Simpan Perubahan' : 'Kirim Penolakan'; ?>
                    </button>
                    <button type="button" class="btn btn-secondary ms-2" onclick="toggleFormTolakPengembalian()">Batal</button>
                </div>
            </form>
        </div>

        <div class="bottom-nav-container mt-4">
            <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn-nav-kembali text-decoration-none"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<script>
    // 1. Toggle Form Tolak Biasa
    function toggleFormTolak() {
        var formTolak = document.getElementById("formTolakContainer");
        var formPengembalian = document.getElementById("formTolakPengembalianContainer");
        
        if(formPengembalian) formPengembalian.style.display = "none"; // Tutup yang lain

        if (formTolak.style.display === "none" || formTolak.style.display === "") {
            formTolak.style.display = "block";
            formTolak.scrollIntoView({ behavior: 'smooth' });
        } else {
            formTolak.style.display = "none";
        }
    }

    // 2. Toggle Form Tolak Pengembalian (YANG HILANG SEBELUMNYA)
    function toggleFormTolakPengembalian() {
        var formTolak = document.getElementById("formTolakContainer");
        var formPengembalian = document.getElementById("formTolakPengembalianContainer");

        if(formTolak) formTolak.style.display = "none"; // Tutup yang lain

        if (formPengembalian.style.display === "none" || formPengembalian.style.display === "") {
            formPengembalian.style.display = "block";
            formPengembalian.scrollIntoView({ behavior: 'smooth' });
        } else {
            formPengembalian.style.display = "none";
        }
    }
</script>