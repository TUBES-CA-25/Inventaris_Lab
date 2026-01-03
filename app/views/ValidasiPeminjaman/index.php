<?php
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>
<div class="content">
    <div class="container-fluid p-4">
        <h1 class="page-title">Validasi peminjaman</h1>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-navy">
                    <div>
                        <div class="stat-label">Total diterima</div>
                        <div class="stat-value">120</div>
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
                        <div class="stat-value">150</div>
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
                        <div class="stat-value">10</div>
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
                        <div class="stat-value">10</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i> </div>
                </div>
            </div>
        </div>

        <div class="custom-table-container">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul kegiatan</th>
                            <th>Tgl pengajuan</th>
                            <th>Tgl mulai peminjaman</th>
                            <th>Tgl akhir peminjaman</th>
                            <th>Status</th>
                            <th>Keterangan</th> <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($data['peminjaman'])) : ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <span class="text-muted">Tidak ada data peminjaman yang perlu divalidasi.</span>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($data['peminjaman'] as $pinjam) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($pinjam['nama_peminjam']); ?></td> <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengajuan'])); ?></td>
                                    <td><?= date('d/m/Y', strtotime($pinjam['tanggal_peminjaman'])); ?></td>
                                    <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengembalian'])); ?></td>

                                    <td>
                                        <?php
                                        // Badge logic
                                        $badgeClass = 'bg-secondary';
                                        if ($pinjam['status'] == 'diproses') $badgeClass = 'bg-warning text-dark';
                                        if ($pinjam['status'] == 'disetujui') $badgeClass = 'bg-success';
                                        if ($pinjam['status'] == 'ditolak') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass; ?> px-3 py-2">
                                            <?= ucfirst($pinjam['status']); ?>
                                        </span>
                                    </td>
                                    
                                    <td>-</td> 

                                    <td class="text-center">
                                        <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $pinjam['id_peminjaman']; ?>" 
                                            class="btn-detail tampilModalValidasi" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>