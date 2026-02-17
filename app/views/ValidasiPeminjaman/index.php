<?php
// Cek sesi login & role
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<main class="content">
    <div class="content-beranda">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Validasi Peminjaman</h1>
            <a href="<?= BASEURL; ?>ValidasiPeminjaman/kirimNotifikasi" class="btn btn-navy">
                <i class="fas fa-envelope mr-2"></i>Kirim Notifikasi Email
            </a>
        </div>

        <section class="stats-overview row g-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-navy">
                    <div>
                        <div class="stat-label">Total diterima</div>
                        <div class="stat-value"><?= isset($data['total_disetujui']) ? $data['total_disetujui'] : 0; ?>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total diproses</div>
                        <div class="stat-value"><?= isset($data['total_diproses']) ? $data['total_diproses'] : 0; ?>
                        </div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total ditolak</div>
                        <div class="stat-value"><?= isset($data['total_ditolak']) ? $data['total_ditolak'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total Pengembalian</div>
                        <div class="stat-value"><?= isset($data['total_kembali']) ? $data['total_kembali'] : 0; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="table-responsive mt-4">
            <table id="myTable" class="table table-hover" style="width:100%; margin-bottom: 0;">
                <thead class="table-custom-header">
                    <tr>
                        <th>No</th>
                        <th>Nama Peminjam</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Akhir</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th class="text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if (empty($data['peminjaman'])): ?>
                    <?php else: ?>
                        <?php foreach ($data['peminjaman'] as $pinjam): ?>
                            <tr>
                                <td><?= $no++; ?></td>

                                <td class="font-weight-bold"><?= htmlspecialchars($pinjam['nama_user']); ?></td>

                                <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengajuan'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($pinjam['tanggal_peminjaman'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengembalian'])); ?></td>

                                <td>
                                    <?php
                                    // Badge logic tetap menggunakan warna asli Bootstrap/Anda
                                    $status = strtolower($pinjam['status']);
                                    $badgeClass = 'bg-secondary';
                                    $is_overdue = false;

                                    // Cek Keterlambatan
                                    // Jika belum dikembalikan/ditolak DAN hari ini > tgl_pengembalian
                                    if (!in_array($status, ['dikembalikan', 'ditolak', 'tolak peminjaman'])) {
                                        $tgl_kembali = strtotime($pinjam['tanggal_pengembalian']);
                                        $today = strtotime(date('Y-m-d')); // Bandingkan per hari ini
                            
                                        if ($today > $tgl_kembali) {
                                            $is_overdue = true;
                                        }
                                    }

                                    if ($status == 'diproses')
                                        $badgeClass = 'bg-warning text-dark';
                                    elseif ($status == 'disetujui')
                                        $badgeClass = 'bg-success';
                                    elseif ($status == 'ditolak' || $status == 'tolak peminjaman')
                                        $badgeClass = 'bg-danger';
                                    elseif ($status == 'dikembalikan')
                                        $badgeClass = 'bg-primary';
                                    ?>

                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="badge text-white rounded-pill <?= $badgeClass; ?> px-3 py-2">
                                            <?= ucfirst($pinjam['status']); ?>
                                        </span>

                                        <?php if ($is_overdue): ?>
                                            <span class="badge bg-danger rounded-pill px-2 py-1 mt-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-exclamation-circle"></i> Terlambat
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td><?= !empty($pinjam['keterangan_peminjaman']) ? $pinjam['keterangan_peminjaman'] : '-'; ?>
                                </td>

                                <td class="text-center">
                                    <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= IdObfuscator::encode($pinjam['id_peminjaman']); ?>"
                                        class="btn-detail" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</main>