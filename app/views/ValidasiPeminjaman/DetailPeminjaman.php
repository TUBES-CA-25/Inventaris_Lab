<?php
// CEK KEAMANAN AKSES
// if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2'])) {
//     header("Location:" . BASEURL . "Login");
//     exit;
// }

$p = $data['peminjaman'];
$status_sekarang = strtolower($p['status']);
$role_login = $_SESSION['id_role']; // 1=Huzain, 2=Fatimah
$status_Kembali = $data['status_Kembali'];
?>

<link rel="stylesheet" href="<?= BASEURL; ?>public/css/ValidasiPeminjaman.css">

<div class="content">
    <div class="content-beranda container-fluid p-4">

        <!-- Header Section -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-2 font-weight-bold" style="color: var(--primary-navy);">Detail Peminjaman</h1>
                <p class="text-muted mb-0">ID Peminjaman: #<?= $p['id_peminjaman']; ?></p>
            </div>

            <?php
            $statusClass = 'navy';
            if ($status_sekarang == 'disetujui') $statusClass = 'disetujui';
            if ($status_sekarang == 'tolak peminjaman') $statusClass = 'tolak peminjaman';
            if ($status_sekarang == 'tolak pengembalian') $statusClass = 'tolak pengembalian';
            if ($status_sekarang == 'diproses') $statusClass = 'diproses';
            ?>
            <span class="status-badge <?= $statusClass; ?>">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                <?= ucfirst($status_sekarang); ?>
            </span>
        </div>

        <div class="row">
            <!-- Kolom Kiri: Data Peminjam -->
            <div class="col-xl-7 col-lg-6">
                <div class="modern-card">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-user-circle mr-2"></i>Data Peminjam</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless modern-table" width="100%" cellspacing="0">
                                <tr>
                                    <th width="35%">Nama Peminjam</th>
                                    <td><strong><?= $p['nama_user']; ?></strong> <span class="text-muted">(<?= $p['nim_nip']; ?>)</span></td>
                                </tr>
                                <tr>
                                    <th>Judul Kegiatan</th>
                                    <td><?= $p['judul_kegiatan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td><?= date('d F Y', strtotime($p['tanggal_pengajuan'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Waktu Peminjaman</th>
                                    <td>
                                        <span style="color: var(--accent-blue); font-weight: 600;">
                                            <?= date('d M Y', strtotime($p['tanggal_peminjaman'])); ?>
                                        </span>
                                        <span class="mx-2">→</span>
                                        <span style="color: var(--accent-blue); font-weight: 600;">
                                            <?= date('d M Y', strtotime($p['tanggal_pengembalian'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= $p['keterangan_peminjaman'] ? $p['keterangan_peminjaman'] : '<span class="text-muted">-</span>'; ?></td>
                                </tr>
                            </table>
                        </div>

                        <hr style="border-color: #e2e8f0;">
                        <h6 class="font-weight-bold mb-3" style="color: var(--primary-navy);">
                            <i class="fas fa-boxes mr-2"></i>Barang yang Dipinjam
                        </h6>
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($data['detail_barang'])) : foreach ($data['detail_barang'] as $item) : ?>
                                    <li class="list-group-item py-3 px-0">
                                        <div class="row align-items-center mx-0" style="color: var(--text-dark);">

                                            <div class="col-md-4 col-12 mb-1 mb-md-0 pl-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-box mr-2" style="color: var(--accent-blue);"></i>
                                                    <span class="font-weight-bold">
                                                        <?= htmlspecialchars($item['nama_barang']); ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12 mb-1 mb-md-0">
                                                <?php if (!empty($item['spesifikasi'])) : ?>
                                                    <span style="font-size: 1rem; color: var(--text-dark);">
                                                        <?= $item['spesifikasi']; ?>
                                                    </span>
                                                <?php else : ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-2 col-12 text-md-right pl-md-0">
                                                <span class="badge badge-light border" style="font-size: 0.9rem;">
                                                    <?= $item['jumlah']; ?> Unit
                                                </span>
                                            </div>

                                        </div>
                                    </li>
                                <?php endforeach;
                            else : ?>
                                <li class="list-group-item text-muted">Tidak ada data barang.</li>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-3 p-3 text-center font-weight-bold" style="background: var(--primary-navy); color: white; border-radius: 10px;">
                            Total Peminjaman: <?= $p['jumlah_peminjaman']; ?> Unit
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Surat & Validasi -->
            <div class="col-xl-5 col-lg-6">

                <!-- Card Surat Permohonan -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <h6><i class="fas fa-file-alt mr-2"></i>Surat Permohonan</h6>
                    </div>
                    <div class="card-body text-center p-4">
                        <?php if (!empty($p['file_surat'])) : ?>
                            <div class="mb-3">
                                <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #dc2626, #ef4444); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
                                    <i class="fas fa-file-pdf text-white" style="font-size: 40px;"></i>
                                </div>
                                <p class="mt-3 mb-0 text-muted small"><?= $p['file_surat']; ?></p>
                            </div>
                            <a href="<?= BASEURL; ?>files/surat-peminjaman/<?= $p['file_surat']; ?>" target="_blank"
                                class="btn btn-navy btn-block">
                                <i class="fas fa-cloud-download-alt mr-2"></i>Download Surat
                            </a>
                        <?php else : ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle mr-2"></i>File surat belum diupload
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card Proses Validasi -->
                <?php if ($status_sekarang == 'diproses') : ?>
                    <div class="modern-card">
                        <div class="card-header-modern">
                            <h6><i class="fas fa-tasks mr-2"></i>Proses Validasi</h6>
                        </div>
                        <div class="card-body p-4">

                            <!-- Step 1: Kepala Lab -->
                            <div class="step-card">
                                <div class="step-icon <?= ($p['validasi_kalab'] == '1') ? 'step-success' : 'step-pending'; ?>">
                                    <?= ($p['validasi_kalab'] == '1') ? '<i class="fas fa-check"></i>' : '1'; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold mb-0" style="color: var(--text-dark);">Kepala Lab</h6>
                                    <small class="text-muted">Huzain Aziz</small>
                                </div>
                                <div>
                                    <?php if ($role_login == '1' && $p['validasi_kalab'] == '0') : ?>
                                        <form id="formAccKalab" action="<?= BASEURL; ?>ValidasiPeminjaman/accKalab" method="post" class="d-inline">
                                            <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                                            <button type="button" class="btn btn-navy btn-sm"
                                                onclick="konfirmasiAksi('formAccKalab', 'Setujui Peminjaman?', 'Yakin setujui?', 'question')">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                        </form>
                                    <?php elseif ($p['validasi_kalab'] == '1') : ?>
                                        <span class="status-badge disetujui" style="font-size: 0.75rem; padding: 4px 12px;">
                                            <i class="fas fa-check"></i> Selesai
                                        </span>
                                    <?php else : ?>
                                        <span class="badge badge-light text-muted">Menunggu</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Step 2: Laboran -->
                            <div class="step-card">
                                <div class="step-icon <?= ($p['validasi_laboran'] == '1') ? 'step-success' : (($p['validasi_kalab'] == '1') ? 'step-active' : 'step-pending'); ?>">
                                    <?= ($p['validasi_laboran'] == '1') ? '<i class="fas fa-check"></i>' : '2'; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold mb-0" style="color: var(--text-dark);">Laboran</h6>
                                    <small class="text-muted">Fatimah Azzahrah</small>
                                </div>
                                <div>
                                    <?php if ($role_login == '2' && $p['validasi_laboran'] == '0') : ?>
                                        <?php if ($p['validasi_kalab'] == '1') : ?>
                                            <a href="<?= BASEURL; ?>ValidasiPeminjaman/viewValidasiPosisi/<?= IdObfuscator::encode($p['id_peminjaman']); ?>"
                                                class="btn btn-navy btn-sm">
                                                <i class="fas fa-pen-nib mr-1"></i> Tanda Tangan
                                            </a>
                                        <?php else : ?>
                                            <button class="btn btn-secondary btn-sm" disabled style="cursor: not-allowed; opacity: 0.6;">
                                                <i class="fas fa-lock"></i> Terkunci
                                            </button>
                                        <?php endif; ?>
                                    <?php elseif ($p['validasi_laboran'] == '1') : ?>
                                        <span class="status-badge disetujui" style="font-size: 0.75rem; padding: 4px 12px;">
                                            <i class="fas fa-check"></i> Selesai
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr style="border-color: #e2e8f0; margin: 20px 0;">
                            <button type="button" class="btn btn-outline-danger btn-block btn-sm mt-3"
                                onclick="bukaFormTolak('formTolakContainer')">
                                <i class="fas fa-times-circle mr-1"></i> Tolak Peminjaman
                            </button>

                        </div>
                    </div>

                <?php elseif ($status_sekarang == 'disetujui') : ?>
                    <div class="modern-card" style="border-left: 4px solid #22c55e;">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div style="width: 60px; height: 60px; margin: 0 auto; background: var(--success-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-check-circle" style="font-size: 30px; color: var(--success-text);"></i>
                                </div>
                            </div>
                            <h5 class="font-weight-bold text-center mb-2" style="color: var(--success-text);">Sedang Dipinjam</h5>
                            <p class="text-center text-muted mb-4">Barang sudah diambil. Tunggu pengembalian.</p>

                            <form id="formTerimaKembali" action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" class="mb-2">
                                <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                                <input type="hidden" name="status" value="dikembalikan">

                                <?php
                                // AMBIL STATUS DENGAN AMAN (Cegah Error jika Controller belum update)
                                // Gunakan data dari $status_Kembali jika ada, jika tidak pakai '-'
                                $statusCek = isset($status_Kembali) ? $status_Kembali : '-';

                                if ($statusCek == 'Selesai Periksa') {
                                    // Skenario 1: SUDAH DIPERIKSA (Aman)
                                    $judulPopup = 'Terima Barang?';
                                    $pesanPopup = 'Pastikan fisik barang sudah dicek.';
                                    $iconPopup  = 'question';
                                    $warnaBtn   = '#0d1b3e'; // Navy
                                } else {
                                    // Skenario 2: BELUM DIPERIKSA (Peringatan)
                                    $judulPopup = 'Peringatan!';
                                    $pesanPopup = 'Barang belum dikembalikan dan diperiksa, yakin terima pengembalian?';
                                    $iconPopup  = 'warning';
                                    $warnaBtn   = '#d33'; // Merah
                                }
                                ?>

                                <button type="button" class="btn btn-navy btn-block py-3"
                                    onclick="konfirmasiAksi('formTerimaKembali', '<?= $judulPopup; ?>', '<?= $pesanPopup; ?>', '<?= $iconPopup; ?>', '<?= $warnaBtn; ?>')">
                                    <i class="fas fa-check-circle mr-2"></i>Terima Pengembalian
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="bukaFormTolak('formTolakPengembalianContainer')">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Keterangan Masalah Pengembalian
                            </button>
                        </div>
                    </div>

                <?php elseif ($status_sekarang == 'tolak peminjaman') : ?>
                    <div class="modern-card" style="border-left: 4px solid #ef4444;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div style="width: 60px; height: 60px; margin: 0 auto; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-excel" style="font-size: 30px; color: #ef4444;"></i>
                                </div>
                            </div>
                            <h5 class="font-weight-bold text-danger mb-3">Pengajuan Ditolak</h5>
                            <div class="condition-box danger text-left">
                                <strong class="d-block mb-1">Alasan Penolakan:</strong>
                                <p class="mb-0"><?= !empty($p['keterangan_peminjaman']) ? $p['keterangan_peminjaman'] : '-'; ?></p>
                            </div>
                        </div>
                    </div>
                <?php elseif ($status_sekarang == 'tolak pengembalian') : ?>
                    <div class="modern-card" style="border-left: 4px solid #f97316;">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div style="width: 60px; height: 60px; margin: 0 auto; background: #ffedd5; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 30px; color: #f97316;"></i>
                                </div>
                            </div>
                            <h5 class="font-weight-bold mb-3" style="color: #c2410c;">Masalah Pengembalian</h5>

                            <div class="alert alert-warning text-left border-0" style="background-color: #fff7ed; color: #9a3412;">
                                <strong><i class="fas fa-info-circle mr-1"></i> Detail Masalah:</strong><br>
                                <?= !empty($p['keterangan_tolak']) ? $p['keterangan_tolak'] : '-'; ?>
                            </div>

                            <form id="formSelesaiMasalah" action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" class="mt-3">
                                <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                                <input type="hidden" name="status" value="dikembalikan">
                                <button type="button" class="btn btn-navy btn-block"
                                    onclick="konfirmasiAksi('formSelesaiMasalah', 'Masalah Teratasi?', 'Barang sudah aman?', 'question')">
                                    <i class="fas fa-check-double mr-2"></i>Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Penolakan (Hidden) -->
        <div id="formTolakContainer" class="modern-card form-section-hidden" style="border-left: 4px solid #ef4444;">
            <div class="card-header-modern" style="background: #ef4444;">
                <h6><i class="fas fa-times-circle mr-2"></i>Form Penolakan</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post">
                    <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                    <input type="hidden" name="status" value="tolak peminjaman">
                    <div class="form-group">
                        <label class="font-weight-bold" style="color: var(--text-dark);">Alasan Penolakan:</label>
                        <textarea class="form-control" name="pesan_penolakan" required rows="4" placeholder="Contoh: Jadwal bentrok dengan kegiatan lain..." style="border-radius: 8px;"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="tutupForm('formTolakContainer')" style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn btn-danger" style="border-radius: 8px; padding: 10px 24px;">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Lapor Masalah Pengembalian (Hidden) -->
        <div id="formTolakPengembalianContainer" class="modern-card form-section-hidden" style="border-left: 4px solid #ef4444;">
            <div class="card-header-modern" style="background: #ef4444;">
                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Lapor Masalah Pengembalian</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/tolakPengembalian" method="post">
                    <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                    <div class="form-group">
                        <label class="font-weight-bold" style="color: var(--text-dark);">Detail Masalah (Rusak/Hilang):</label>
                        <textarea class="form-control" name="alasan_penolakan" required rows="4" placeholder="Jelaskan kondisi barang yang rusak atau hilang..." style="border-radius: 8px;"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="tutupForm('formTolakPengembalianContainer')" style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn btn-danger" style="border-radius: 8px; padding: 10px 24px;">
                            <i class="fas fa-save mr-2"></i>Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Detail Barang & Status Pengembalian -->
        <?php if ($status_sekarang == 'disetujui' || $status_sekarang == 'dikembalikan') : ?>
            <div class="modern-card mt-4">
                <div class="card-header-modern">
                    <h6><i class="fas fa-clipboard-list mr-2"></i>Detail Barang & Status Pengembalian</h6>
                </div>
                <div class="card-body p-4">
                    <?php if ($status_Kembali == 'Selesai Periksa') : ?>

                        <div class="row px-0 mb-3 d-none d-md-flex" style="color: #888; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            <div class="col-md-3 pl-4">Nama Barang</div>
                            <div class="col-md-3">Spesifikasi</div>
                            <div class="col-md-3">Kondisi Fisik</div>
                            <div class="col-md-3">Keterangan</div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <?php if (!empty($data['detail_barang'])) : ?>
                                <?php foreach ($data['detail_barang'] as $item) : ?>
                                    <li class="list-group-item px-0 py-3">

                                        <div class="row align-items-center mx-0" style="color: #333; font-size: 1rem; font-weight: 500;">

                                            <div class="col-md-3 col-12 mb-2 mb-md-0 pl-md-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-box mr-2" style="color: var(--accent-blue);"></i>
                                                    <?= htmlspecialchars($item['nama_barang']); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12 mb-2 mb-md-0">
                                                <?= !empty($item['spesifikasi']) ? $item['spesifikasi'] : '-'; ?>
                                            </div>

                                            <div class="col-md-3 col-12 mb-2 mb-md-0">
                                                <?php
                                                if (!empty($item['kondisi_kembali'])) {
                                                    $color = '#333';
                                                    $icon = '';

                                                    if ($item['kondisi_kembali'] == 'Baik') {
                                                        $color = '#1cc88a';
                                                        $icon = 'fa-check';
                                                    } elseif ($item['kondisi_kembali'] == 'Rusak') {
                                                        $color = '#f6c23e';
                                                        $icon = 'fa-exclamation-triangle';
                                                    } elseif ($item['kondisi_kembali'] == 'Hilang') {
                                                        $color = '#e74a3b';
                                                        $icon = 'fa-times';
                                                    }

                                                    echo '<span style="color: ' . $color . ';">';
                                                    echo '<i class="fas ' . $icon . ' mr-1"></i> ' . ucfirst($item['kondisi_kembali']);
                                                    echo '</span>';
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <?php if (!empty($item['ket_kembali']) && $item['ket_kembali'] != '-') : ?>
                                                    <span style="color: #555; font-size: 0.95rem;">
                                                        <?= htmlspecialchars($item['ket_kembali']); ?>
                                                    </span>
                                                <?php else : ?>
                                                    <span class="text-muted small font-italic">Tidak ada catatan</span>
                                                <?php endif; ?>
                                            </div>

                                        </div>

                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li class="list-group-item text-muted text-center">Tidak ada data barang.</li>
                            <?php endif; ?>
                        </ul>

                    <?php else : ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-clock mb-2" style="font-size: 2rem; color: #cbd5e0;"></i>
                            <p class="mb-0">Barang belum diperiksa oleh laboran.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mb-5 mt-4">
            <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn btn-navy px-4 py-2">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<style>
    /* Form Hidden Animation */
    .form-section-hidden {
        display: none;
        margin-top: 20px;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .status-badge {
            font-size: 0.75rem;
            padding: 4px 12px;
        }

        .step-card {
            padding: 12px;
        }

        .step-icon {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
    }
</style>

<script>
    function bukaFormTolak(id) {
        // Tutup semua form dulu
        document.getElementById('formTolakContainer').style.display = 'none';
        document.getElementById('formTolakPengembalianContainer').style.display = 'none';

        var el = document.getElementById(id);
        if (el) {
            el.style.display = 'block';
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    function tutupForm(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }

    function konfirmasiAksi(formId, judul, pesan, ikon, warnaTombol) {

        // Set default warna jika tidak dikirim (Fallback ke Navy)
        if (!warnaTombol) warnaTombol = '#0d1b3e';

        Swal.fire({
            title: judul,
            text: pesan,
            icon: ikon,
            showCancelButton: true,
            confirmButtonColor: warnaTombol, // Warna dinamis (Merah/Navy)
            cancelButtonColor: '#5a5c69',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>